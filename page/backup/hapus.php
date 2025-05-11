<?php
$id = $_GET['id'];

$sql = $koneksi->query("SELECT * FROM riwayat_backupdb WHERE id_backup = '$id'");
$data = $sql->fetch_assoc();

$nama = $data['nama_db'];
$tanggal = $data['tanggal'];

// Form the file path based on the retrieved data
$filePath = "backup_db/" . $nama;

// Check if the file exists
if (file_exists($filePath)) {
    // Attempt to delete the file
    if (unlink($filePath)) {

        $berhasil = $koneksi->query("DELETE FROM riwayat_backupdb WHERE id_backup = '$id' ");
        echo "<script>
        setTimeout(function() {
            swal({
                title: 'Data Billing',
                text: 'Berhasil Dihapus',
                type: 'success'
            }, function() {
                window.location = '?page=backup';
            });
        }, 300);
        </script>";
    } else {
        echo "<script>
        setTimeout(function() {
            swal({
                title: 'Data Billing',
                text: 'Gagal Dihapus',
                type: 'error'
            }, function() {
                window.location = '?page=backup';
            });
        }, 300);
        </script>";
    }
} else {
    echo "File does not exist.";
}
