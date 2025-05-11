<?php

require('../../include/routeros_api.php');
include '../../include/koneksi.php';

$sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
$result = mysqli_query($koneksi, $sql_mikrotik);
$row = mysqli_fetch_assoc($result);

$API = new RouterosAPI();
if ($API->connect($row['ip'], $row['username'], $row['password'])) {
    // Ambil data pelanggan yang belum membayar dari database dengan jatuh tempo lebih awal dari waktu saat ini

    $sql = $koneksi->query("SELECT * FROM tb_pelanggan INNER JOIN tb_tagihan ON tb_pelanggan.id_pelanggan = tb_tagihan.id_pelanggan");

    // Periksa apakah ada hasil yang ditemukan
    while ($data = $sql->fetch_assoc()) {
        $id = $data['id_tagihan'];
        $no_hp = $data['no_telp'];
        $status = $data['status_bayar'];
        $nama = $data['nama_pelanggan'];
        $ip = $data['ip_address'];
        // $paket = $data['nama_paket'];
        $tagihan = number_format($data['jml_bayar'], 0, ",", ".");
        $waktu_indonesia = date('d F Y H:i:s');
        $jatuh_tempo = $data['jatuh_tempo'];
        $blokir = $data['blokir_status'];

        $sekarang = time();

        $jatuh = strtotime('+3 hours', strtotime($jatuh_tempo));

        // $jatuh = strtotime($jatuh_tempo);

        if ($sekarang >= $jatuh && $blokir != 1) {

            $API->comm("/ip/firewall/address-list/add", array(
                "list"     => "blocked_clients",
                "address"  => $ip,
                "comment"  => "Blokir Bulanan " . $ip
            ));

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
                    'target' => $no_hp,
                    'message' => 'Mohon Maaf Kepada ' . $nama . ' Saat Ini WIFI sedang Di Nonaktifkan, Dikarenakan Belum Membayar Bulanan Sebesar Rp.' . $tagihan . ' Pemblokiran Dilakukan Pada Tanggal ' . $waktu_indonesia . ' Secara Otomatis Oleh Komputer Terima Kasih',
                    'countryCode' => '62', //optional
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: 6zLqVDjBic7AQJD4-Jva' //ganti TOKEN dengan token yang sesuai
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;

            // $pelanggan_id = $row_pelanggan['id_pelanggan'];
            $koneksi->query("UPDATE tb_tagihan SET blokir_status = 1 WHERE id_tagihan = $id");
        }
    }

    $API->disconnect();
}
