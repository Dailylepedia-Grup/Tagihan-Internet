<?php

$id = $_GET['id'];

$id = mysqli_real_escape_string($koneksi, $id);

$adminCount = $koneksi->query("SELECT COUNT(*) as count FROM tb_user WHERE level='admin'")->fetch_assoc()['count'];

$userLevel = $koneksi->query("SELECT level FROM tb_user WHERE id='$id'")->fetch_assoc()['level'];

if ($adminCount > 1 || $userLevel !== 'admin') {
	$sql = $koneksi->query("DELETE FROM tb_user WHERE id='$id'");

	if ($sql) {
?>
		<script>
			setTimeout(function() {
				sweetAlert({
					title: 'Berhasil',
					text: 'Data Berhasil Dihapus!',
					type: 'success'
				}, function() {
					window.location = '?page=pengguna';
				});
			}, 300);
		</script>
	<?php
	} else {
		echo "Error deleting user: " . $koneksi->error;
	}
} else {
	?>
	<script>
		sweetAlert({
			title: 'Gagal',
			text: 'Tidak Dapat Menghapus Satu-satunya Admin',
			type: 'error'
		}, function() {
			window.location = '?page=pengguna';
		});
	</script>
<?php
}
?>