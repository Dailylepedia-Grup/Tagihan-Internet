<?php
$id_tagihan = $_GET['id'];
$tgl_bayar = date('Y-m-d');
$sql = $koneksi->query("SELECT * FROM tb_tagihan, tb_pelanggan, tb_paket WHERE tb_pelanggan.id_pelanggan=tb_tagihan.id_pelanggan AND tb_paket.id_paket=tb_pelanggan.paket AND tb_tagihan.id_tagihan='$id_tagihan'");
$data = $sql->fetch_assoc();
$jml_bayar = $data['jml_bayar'];
$pelanggan = $data['nama_pelanggan'];
$paket = $data['nama_paket'];
$ket = "Pembayaran Internet AN." . "&nbsp" . $pelanggan . "," . "&nbsp" . "Paket" . "&nbsp" . $paket;
$sql2 = $koneksi->query("UPDATE tb_tagihan SET terbayar=NULL, status_bayar=NULL, tgl_bayar=NULL, waktu_bayar=NULL, user_id=NULL WHERE id_tagihan='$id_tagihan'");
$query = $koneksi->query("DELETE FROM tb_kas WHERE id_tagihan='$id_tagihan' ");
if ($sql2) {
    echo "
                  <script>
                      setTimeout(function() {
                          swal({
                              title: 'Tagihan',
                              text: 'Berhasil Dibatalkan!',
                              type: 'success'
                          }, function() {
                              window.location = '?page=transaksi';
                          });
                      }, 300);
                  </script>
              ";
}
