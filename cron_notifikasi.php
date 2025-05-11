<?php
include('include/koneksi.php');

$get_status = $koneksi->query("SELECT * FROM tbl_notif INNER JOIN tb_pelanggan");
$ambil_status = $get_status->fetch_assoc();

if ($ambil_status['status_notifikasi'] === 'aktif') {

    $sql_token = "SELECT * FROM tbl_token WHERE id_token = 1"; // Sesuaikan dengan query yang sesuai
    $result = mysqli_query($koneksi, $sql_token);
    $row = mysqli_fetch_assoc($result);
    $authorizationToken = $row['token'];


    $sql = $koneksi->query("SELECT * FROM tb_pelanggan INNER JOIN tb_tagihan ON tb_pelanggan.id_pelanggan=tb_tagihan.id_pelanggan");

    while ($data = $sql->fetch_assoc()) {

        $id_tagihan = $data['id_tagihan'];
        $no_telp = $data['no_telp'];
        $nama = $data['nama_pelanggan'];
        $jatuh_tempo = $data['jatuh_tempo'];
        $tagihan = number_format($data['jml_bayar'], 0, ",", ".");
        $pesan = $ambil_status['pesan_notifikasi'];

        $pesan = str_replace('$nama', $nama, $pesan); // Ganti '$nama' dengan nilai $nama
        $pesan = str_replace('$jatuh_tempo', $jatuh_tempo, $pesan); // Ganti '$jatuh_tempo' dengan nilai $jatuh_tempo
        $pesan = str_replace('$tagihan', $tagihan, $pesan); // Ganti '$jatuh_tempo' dengan nilai $jatuh_tempo
        $pesan = str_replace('$no_telp', $no_telp, $pesan); // Ganti '$jatuh_tempo' dengan nilai $jatuh_tempo

        echo $pesan; // Tampilkan pesan yang telah diganti

        $waktu = strtotime($data['jatuh_tempo']);
        $waktu_format = date("Y-m-d H:i", $waktu);
        $waktu_unix = strtotime($waktu_format);

        $sekarang = time();
        $sekarang_format = date("Y-m-d H:i", $sekarang);
        $sekarang_unix = strtotime($sekarang_format);

        if ($waktu_unix == $sekarang_unix) {

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
                    // 'message' => 'Notifikasi Wifi, Mohon Maaf Kepada ' . $nama . ' Sudah Jatuh Tempo Pada Tanggal ' . $jatuh_tempo . ' Akan Segera Terblokir dalam 1x24 Jam jika belum Melakukan Pembayaran WIFI Sebesar Rp ' . $tagihan . "\n\n" . 'Untuk Pembayaran Dapat Kerumah atau Transfer Melalui M-banking Nomor Rekening ada Di Link Berikut' . "\n" . 'https://gayuhinternet.paynow.biz.id' . "\n\n" . 'Username ' . $no_telp . "\n" . 'Password ' . $no_telp . "\n\n" . '_Jangan Balas Pesan Ini Pesan Otomatis_',
                    'message' => $pesan,
                    'countryCode' => '62', //optional
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization:' . $authorizationToken
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response;

            $koneksi->query("UPDATE tb_tagihan SET terkirim = 'terkirim' WHERE id_tagihan = $id_tagihan");
        }
    }

    $koneksi->close();
}
