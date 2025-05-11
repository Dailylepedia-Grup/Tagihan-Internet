<?php

$id = $_GET['id'];

$sql = $koneksi->query("DELETE FROM tbl_mikrotik WHERE id_mikrotik='$id'");

if ($sql) {
?>

    <script>
        setTimeout(function() {
            sweetAlert({
                title: 'Berhasil!',
                text: 'Data Mikrotik Dihapus',
                type: 'success'
            }, function() {
                window.location = '?page=profile';
            });
        }, 300);
    </script>

<?php
}
