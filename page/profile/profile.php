<?php if ($_SESSION['admin']) { ?>

  <?php
  $sql = $koneksi->query("select * from tb_profile ");
  $data = $sql->fetch_assoc();

  $sql_wa = $koneksi->query("SELECT * FROM tbl_token");
  $data_wa = $sql_wa->fetch_assoc();

  $sql_mikrotik = $koneksi->query("SELECT * FROM tbl_mikrotik");
  $data_mikrotik = $sql_mikrotik->fetch_assoc();

  $sql_nomorsaya = $koneksi->query("SELECT * FROM tbl_nomorphone");
  $data_nomor = $sql_nomorsaya->fetch_assoc();
  ?>

  <div class="row">
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Setting Profile</h3>
        </div>
        <form method="POST" enctype="multipart/form-data">
          <div class="box-body">
            <div class="form-group">
              <label for="nama_sekolah">Nama Aplikasi</label>
              <input type="text" class="form-control" name="nama_sekolah" value="<?php echo $data['nama_sekolah'] ?>">
            </div>
            <div class="form-group">
              <label for="alamat">Alamat</label>
              <textarea class="form-control" rows="3" name="alamat"><?php echo $data['alamat']; ?></textarea>
            </div>
            <div class="form-group">
              <label for="foto">Ganti Logo (Ganti Nama Gambar Lebih Singkat Contoh : logo.png)</label>
              <input type="file" name="foto">
              <img src="images/<?php echo $data['foto'] ?>" width="100" height="100" alt="">
            </div>
          </div>
          <div class="box-footer">
            <button type="submit" name="simpan_profile" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Pemilik</h3>
        </div>
        <form method="POST" enctype="multipart/form-data">
          <div class="box-body">
            <div class="form-group">
              <label for="nama_saya">Nama Pemilik</label>
              <input type="text" class="form-control" name="nama_saya" value="<?php echo $data_nomor['nama_pemilik'] ?>" placeholder="Nama Pemilik / Admin">
            </div>
            <div class="form-group">
              <label for="nomor_saya">Nomor Saya</label>
              <input type="number" class="form-control" name="nomor_saya" value="<?php echo $data_nomor['my_number'] ?>" placeholder="08961234567">
            </div>

          </div>
          <div class="box-footer">
            <button type="submit" name="simpan_nomor" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class=" box-title">Mikrotik API</h3>
        </div>
        <div class="box-body">
          <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <input type="hidden" value="<?= $data_mikrotik['id_mikrotik'] ?>" name="id_mikrotik">
              <label for="ip_mikrotik">IP Mikrotik</label>
              <input type="text" class="form-control" name="ip_mikrotik" id="ip_mikrotik" placeholder="192.168.88.1 / VPN REMOTE Port 8728" value="<?php echo $data_mikrotik['ip'] ?>">
            </div>
            <div class="form-group">
              <label for="username_mikrotik">Usename</label>
              <input type="text" class="form-control" name="username_mikrotik" id="username_mikrotik" placeholder="Username Mikrotik" value="<?php echo $data_mikrotik['username'] ?>">
            </div>
            <div class="form-group">
              <label for="password_mikrotik">Password</label>
              <input type="text" class="form-control" name="password_mikrotik" id="password_mikrotik" placeholder="Password Mikrotik" value="<?php echo $data_mikrotik['password'] ?>">
            </div>
            <div class="form-group">
              <label for="port_api_mikrotik">Port Api</label>
              <input type="text" class="form-control" name="port_api_mikrotik" id="port_api_mikrotik" placeholder="8728" value="<?php echo $data_mikrotik['port_mikrotik'] ?>">
            </div>
            <div class="box-footer">
              <button type="submit" name="simpan_mikrotik" class="btn btn-primary">Simpan</button>
              <a href="?page=profile&aksi=hapus_mikrotik&id=<?php echo $data_mikrotik['id_mikrotik']; ?>" class="btn btn-danger" title=""> Hapus Mikrotik</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="box box-primary">
        <form method="POST" enctype="multipart/form-data">
          <div class="box-header with-border">
            <h3 class="box-title">Token Whatsapp API</h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <input type="hidden" value="<?= $data_wa['id_token'] ?>" name="id_token">
              <label for="token_wa">Token</label>
              <input type="text" class="form-control" name="token_wa" placeholder="Paste Token Whatsapp Disini" value="<?php echo $data_wa['token']; ?>">
            </div>
          </div>
          <div class="box-footer">
            <button type="submit" name="simpan_token_wa" class="btn btn-primary">Simpan</button>
            <a href="?page=profile&aksi=hapus_waapi&id=<?php echo $data_wa['id_token']; ?>" class="btn btn-danger" title=""> Hapus Token API</a>
          </div>
        </form>
      </div>
    </div>

  </div>

  <?php
  if (isset($_POST['simpan_mikrotik'])) {

    $ip_mikrotik = $_POST['ip_mikrotik'];
    $username = $_POST['username_mikrotik'];
    $password = $_POST['password_mikrotik'];
    $port_api = $_POST['port_api_mikrotik'];

    if (!empty($ip_mikrotik) && !empty($username) && !empty($password) && !empty($port_api)) {
      $cek_mikrotik = $koneksi->query("SELECT * FROM tbl_mikrotik");
      $row = $cek_mikrotik->fetch_assoc();

      if ($row) {
        // Jika data token sudah ada, lakukan UPDATE
        $sql_mikrotik = $koneksi->query("UPDATE tbl_mikrotik SET ip='$ip_mikrotik', username='$username', password='$password', port_mikrotik='$port_api'");
        $message = 'Berhasil Diperbarui!';
      } else {
        // Jika data token belum ada, lakukan INSERT
        $sql_mikrotik = $koneksi->query("INSERT INTO tbl_mikrotik (id_mikrotik, ip, username, password, port_mikrotik) VALUES (1, '$ip_mikrotik', '$username', '$password', '$port_api')");
        $message = 'Berhasil Disimpan!';
      }

      if ($sql_mikrotik) {
  ?>
        <script>
          setTimeout(function() {
            swal({
              title: 'Data Mikrotik',
              text: '<?php echo $message; ?>',
              type: 'success'
            }, function() {
              window.location = '?page=profile';
            });
          }, 300);
        </script>
  <?php
      }
    }
  }
  ?>

  <?php
  if (isset($_POST['simpan_nomor'])) {
    $nomorsaya = $_POST['nomor_saya'];
    $nama_saya = $_POST['nama_saya'];

    if (!empty($nomorsaya . $nama_saya)) {
      $checkNomor = $koneksi->query("SELECT * FROM tbl_nomorphone");
      $row = $checkNomor->fetch_assoc();

      if ($row) {
        // Jika data nomor "nomor_saya" sudah ada, lakukan UPDATE
        $sql_nomorss = $koneksi->query("UPDATE tbl_nomorphone SET my_number='$nomorsaya', nama_pemilik='$nama_saya'");
        $message = 'Berhasil Diperbarui!';
      } else {
        // Jika data nomor "nomor_saya" belum ada, lakukan INSERT
        $sql_nomorss = $koneksi->query("INSERT INTO tbl_nomorphone (my_number, nama_pemilik) VALUES ('$nomorsaya', '$nama_saya')");
        $message = 'Berhasil Disimpan!';
      }

      if ($sql_nomorss) {
  ?>
        <script>
          setTimeout(function() {
            swal({
              title: 'Data Nomor Saya',
              text: '<?php echo $message; ?>',
              type: 'success'
            }, function() {
              window.location = '?page=profile';
            });
          }, 300);
        </script>
  <?php
      }
    }
  }
  ?>

  <?php
  if (isset($_POST['simpan_token_wa'])) {
    $token = $_POST['token_wa'];

    if (!empty($token)) {
      $checkTokenQuery = $koneksi->query("SELECT * FROM tbl_token");
      $row = $checkTokenQuery->fetch_assoc();

      if ($row) {
        // Jika data token sudah ada, lakukan UPDATE
        $sql_wa = $koneksi->query("UPDATE tbl_token SET token='$token'");
        $message = 'Berhasil Diperbarui!';
      } else {
        // Jika data token belum ada, lakukan INSERT
        $sql_wa = $koneksi->query("INSERT INTO tbl_token (id_token, token) VALUES (1, '$token')");
        $message = 'Berhasil Disimpan!';
      }

      if ($sql_wa) {
  ?>
        <script>
          setTimeout(function() {
            swal({
              title: 'Data Token WA',
              text: '<?php echo $message; ?>',
              type: 'success'
            }, function() {
              window.location = '?page=profile';
            });
          }, 300);
        </script>
  <?php
      }
    }
  }
  ?>

  <?php
  if (isset($_POST['simpan_profile'])) {
    $nama_sekolah = htmlspecialchars(strip_tags($_POST['nama_sekolah']));
    $alamat = htmlspecialchars(strip_tags($_POST['alamat']));
    $foto = $_FILES['foto']['name'];
    $lokasi = $_FILES['foto']['tmp_name'];

    if (!empty($lokasi)) {
      move_uploaded_file($lokasi, "images/" . $foto);
      $sql = $koneksi->query("update  tb_profile set nama_sekolah='$nama_sekolah', foto='$foto', alamat='$alamat' ");
      if ($sql) {
  ?>
        <script>
          setTimeout(function() {
            swal({
              title: 'Data Profile',
              text: 'Berhasil Diubah!',
              type: 'success'
            }, function() {
              window.location = '?page=profile';
            });
          }, 300);
        </script>
      <?php
      }
    } else {
      $sql = $koneksi->query("update  tb_profile set nama_sekolah='$nama_sekolah', alamat='$alamat' ");
      if ($sql) {
      ?>
        <script>
          setTimeout(function() {
            swal({
              title: 'Data Profile ',
              text: 'Berhasil Diubah!',
              type: 'success'
            }, function() {
              window.location = '?page=profile';
            });
          }, 300);
        </script>
<?php
      }
    }
  }
} else {
  echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>