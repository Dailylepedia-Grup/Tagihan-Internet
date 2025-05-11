<?php

$id_tagihan = $_GET['id'];

$sql = $koneksi->query("DELETE FROM tb_tagihan WHERE id_tagihan = '$id_tagihan'");

if ($sql) {
    echo "
                  <script>
                      setTimeout(function() {
                          swal({
                              title: 'Tagihan',
                              text: 'Berhasil Dihapus',
                              type: 'success'
                          }, function() {
                              window.location = '?page=transaksi';
                          });
                      }, 300);
                  </script>
              ";
}
