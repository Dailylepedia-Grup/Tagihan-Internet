<?php
include('../../include/routeros_api.php');
include "../../include/koneksi.php";

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

$id_tagihan = $_GET['id'];
$ip_address = $_GET['ipaddress'];

$tgl_bayar = date('Y-m-d');
$sql = $koneksi->query("SELECT * FROM tb_tagihan, tb_pelanggan, tb_paket WHERE tb_pelanggan.id_pelanggan=tb_tagihan.id_pelanggan AND tb_paket.id_paket=tb_pelanggan.paket AND tb_tagihan.id_tagihan='$id_tagihan'");
$sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
$result = mysqli_query($koneksi, $sql_mikrotik);
$row = mysqli_fetch_assoc($result);
$data = $sql->fetch_assoc();
$id_pelanggan = $data['id_pelanggan'];
$jatuh_tempo = $data['jatuh_tempo'];
$jml_bayar = $data['jml_bayar'];
$pelanggan = $data['nama_pelanggan'];
$paket = $data['nama_paket'];

// $ip_address = $data['ip_address'];

$ket = "Pembayaran Internet AN." . "&nbsp" . $pelanggan . "," . "&nbsp" . "Paket" . "&nbsp" . $paket;
$tgl_pemasangan_obj = new DateTime($jatuh_tempo);
$jam_sekarang = date('H:i:s');
$tanggal_sekarang = date('Y-m-d');

if ($tanggal_sekarang < $jatuh_tempo) {
    $tgl_pemasangan_obj = new DateTime($jatuh_tempo);
    $tgl_pemasangan_obj->modify('+1 Month');
} else {
    $tgl_pemasangan_obj = new DateTime($tanggal_sekarang);
    $tgl_pemasangan_obj->modify('+1 Month');
}

if (!empty($row)) {

    $API = new RouterosAPI();

    if ($API->connect($row['ip'], $row['username'], $row['password'])) {

        $conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
        $checkUser = $conPelanggan->fetch_assoc();

        if ($checkUser['ippelanggan'] == 'statik') {
            $sql_pelanggan = "SELECT * FROM tb_pelanggan WHERE ip_address = '$ip_address'";
            $result_pelanggan = mysqli_query($koneksi, $sql_pelanggan);

            while ($row_pelanggan = mysqli_fetch_assoc($result_pelanggan)) {

                $ip_pelanggan = $row_pelanggan['ip_address'];
                $commentToSearch = "Blokir Bulanan " . $ip_pelanggan;
                $API->write('/ip/firewall/address-list/print', false);
                $API->write('?comment=' . $commentToSearch);
                $ips = $API->read();

                if (!empty($ips)) {
                    foreach ($ips as $ip_data) {
                        $API->write('/ip/firewall/address-list/remove', false);
                        $API->write('=.id=' . $ip_data['.id']);
                        $API->read();
                        echo "Berhasil menghapus entri address-list dengan komentar: " . $ip_data['comment'] . "<br>";
                    }
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

                // Menyimpan perubahan status di database
                $koneksi->query("UPDATE tb_tagihan SET blokir_status = NULL WHERE id_tagihan = $id_tagihan");
            }
        }
    }
}

$tgl_jatuh_tempo = $tgl_pemasangan_obj->format('Y-m-d') . ' ' . $jam_sekarang;
$sql2 = $koneksi->query("UPDATE tb_tagihan SET terbayar='$jml_bayar', status_bayar=1, tgl_bayar='$tgl_bayar', blokir_status=NULL, no_invoice='$format', waktu_bayar=NOW(), user_id=$id_user WHERE id_tagihan='$id_tagihan'");
$sql_test = $koneksi->query("UPDATE tb_pelanggan SET jatuh_tempo='$tgl_jatuh_tempo' WHERE id_pelanggan='$id_pelanggan'");

$query3 = $koneksi->query("
    INSERT INTO tb_kas (tgl_kas, keterangan, penerimaan, id_tagihan)
    SELECT '$tgl_bayar', '$ket', '$jml_bayar', '$id_tagihan'
    FROM DUAL
    WHERE NOT EXISTS (
        SELECT 1 FROM tb_kas WHERE id_tagihan = '$id_tagihan'
    )
");

if ($sql2) {
    echo "
            <script>
                setTimeout(function() {
                    swal({
                        title: 'Tagihan',
                        text: 'Berhasil Dibayar!',
                        type: 'success'
                    }, function() {
                        window.location = '?page=transaksi';
                    });
                }, 300);
            </script>
        ";
} else {
    echo "
    <script>
        setTimeout(function() {
            swal({
                title: 'Tagihan',
                text: 'Sudah Terbayar',
                type: 'success'
            }, function() {
                window.location = '?page=transaksi';
            });
        }, 300);
    </script>
";
}
