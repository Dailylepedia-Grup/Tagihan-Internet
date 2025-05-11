<?php

include("../include/routeros_api.php");
include("../include/koneksi.php");

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

$id_tagihan = substr($order_id, 0, 3);
$tgl_bayar = date('Y-m-d');

$sql = $koneksi->query("SELECT * FROM tb_tagihan, tb_pelanggan, tb_paket WHERE tb_pelanggan.id_pelanggan=tb_tagihan.id_pelanggan AND tb_paket.id_paket=tb_pelanggan.paket and tb_tagihan.id_tagihan='$id_tagihan'");

$sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
$result = mysqli_query($koneksi, $sql_mikrotik);
$row = mysqli_fetch_assoc($result);

$data = $sql->fetch_assoc();

$id_pelanggan = $data['id_pelanggan'];
$jatuh_tempo = $data['jatuh_tempo'];
$jml_bayar = $data['jml_bayar'];
$pelanggan = $data['nama_pelanggan'];
$paket = $data['nama_paket'];
$ip_address = $data['ip_address'];
$ket = "Pembayaran Internet AN." . "&nbsp" . $pelanggan . "," . "&nbsp" . "Paket" . "&nbsp" . $paket;

$tgl_pemasangan_obj = new DateTime($jatuh_tempo);
$jam_sekarang = date('H:i:s'); // Mendapatkan jam saat ini
$tanggal_sekarang = date('Y-m-d'); // Mendapatkan tanggal saat ini

// Jika tanggal pembayaran lebih kecil dari tanggal jatuh tempo, tambahkan 1 bulan ke tanggal jatuh tempo
if ($tanggal_sekarang < $jatuh_tempo) {
    $tgl_pemasangan_obj = new DateTime($jatuh_tempo);
    $tgl_pemasangan_obj->modify('+1 Month');
} else {
    // Jika tidak, tambahkan 1 bulan ke tanggal pembayaran
    $tgl_pemasangan_obj = new DateTime($tanggal_sekarang);
    $tgl_pemasangan_obj->modify('+1 Month');
}

if (!empty($row)) {
    $API = new RouterosAPI();
    if ($API->connect($row['ip'], $row['username'], $row['password'])) {
        $sql_pelanggan = "SELECT * FROM tb_pelanggan WHERE ip_address = '$ip_address'";
        $result_pelanggan = mysqli_query($koneksi, $sql_pelanggan);

        while ($row_pelanggan = mysqli_fetch_assoc($result_pelanggan)) {
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
        $API->disconnect();
    }
}

$tgl_jatuh_tempo = $tgl_pemasangan_obj->format('Y-m-d') . ' ' . $jam_sekarang;

$sql2 = $koneksi->query("UPDATE tb_tagihan SET terbayar='$jml_bayar', status_bayar=1, tgl_bayar='$tgl_bayar', no_invoice='$format' WHERE id_tagihan='$id_tagihan'");

$query = $koneksi->query("INSERT INTO tb_kas2 (tgl_kas, keterangan, pengeluaran, id_tagihan, id_pelanggan)VALUES('$tgl_bayar', '$ket', '$jml_bayar', '$id_tagihan', '$id_pelanggan')");

$query3 = $koneksi->query("INSERT INTO tb_kas (tgl_kas, keterangan, penerimaan, id_tagihan)VALUES('$tgl_bayar', '$ket', '$jml_bayar', '$id_tagihan') ");

$sql_test = $koneksi->query("UPDATE tb_pelanggan SET jatuh_tempo='$tgl_jatuh_tempo' WHERE id_pelanggan='$id_pelanggan'");
