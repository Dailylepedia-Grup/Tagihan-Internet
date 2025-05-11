<?php


$id = $_GET['id'];

$sql = $koneksi->query("DELETE FROM tbl_odc WHERE id_odc='$id'");

if ($sql) {
?>

    <script>
        setTimeout(function() {
            sweetAlert({
                title: 'OKE!',
                text: 'Data Berhasil Dihapus!',
                type: 'error'
            }, function() {
                window.location = '?page=odc&aksi=listodc';
            });
        }, 300);
    </script>

<?php
}

?>