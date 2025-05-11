<?php if ($_SESSION['admin'] || $_SESSION['teknisi']) { ?>

  <?php if ($_SESSION['admin']) { ?>
    <?php

    $cekMikrotik = $koneksi->query("SELECT * FROM tbl_mikrotik");

    $getData = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
    $data = $getData->fetch_assoc();

    $getData = $koneksi->query("SELECT * FROM tbl_paketmikrotik");
    $data3 = $getData->fetch_assoc();

    if ($cekMikrotik->num_rows > 0) { ?>

      <div class="row">

        <div class="col-md-12">

          <div class="box box-primary">

            <div class="box-body">
              <div class="box-header with-border">
                <h4 class="box-title box-primary">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    + Buka Konfigurasi Mikrotik Pelanggan
                  </a>
                </h4>
              </div>
              <div id="collapseTwo" class="panel-collapse collapse">
                <form action="" method="post">
                  <div class="row">

                    <div class="col-md-2">
                      <div class="panel-body">
                        <div class="form-group">
                          <label for="exampleFormControlSelect1">Sinkron Dengan PPPOE Secrect</label>
                          <select class="form-control" name="statusSecrect" id="exampleFormControlSelect1">
                            <option value="tidak" <?php echo ($data['status'] == 'tidak') ? 'selected' : ''; ?>>Tidak</option>
                            <option value="ya" <?php echo ($data['status'] == 'ya') ? 'selected' : ''; ?>>Ya</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-2">
                      <div class="panel-body">
                        <div class="form-group">
                          <label for="exampleFormControlSelect1">Otomatis Tambah PPPOE Secrect</label>
                          <select class="form-control" name="addSecret" id="exampleFormControlSelect1">
                            <option value="tidak" <?php echo ($data['addppsecret'] == 'tidak') ? 'selected' : ''; ?>>Tidak</option>
                            <option value="ya" <?php echo ($data['addppsecret'] == 'ya') ? 'selected' : ''; ?>>Ya</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-2">
                      <div class="panel-body">
                        <div class="form-group">
                          <label for="exampleFormControlSelect1">Konfigurasi IP Pelanggan</label>
                          <select class="form-control" name="ipUser" id="exampleFormControlSelect1">
                            <option value="statik" <?php echo ($data['ippelanggan'] == 'statik') ? 'selected' : ''; ?>>Statik</option>
                            <option value="dynamic" <?php echo ($data['ippelanggan'] == 'dynamic') ? 'selected' : ''; ?>>Dynamic</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-2">
                      <div class="panel-body">
                        <div class="form-group">
                          <label for="exampleFormControlSelect1">Fitur Lokasi Pelanggan</label>
                          <select class="form-control" name="mapping" id="exampleFormControlSelect1">
                            <option value="aktif" <?php echo ($data['mapping'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="tidak" <?php echo ($data['mapping'] == 'tidak') ? 'selected' : ''; ?>>Tidak</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-2">
                      <div class="panel-body">
                        <div class="form-group">
                          <label for="exampleFormControlSelect1">Menggunakan IP Pool</label>
                          <select class="form-control" name="ip_pool" id="exampleFormControlSelect1">
                            <option value="ya" <?php echo ($data['ip_pool'] == 'ya') ? 'selected' : ''; ?>>Ya</option>
                            <option value="tidak" <?php echo ($data['ip_pool'] == 'tidak') ? 'selected' : ''; ?>>Tidak</option>
                          </select>
                        </div>
                      </div>
                    </div>

                  </div>
                  <button type="submit" name="conPelanggan" class="btn btn-success" style="margin-bottom: 15px; margin-left: 15px; ">
                    Simpan
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php
      if (isset($_POST['conPelanggan'])) {
        $statusSecret = $_POST['statusSecrect'];
        $addSecret = $_POST['addSecret'];
        $ipUser = $_POST['ipUser'];
        $mapping = $_POST['mapping'];
        $ip_pool = $_POST['ip_pool'];

        $cekPelangganMikrotik = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");

        if ($cekPelangganMikrotik->num_rows > 0) {
          $pakQuery = $koneksi->query("UPDATE tbl_penggunamikrotik SET status='$statusSecret', addppsecret='$addSecret', ippelanggan='$ipUser', mapping='$mapping', ip_pool='$ip_pool' WHERE id_penggunamikrotik = 1");
        } else {
          $pakQuery = $koneksi->query("INSERT INTO tbl_penggunamikrotik (id_penggunamikrotik , status, addppsecret, ippelanggan, mapping, ip_pool) VALUES (1, '$statusSecret', '$addSecret', '$ipUser', '$mapping', '$ip_pool')");
        }

        if ($pakQuery) {
          echo "
            <script>
                setTimeout(function() {
                    swal({
                        title: 'Data Pelanggan',
                        text: 'Berhasil Dikaitkan Dengan Mikrotik!',
                        type: 'success'
                    }, function() {
                        window.location = '?page=pelanggan';
                    });
                }, 300);
            </script>
        ";
        } else {
          echo "
            <script>
                swal('Error', 'Terjadi kesalahan saat mengupdate/insert data.', 'error');
            </script>
        ";
        }
      }
      ?>


    <?php
    }
    ?>
  <?php
  }
  ?>


  <div class="row">
    <div class="col-md-12">
      <!-- Advanced Tables -->
      <div class="box box-primary box-solid">
        <div class="box-header with-border">
          Data Pelanggan
        </div>
        <div class="panel-body">

          <a href="?page=pelanggan&aksi=tambah_pelanggan" type="button" class="btn btn-info" style="margin-bottom: 10px;">
            Tambah
          </a>

          <?php if ($_SESSION['admin']) { ?>
            <a href="page/pelanggan/cetak_pelanggan.php" target="_blank" class="btn btn-success" style="margin-bottom: 10px;">
              Cetak Pelanggan
            </a>
          <?php } ?>

          <?php if ($_SESSION['admin']) { ?>
            <a href="page/pelanggan/export_excel.php" target="_blank" class="btn btn-success" style="margin-bottom: 10px;">
              Export Excel
            </a>
          <?php } ?>

          <?php
          $conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
          $checkUser = $conPelanggan->fetch_assoc();
          ?>

          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="example1">

              <thead>
                <tr>
                  <th class="text-center">No</th>
                  <th class="text-center">Kode</th>
                  <th class="text-center">Nama</th>
                  <th class="text-center">Alamat</th>
                  <th class="text-center">No. Telp.</th>
                  <th class="text-center">Paket</th>
                  <?php if ($_SESSION['admin']) { ?>
                    <th class="text-center">Harga</th>
                  <?php } ?>
                  <th class="text-center">Lokasi & ODP</th>
                  <?php if ($checkUser['ippelanggan'] == 'statik') { ?>
                    <th class="text-center">Ip</th>
                  <?php } ?>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>

                <?php

                $no = 1;

                $sql = $koneksi->query("SELECT * FROM tb_pelanggan JOIN tb_paket ON tb_pelanggan.paket = tb_paket.id_paket
                                         LEFT JOIN tb_perangkat ON tb_perangkat.id_perangkat = tb_pelanggan.id_perangkat
                                         ORDER BY tb_pelanggan.id_pelanggan DESC ");

                $getData = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
                $dataaa = $getData->fetch_assoc();

                while ($data = $sql->fetch_assoc()) {

                  // Lokasi Pelanggan
                  if (!empty($data['location'])) {
                    $coord_parts = explode(',', $data['location']);
                    $lat = floatval(trim($coord_parts[0]));
                    $lng = floatval(trim($coord_parts[1]));
                  }

                  if ($data['odp'] != NULL) {
                    // Lokasi ODP
                    $idd = $data['odp'];
                    $getODP = $koneksi->query("SELECT * FROM tbl_odp WHERE id_odp = $idd");
                    $ambil = $getODP->fetch_assoc();

                    if (!empty($ambil['location'])) {
                      $coord_parts = explode(',', $ambil['location']);
                      $latt = floatval(trim($coord_parts[0]));
                      $lngg = floatval(trim($coord_parts[1]));
                    }
                  }

                ?>

                  <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td class="text-center"><?php echo $data['kode_pelanggan'] ?></td>
                    <td class="text-center"><?php echo $data['nama_pelanggan'] ?></td>
                    <!-- <td><?= $data['tgl_pemasangan'] ?></td> -->
                    <!-- <td><?= $data['nama_perangkat'] ?: "Tidak Disebutkan" ?></td> -->
                    <td class="text-center"><?php echo $data['alamat'] ?></td>
                    <td class="text-center"><?php echo $data['no_telp'] ?></td>
                    <td class="text-center"><?php echo $data['nama_paket'] ?></td>
                    <?php if ($_SESSION['admin']) { ?>

                      <?php
                      if ($data3['ppn'] == 'aktif') {
                        $ppn = $data['harga'] * $data['ppn'];
                        $total_harga = $data['harga'] + $ppn;
                      } else {
                        $total_harga = $data['harga'];
                      }
                      ?>

                      <td class="text-center"><?= number_format($total_harga, 0, ",", ".") ?></td>
                      <td class="text-center">

                        <?php
                        if (!empty($data['location'])) {
                        ?>
                          <a href="https://www.google.com/maps?q=<?php echo $lat ?>,<?php echo $lng ?>" target="_blank">Rumah User</a>
                        <?php
                        }
                        ?>

                        <?php
                        if ($data['odp'] != NULL) {
                        ?>
                          ||
                          <a href="https://www.google.com/maps?q=<?php echo $latt ?>,<?php echo $lngg ?>" target="_blank"><?= $ambil['nama_odp'] ?></a>
                        <?php
                        }
                        ?>

                      </td>

                    <?php } ?>
                    <?php if ($checkUser['ippelanggan'] == 'statik') { ?>
                      <td class="text-center"><?php echo $data['ip_address'] ?></td>
                    <?php } ?>

                    <td class="text-center">
                      <!-- <a href="#" type="button" class="btn btn-info" data-toggle="modal" data-target="#mymodal<?php echo $data['id_pelanggan']; ?>"><i class="fa fa-edit"></i> Ubah</a> -->
                      <a href="?page=pelanggan&aksi=ubah_pelanggan&id=<?php echo $data['id_pelanggan'] ?>" type="button" class="btn btn-info"><i class="fa fa-edit"></i> Ubah</a>
                      <?php if ($dataaa['status'] == 'ya') { ?>
                        <a href="?page=pelanggan&aksi=hapus&id=<?php echo $data['id_pelanggan']; ?>&nama=<?= $data['nama_pelanggan'] ?>" class="btn btn-danger alert_notif" title=""><i class="fa fa-trash"></i> Hapus</a>
                      <?php } else { ?>
                        <a href="?page=pelanggan&aksi=hapus&id=<?php echo $data['id_pelanggan']; ?>" class="btn btn-danger alert_notif" title=""><i class="fa fa-trash"></i> Hapus</a>
                      <?php } ?>
                    </td>

                  <?php } ?>

              </tbody>

            </table>

          </div>
        </div>
      </div>

    <?php } else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
  }
    ?>