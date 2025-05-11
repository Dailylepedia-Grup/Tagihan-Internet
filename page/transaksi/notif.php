<?php

include("../../include/koneksi.php");

$id_tagihan = $_GET['id_tagihan'];
$koneksi->query("UPDATE tb_tagihan SET terkirim='terkirim' WHERE id_tagihan=$id_tagihan ");
