<?php
$id_perangkat = $_GET['id'];
$q = $koneksi->query("SELECT * FROM tb_perangkat WHERE id_perangkat = '$id_perangkat' ");
$data = $q->fetch_assoc();

?>

<div class="row">

    <div class="col-md-6">

        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">Ubah Perangkat</h3>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="box-body">

                    <input type="hidden" name="id_perangkat" value="<?= $data['id_perangkat'] ?>">

                    <div class="form-group">
                        <label>Nama Perangkat (Tipe Router/Model)</label>
                        <input type="text" name="nama_perangkat" id="nama_perangkat" value="<?= $data['nama_perangkat'] ?>" class="form-control" required>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="submit" name="tambah" class="btn btn-block btn-primary btn-lg">Tambah</button>

                </div>
            </form>

        </div>

    </div>

</div>

<?php
if (isset($_POST['tambah'])) {

    $id_perangkat = $_POST['id_perangkat'];
    $nama_perangkat = htmlspecialchars(strip_tags($_POST['nama_perangkat']));

    $sql = $koneksi->query("UPDATE tb_perangkat SET nama_perangkat='$nama_perangkat' WHERE id_perangkat='$id_perangkat'");

    if ($sql) {
        echo "

          <script>
              setTimeout(function() {
                  swal({
                      title: 'Data Perangkat',
                      text: 'Berhasil Di Ubah!',
                      type: 'success'
                  }, function() {
                      window.location = '?page=perangkat';
                  });
              }, 300);
          </script>

      ";
    }
}

?>