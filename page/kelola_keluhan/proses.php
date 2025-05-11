<?php

$id = $_GET['id_pelanggan'];
$id_keluhan = $_GET['id_keluhan'];
$telp = $_GET['no_telp'];
$nama_user = $_GET['nama_user'];
$nomor_tiket = $_GET['nomor_tiket'];

$sql_token = "SELECT * FROM tbl_token WHERE id_token = 1"; // Sesuaikan dengan query yang sesuai
$result = mysqli_query($koneksi, $sql_token);
$row = mysqli_fetch_assoc($result);
$authorizationToken = $row['token'];

// batas
$sql = $koneksi->query("SELECT status_keluhan FROM tbl_keluhan WHERE id_pelanggan='$id'");
$data = $sql->fetch_assoc();

$sql2 = $koneksi->query("UPDATE tbl_keluhan SET status_keluhan='proses' WHERE id_keluhan='$id_keluhan'");

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
        'target' => $telp,
        'message' => 'Halo ' . $nama_user . ' Saat ini Keluhan dengan nomor tiket ' . $nomor_tiket . ' kamu sedang kami proses mohon untuk tunggu informasi lebih lanjut, Terima Kasih.' . "\n\n" . '_Jangan Balas Pesan Ini Pesan Otomatis_',
        'countryCode' => '62', //optional
    ),
    CURLOPT_HTTPHEADER => array(
        'Authorization: ' . $authorizationToken
    ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;

if ($sql) {
?>

    <script>
        setTimeout(function() {
            sweetAlert({
                title: 'Berhasil!',
                text: 'Keluhan Di Proses',
                type: 'success'
            }, function() {
                window.location = '?page=kelola_keluhan';
            });
        }, 300);
    </script>

<?php
}

?>