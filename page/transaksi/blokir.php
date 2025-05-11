<?php
require("../../include/routeros_api.php");
include("../../include/koneksi.php");

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

        $sql_pelanggan = "SELECT * FROM tb_pelanggan WHERE ip_address = '$ip_address'";
        $result_pelanggan = mysqli_query($koneksi, $sql_pelanggan);
        while ($row_pelanggan = mysqli_fetch_assoc($result_pelanggan)) {
            $ip_pelanggan = $row_pelanggan['ip_address']; // Ambil alamat IP pelanggan
            $API->comm("/ip/firewall/address-list/add", array(
                "list"     => "blocked_clients",
                "address"  => $ip_pelanggan,
                "comment"  => "Blokir Bulanan " . $ip_pelanggan
            ));
            $koneksi->query("UPDATE tb_tagihan SET blokir_status = 1 WHERE id_tagihan = $id_tagihan");
        }
    } else {

        $sql_pelanggan = "SELECT * FROM tb_user WHERE username = '$ip_address'";
        $result_pelanggan = mysqli_query($koneksi, $sql_pelanggan);

        while ($row_pelanggan = mysqli_fetch_assoc($result_pelanggan)) {
            $username = $row_pelanggan['username']; // Ambil nama pelanggan

            // Menonaktifkan PPP Secret
            $API->comm("/ppp/secret/disable", array(
                "numbers" => $username,
            ));

            // Mendapatkan ID koneksi PPP aktif
            $activeConnections = $API->comm("/ppp/active/print", array(
                "?name" => $username,
            ));

            // Menonaktifkan dan menghapus koneksi PPP aktif
            foreach ($activeConnections as $connection) {
                $connectionId = $connection['.id'];

                $API->comm("/ppp/active/set", array(
                    ".id" => $connectionId,
                    "disabled" => "yes",
                ));

                $API->comm("/ppp/active/remove", array(
                    ".id" => $connectionId,
                ));
            }

            // Menyimpan perubahan status di database
            $koneksi->query("UPDATE tb_tagihan SET blokir_status = 1 WHERE id_tagihan = $id_tagihan");
        }
    }

    $API->disconnect();
}
