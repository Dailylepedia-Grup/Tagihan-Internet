<?php

namespace Midtrans;

require_once dirname(__FILE__) . '/../vendor/autoload.php';

include("../include/routeros_api.php");
include("../include/koneksi.php");

$queryP = $koneksi->query("SELECT * FROM tbl_pgate WHERE id_pgat=1");
$tokenP = $queryP->fetch_assoc();
$clienttKey = $tokenP['tclientkey'];
$serverrKey = $tokenP['tserverkey'];

Config::$serverKey = $serverrKey;

Config::$isProduction = true;
// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

try {
    $notif = new Notification();
} catch (\Exception $e) {
    exit($e->getMessage());
}

$notif = $notif->getResponse();
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

if ($transaction == 'capture') {
    // For credit card transaction, we need to check whether transaction is challenge by FDS or not
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            // TODO set payment status in merchant's database to 'Challenge by FDS'
            // TODO merchant should decide whether this transaction is authorized or not in MAP
            echo "Transaction order_id: " . $order_id . " is challenged by FDS";
        } else {
            // TODO set payment status in merchant's database to 'Success'
            echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;
        }
    }
} else if ($transaction == 'settlement') {

    $bulan = date('m');
    $tgl2 = date('Y');
    $sql = $koneksi->query("SELECT no_invoice FROM tb_tagihan ORDER BY no_invoice DESC");
    $data = $sql->fetch_assoc();
    $no_spt = $data['no_invoice'];
    $urut = substr($no_spt, 0, 5);
    $tambah = (int) $urut + 1;
    if (strlen($tambah) == 1) {
        $format = "0000" . $tambah . ".BLR.MST.";
    } else if (strlen($tambah) == 2) {
        $format = "000" . $tambah . ".BLR.MST.";
    } else if (strlen($tambah) == 3) {
        $format = "00" . $tambah . ".BLR.MST.";
    } else if (strlen($tambah) == 4) {
        $format = "0" . $tambah . ".BLR.MST.";
    } else {
        $format = $tambah . ".BLR.MST.";
    }

    $pieces = explode("-", $order_id);
    $id_tagihan = $pieces[0];

    $tgl_bayar = date('Y-m-d');

    $sql_tagihan = $koneksi->query("SELECT * FROM tb_tagihan, tb_pelanggan, tb_paket, tb_user WHERE tb_pelanggan.id_pelanggan=tb_tagihan.id_pelanggan AND tb_pelanggan.id_pelanggan=tb_user.id_pelanggan AND tb_paket.id_paket=tb_pelanggan.paket AND tb_tagihan.id_tagihan='$id_tagihan'");
    $data_tagihan = $sql_tagihan->fetch_assoc();

    $sql_mikrotik = $koneksi->query("SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1");
    $row = $sql_mikrotik->fetch_assoc();

    $id_pelanggan = $data_tagihan['id_pelanggan'];
    $jatuh_tempo = $data_tagihan['jatuh_tempo'];
    $jml_bayar = $data_tagihan['jml_bayar'];
    $pelanggan = $data_tagihan['nama_pelanggan'];
    $paket = $data_tagihan['nama_paket'];
    // $ip_address = $data_tagihan['ip_address'];
    $no_telp = $data_tagihan['no_telp'];
    $sekarangs = date('d F Y H:i:s');
    $ket = "Pembayaran Internet AN." . "&nbsp" . $pelanggan . "," . "&nbsp" . "Paket" . "&nbsp" . $paket;
    $tgl_pemasangan_obj = new \DateTime($jatuh_tempo);
    $jam_sekarang = date('H:i:s'); // Mendapatkan jam saat ini
    $tanggal_sekarang = date('Y-m-d'); // Mendapatkan tanggal saat ini
    // Jika tanggal pembayaran lebih kecil dari tanggal jatuh tempo, tambahkan 1 bulan ke tanggal jatuh tempo
    if ($tanggal_sekarang < $jatuh_tempo) {
        $tgl_pemasangan_obj->modify('+1 Month');
    }

    $conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
    $checkUser = $conPelanggan->fetch_assoc();

    if (($checkUser['status'] == 'ya') || ($checkUser['ippelanggan'] == 'dynamic')) {
        $ip_address = $data_tagihan['username'];
    } else {
        $ip_address = $data_tagihan['ip_address'];
    }

    if (!empty($row)) {
        $API = new \RouterosAPI();
        if ($API->connect($row['ip'], $row['username'], $row['password'])) {

            if ($checkUser['ippelanggan'] == 'statik') {
                $ip_address = mysqli_real_escape_string($koneksi, $ip_address);
                $sql_pelanggan = $koneksi->query("SELECT * FROM tb_pelanggan WHERE ip_address = '$ip_address'");

                while ($row_pelanggan = $sql_pelanggan->fetch_assoc()) {

                    $ip_pelanggan = $row_pelanggan['ip_address'];

                    $commentToSearch = "Blokir Bulanan " . $ip_pelanggan;

                    $API->write('/ip/firewall/address-list/print', false);
                    $API->write('?comment=' . $commentToSearch);

                    $ips = $API->read();

                    if (!empty($ips)) {
                        // Proses entri address-list yang ditemukan
                        foreach ($ips as $ip_data) {
                            // Hapus entri address-list berdasarkan ID yang ditemukan
                            $API->write('/ip/firewall/address-list/remove', false);
                            $API->write('=.id=' . $ip_data['.id']);
                            $API->read();
                            echo "Berhasil menghapus entri address-list dengan komentar: " . $ip_data['comment'] . "<br>";
                        }
                    } else {
                        echo "Tidak ada entri address-list yang ditemukan.";
                    }
                }
            } else {
                $sql_pelanggan = "SELECT * FROM tb_user WHERE username = '$ip_address'";
                $result_pelanggan = mysqli_query($koneksi, $sql_pelanggan);

                while ($row_pelanggan = mysqli_fetch_assoc($result_pelanggan)) {
                    $username = $row_pelanggan['username']; // Ambil nama pelanggan

                    // Menonaktifkan PPP Secret
                    $API->comm("/ppp/secret/enable", array(
                        "numbers" => $username,
                    ));
                }
            }
        }
    }

    $tgl_jatuh_tempo = $tgl_pemasangan_obj->format('Y-m-d') . ' ' . $jam_sekarang;
    $sql2 = $koneksi->query("UPDATE tb_tagihan SET terbayar='$jml_bayar', status_bayar=1, tgl_bayar='$tgl_bayar', blokir_status=NULL, no_invoice='$format', waktu_bayar=NOW(), user_id=NULL WHERE id_tagihan='$id_tagihan'");
    $sql_test = $koneksi->query("UPDATE tb_pelanggan SET jatuh_tempo='$tgl_jatuh_tempo' WHERE id_pelanggan='$id_pelanggan'");
    $query3 = $koneksi->query("INSERT INTO tb_kas (tgl_kas, keterangan, penerimaan, id_tagihan)VALUES('$tgl_bayar', '$ket', '$jml_bayar', '$id_tagihan') ");

    $sql_token = $koneksi->query("SELECT * FROM tbl_token WHERE id_token = 1");
    $row = $sql_token->fetch_assoc();

    if (!empty($row)) {
        $authorizationToken = $row['token'];

        $sql_notifbayar = $koneksi->query("SELECT * FROM tbl_notifbayar");
        $bayar = $sql_notifbayar->fetch_assoc();

        if (!empty($bayar)) {
            $pesanBayar = $bayar['pesan_bayar'];
            $pesanBayar = str_replace('$nama', $pelanggan, $pesanBayar);
            $formatJmlByr = 'Rp. ' . number_format($jml_bayar, 0, ',', '.');
            $pesanBayar = str_replace('$tagihan', $formatJmlByr, $pesanBayar);
            $pesanBayar = str_replace('$harinin', $sekarangs, $pesanBayar);
            $pesanBayar = str_replace('$no_telp', $no_telp, $pesanBayar);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $no_telp,
                    'message' => $pesanBayar,
                    'countryCode' => '62', //optional
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $authorizationToken //change TOKEN to your actual token
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;
        } else {
        }
    } else {
    }
} else if ($transaction == 'pending') {
    // TODO set payment status in merchant's database to 'Pending'
    echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
} else if ($transaction == 'deny') {
    // TODO set payment status in merchant's database to 'Denied'
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
} else if ($transaction == 'expire') {
    // TODO set payment status in merchant's database to 'expire'
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
} else if ($transaction == 'cancel') {
    // TODO set payment status in merchant's database to 'Denied'
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
}

function printExampleWarningMessage()
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo 'Notification-handler are not meant to be opened via browser / GET HTTP method. It is used to handle Midtrans HTTP POST notification / webhook.';
    }
    if (strpos(Config::$serverKey, 'your ') != false) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
        die();
    }
}
