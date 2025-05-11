<div class="row">

    <div class="col-md-6">

        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">Tambah Perangkat Baru</h3>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="box-body">

                    <div class="form-group">
                        <label>Nama Perangkat (Tipe Router/Model)</label>
                        <input type="text" name="nama_perangkat" id="nama_perangkat" class="form-control" required>
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

    $nama_perangkat = htmlspecialchars(strip_tags($_POST['nama_perangkat']));

    $sql3 = $koneksi->query("SELECT * FROM tb_perangkat WHERE nama_perangkat = '$nama_perangkat'");

    $data = $sql3->num_rows;

    if ($data >= 1) {
?>
        <script type="text/javascript">
            alert("Perangkat Ini Sudah Tercatat Di Database silahkan ulangi kembali");
        </script>
<?php
    } else {

        $sql = $koneksi->query("INSERT INTO tb_perangkat (nama_perangkat) VALUES ('$nama_perangkat')");
    }

    if ($sql) {
        echo "

          <script>
              setTimeout(function() {
                  swal({
                      title: 'Data Perangkat',
                      text: 'Berhasil Disimpan!',
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