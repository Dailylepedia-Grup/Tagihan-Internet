<?php
$id_perangkat = $_GET['id'];

$sql = $koneksi->query("DELETE FROM tb_perangkat WHERE id_perangkat='$id_perangkat'");

if ($sql) {
?>
    <script>
        setTimeout(function() {
            sweetAlert({
                title: 'Berhasil!',
                text: 'Data Berhasil Dihapus!',
                type: 'success'
            }, function() {
                window.location = '?page=perangkat';
            });
        }, 300);
    </script>
<?php
}
