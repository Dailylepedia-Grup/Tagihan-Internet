<?php

$id = $_GET['id'];

$sql = $koneksi->query("DELETE FROM tbl_token WHERE id_token='$id'");

if ($sql) {
?>

    <script>
        setTimeout(function() {
            sweetAlert({
                title: 'Berhasil!',
                text: 'Data Token WA API Dihapus',
                type: 'success'
            }, function() {
                window.location = '?page=profile';
            });
        }, 300);
    </script>

<?php
}
