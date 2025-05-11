<?php
$id_pgat = $_GET['id'];

// Lakukan sanitasi input jika belum
// Contoh: $id_pgat = filter_var($id_pgat, FILTER_SANITIZE_NUMBER_INT);

$sql = $koneksi->query("DELETE FROM tbl_pgate WHERE id_pgat='$id_pgat'");

if ($sql) {
    echo "
      <script>
          swal({
              title: 'Payment Gateway',
              text: 'Berhasil Dihapus!',
              type: 'success'
          }, function() {
              window.location = '?page=kelola_bank';
          });
      </script>
  ";
}
