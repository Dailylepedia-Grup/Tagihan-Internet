<?php

$id_tagihan = $_GET['id'];

$sql = $koneksi->query("SELECT * from tb_tagihan, tb_pelanggan, tb_paket where tb_pelanggan.id_pelanggan=tb_tagihan.id_pelanggan and tb_paket.id_paket=tb_pelanggan.paket and tb_tagihan.id_tagihan='$id_tagihan'");

$data = $sql->fetch_assoc();

$jml_bayar = $data['jml_bayar'];
$pelanggan = $data['nama_pelanggan'];
$paket = $data['nama_paket'];

$sql2 = $koneksi->query("UPDATE tb_tagihan SET terbayar='$jml_bayar', status_bayar=1, tgl_bayar='$tgl_bayar', no_invoice='$format', pesan='Sudah Dikirim' WHERE id_tagihan='$id_tagihan'");
