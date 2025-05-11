<div class="row">
  <!-- left column -->
  <div class="col-md-6">
    <!-- general form elements -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Tambah Data Pengguna</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form method="POST" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Username</label>
            <input type="text" class="form-control" name="username">
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1">Nama</label>
            <input type="text" class="form-control" name="nama">
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" name="password">
          </div>

          <div class="form-group">
            <label>Level</label> <br>
            <select class="form-control" name="level" id="levelSelect" required="">
              <option value="">Pilih Level User</option>
              <option value="administrator">Administrator</option>
              <option value="admin">Admin</option>
              <option value="teknisi">Teknisi</option>
              <option value="kasir">Kasir</option>
            </select>
          </div>

          <div id="additionalInput" style="display: none;">
            <div class="form-group">
              <label for="additionalField">Nomor Telepon (0821)</label>
              <input type="number" class="form-control" name="additionalField" id="additionalField" required>
            </div>
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1">Foto</label>
            <input type="file" name="foto">
          </div>

          <div class="box-footer">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
          </div>
      </form>
    </div>

    <?php

    if (isset($_POST['simpan'])) {
      $username = htmlspecialchars($_POST['username']);
      $nama = htmlspecialchars($_POST['nama']);
      $password = htmlspecialchars($_POST['password']);
      $level = htmlspecialchars($_POST['level']);
      $noHP = htmlspecialchars($_POST['additionalField']);

      if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {
        $allowed_extensions = array('png', 'jpg', 'jpeg');
        $foto = $_FILES['foto']['name'];
        $lokasi = $_FILES['foto']['tmp_name'];
        $ext = pathinfo($foto, PATHINFO_EXTENSION);

        if (!in_array(strtolower($ext), $allowed_extensions)) {
          echo "Hanya file PNG, JPG, atau JPEG yang diizinkan.";
          exit;
        }

        // Pindahkan file ke direktori tujuan
        $upload = move_uploaded_file($lokasi, "images/" . $foto);

        if (!$upload) {
          echo "Error mengunggah file.";
          exit;
        }
      } else {
        $foto = 'default.png';
      }


      $sql = $koneksi->query("INSERT INTO tb_user (username, nama_user, password, level, foto, phone_number) VALUES ('$username', '$nama', '$password', '$level', '$foto', '$noHP') ");

      if ($sql) {

    ?>
        <script>
          setTimeout(function() {
            sweetAlert({
              title: 'OKE!',
              text: 'Data Berhasil Disimpan!',
              type: 'success'
            }, function() {
              window.location = '?page=pengguna';
            });
          }, 300);
        </script>
    <?php
      } else {
        echo "Error memasukkan data: " . $koneksi->error;
      }
    }

    ?>