<?php 


		$id_paket = $_GET['id'];

		$sql=$koneksi->query("delete from tb_paket where id_paket='$id_paket'");

		if ($sql) {
			?>

				<script>
				    setTimeout(function() {
				        sweetAlert({
				            title: 'OKE!',
				            text: 'Data Berhasil Dihapus!',
				            type: 'error'
				        }, function() {
				            window.location = '?page=paket';
				        });
				    }, 300);
				</script>

			<?php
		}

 ?>


