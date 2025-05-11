<?php

$id_pelanggan = $_GET['id'];

$sql = $koneksi->query("delete from tb_pelanggan where id_pelanggan='$id_pelanggan'");
$sql = $koneksi->query("delete from tb_user where id_pelanggan='$id_pelanggan'");

$conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
$checkUser = $conPelanggan->fetch_assoc();

if ($checkUser['status'] == 'ya') {

	$nama_pelanggan = $_GET['nama'];

	$sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
	$result = mysqli_query($koneksi, $sql_mikrotik);
	$row = mysqli_fetch_assoc($result);

	$API = new RouterosAPI();
	if ($API->connect($row['ip'], $row['username'], $row['password'])) {

		$API->comm("/ppp/secret/remove", array(
			"numbers" => $nama_pelanggan,
		));
	}
}

if ($sql) {
?>

	<script>
		setTimeout(function() {
			sweetAlert({
				title: 'Berhasil!',
				text: 'Data Berhasil Dihapus!',
				type: 'success'
			}, function() {
				window.location = '?page=pelanggan';
			});
		}, 300);
	</script>

<?php
}

?>