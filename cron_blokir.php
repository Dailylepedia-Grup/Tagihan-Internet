<?php
require('include/routeros_api.php');
include('include/koneksi.php');

$get_blokir = $koneksi->query("SELECT * FROM tbl_blokir");
$status_blokir = $get_blokir->fetch_assoc();

$berapa = $status_blokir['set_waktu'];
$kapan = $status_blokir['set_waktu2'];

if ($status_blokir['status_blokir'] == 'aktif') {

    $sql_token = "SELECT * FROM tbl_token WHERE id_token = 1"; // Sesuaikan dengan query yang sesuai
    $result = mysqli_query($koneksi, $sql_token);
    $row = mysqli_fetch_assoc($result);
    $authorizationToken = $row['token'];

    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $sql = $koneksi->query("SELECT 
                tb_pelanggan.*, 
                tb_tagihan.*,
                tb_user.*
            FROM tb_pelanggan
            INNER JOIN tb_tagihan ON tb_pelanggan.id_pelanggan = tb_tagihan.id_pelanggan
            LEFT JOIN tb_user ON tb_pelanggan.id_pelanggan = tb_user.id_pelanggan");

    // $sql = $koneksi->query("SELECT * FROM tb_pelanggan INNER JOIN tb_tagihan ON tb_pelanggan.id_pelanggan=tb_tagihan.id_pelanggan");

    $conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
    $checkUser = $conPelanggan->fetch_assoc();

    while ($data = $sql->fetch_assoc()) {

        $ip_address = $data['ip_address'];
        $id_tagihan = $data['id_tagihan'];
        $no_telp = $data['no_telp'];
        $nama = $data['nama_pelanggan'];
        $jatuh_tempo = $data['jatuh_tempo'];
        $username = $data['username'];
        $tagihan = number_format($data['jml_bayar'], 0, ",", ".");

        $pesan = $status_blokir['pesan_blokir'];

        $waktu_unix = strtotime($data['jatuh_tempo']);
        $waktu_unix = strtotime("+" . $berapa . $kapan, $waktu_unix);
        $waktu_format = date("Y-m-d H:i", $waktu_unix);

        $sekarang = time();
        $sekarang_format = date("Y-m-d H:i", $sekarang);
        $sekarang_unix = strtotime($sekarang_format);

        $pesan = str_replace('$nama', $nama, $pesan); // Ganti '$nama' dengan nilai $nama
        $pesan = str_replace('$jatuh_tempo', $jatuh_tempo, $pesan); // Ganti '$jatuh_tempo' dengan nilai $jatuh_tempo
        $pesan = str_replace('$tagihan', $tagihan, $pesan); // Ganti '$jatuh_tempo' dengan nilai $jatuh_tempo
        $pesan = str_replace('$no_telp', $no_telp, $pesan); // Ganti '$jatuh_tempo' dengan nilai $jatuh_tempo
        $pesan = str_replace('$sekarang_format', $sekarang_format, $pesan); // Ganti '$jatuh_tempo' dengan nilai $jatuh_tempo   


        if ($waktu_unix == $sekarang_unix) {

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

                    $sql_pelanggan = "SELECT * FROM tb_user WHERE username = '$username'";
                    $result_pelanggan = mysqli_query($koneksi, $sql_pelanggan);

                    while ($row_pelanggan = mysqli_fetch_assoc($result_pelanggan)) {
                        $nama_pelanggan = $row_pelanggan['username']; // Ambil nama pelanggan

                        // Menonaktifkan PPP Secret
                        $API->comm("/ppp/secret/disable", array(
                            "numbers" => $nama_pelanggan,
                        ));

                        // Mendapatkan ID koneksi PPP aktif
                        $activeConnections = $API->comm("/ppp/active/print", array(
                            "?name" => $nama_pelanggan,
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
                    'message' => $pesan,
                    'countryCode' => '62', //optional
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization:' . $authorizationToken
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;
        }
    }

    $koneksi->close();
}
