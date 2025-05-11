<?php


$id = $_GET['id'];

$sql = $koneksi->query("DELETE FROM tbl_odp WHERE id_odp='$id'");

if ($sql) {
?>

    <script>
        setTimeout(function() {
            sweetAlert({
                title: 'OKE!',
                text: 'Data Berhasil Dihapus!',
                type: 'error'
            }, function() {
                window.location = '?page=odp&aksi=listodp';
            });
        }, 300);
    </script>

<?php
}

?>