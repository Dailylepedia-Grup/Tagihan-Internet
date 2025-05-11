<?php
$sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
$result = mysqli_query($koneksi, $sql_mikrotik);
$row = mysqli_fetch_assoc($result);

$API = new RouterosAPI();

if ($API->connect($row['ip'], $row['username'], $row['password'])) {
    // ID Simple Queue yang akan dihapus
    $usersProfile = $_GET['id']; // Gantilah dengan ID yang sesuai

    // Mengirim perintah penghapusan ke MikroTik
    $API->write('/ip/hotspot/user/profile/remove', false);
    $API->write("=.id=" . $usersProfile, true);

    $result = $API->read();

    // Periksa jika operasi penghapusan berhasil
    if (count($result) == 0) {
?>

        <script>
            setTimeout(function() {
                swal({
                    title: 'Hotspot Profile',
                    text: 'Data Berhasil Dihapus',
                    type: 'success'
                }, function() {
                    window.location = '?page=users-profile';
                });
            }, 300);
        </script>

<?php
    } else {
        echo "Gagal menghapus PPPOE. Pesan kesalahan: " . $API->error;
    }

    $API->disconnect();
} else {
    echo 'Tidak dapat terhubung ke MikroTik.';
}
?>