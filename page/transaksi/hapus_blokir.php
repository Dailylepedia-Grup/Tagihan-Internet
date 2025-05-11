<?php
require('../../include/routeros_api.php');
include "../../include/koneksi.php";

$ip_address = $_GET['ip_pelanggan'];
$id_tagihan = $_GET['id_tagihan'];

$conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
$checkUser = $conPelanggan->fetch_assoc();

$sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
$result = mysqli_query($koneksi, $sql_mikrotik);
$row = mysqli_fetch_assoc($result);

$API = new RouterosAPI();
if ($API->connect($row['ip'], $row['username'], $row['password'])) {

    if ($checkUser['ippelanggan'] == 'statik') {
        // Langkah 2: Ambil data pelanggan yang belum membayar dari database
        $sql_pelanggan = "SELECT * FROM tb_pelanggan WHERE ip_address = '$ip_address'";
        $result_pelanggan = mysqli_query($koneksi, $sql_pelanggan);

        while ($row_pelanggan = mysqli_fetch_assoc($result_pelanggan)) {
            $ip_pelanggan = $row_pelanggan['ip_address']; // Ambil alamat IP pelanggan

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
                // Tidak ada entri address-list yang ditemukan
                echo "Tidak ada entri address-list yang ditemukan.";
            }

            // $pelanggan_id = $row_pelanggan['id_pelanggan'];
            $koneksi->query("UPDATE tb_tagihan SET blokir_status = NULL WHERE id_tagihan = $id_tagihan");
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

    $API->disconnect();
}
