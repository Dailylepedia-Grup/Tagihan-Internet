<?php
$id = $_GET['id'];
$get = $koneksi->query("SELECT * FROM tbl_odc WHERE id_odc = $id");
$ambil = $get->fetch_assoc();
?>
<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">Ubah Data ODC</h3>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="box-body">

                    <div class="form-group">
                        <input type="hidden" name="id_odc" class="form-control" value="<?= $ambil['id_odc'] ?>" required>
                        <label>Nama ODC</label>
                        <input type="text" name="nama_odc" value="<?= $ambil['nama_odc'] ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Port ODC</label>
                        <input type="text" name="jumlah_port" value="<?= $ambil['port_odc'] ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Perangkat</label>
                        <input type="text" name="nama_perangkat" value="<?= $ambil['perangkat_odc'] ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="mappingg" value="<?= $ambil['location'] ?>" class="form-control" id="odc">
                    </div>

                    <div class="form-group">
                        <label>Lokasi ODC</label>
                        <div class="box-body">
                            <div id="titikodc" style="height: 600px;"></div>
                            <p id="odc"></p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="submit" name="ubah" class="btn btn-block btn-primary btn-lg">Simpan</button>

                </div>
            </form>

        </div>

    </div>

</div>

<?php

if (isset($_POST['ubah'])) {

    $id_odc = $_POST['id_odc'];
    $nama_odc = $_POST['nama_odc'];
    $jumlah_port = $_POST['jumlah_port'];
    $nama_perangkat = $_POST['nama_perangkat'];
    $mapping = $_POST['mappingg'];

    $sql = $koneksi->query("UPDATE tbl_odc SET nama_odc = '$nama_odc', port_odc = '$jumlah_port', perangkat_odc = '$nama_perangkat', location = '$mapping' WHERE id_odc = '$id_odc'");

    if ($sql) {
        echo "

      <script>
          setTimeout(function() {
              swal({
                  title: 'Data ODC',
                  text: 'Berhasil Disimpan!',
                  type: 'success'
              }, function() {
                  window.location = '?page=odc';
              });
          }, 300);
      </script>

  ";
    }
}

?>