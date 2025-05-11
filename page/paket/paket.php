<?php if ($_SESSION['admin']) { ?>

  <?php
  $cekMikrotik = $koneksi->query("SELECT * FROM tbl_mikrotik");

  $getData = $koneksi->query("SELECT * FROM tbl_paketmikrotik");
  $data1 = $getData->fetch_assoc();
  ?>

  <div class="row">
    <!-- First Column -->
    <?php if ($cekMikrotik->num_rows > 0) { ?>
      <div class="col-md-6">
        <div class="box box-primary box-solid">
          <div class="box-header with-border">
            Konfigurasi Mikrotik Paket
          </div>
          <div class="panel-body">
            <form method="post" action="">
              <div class="form-group">
                <label for="exampleFormControlSelect1">Ambil Nama Paket Dari PPPOE Profile</label>
                <select class="form-control" name="paketpppoe" id="exampleFormControlSelect1">
                  <option value="tidak" <?php echo ($data1['status'] == 'tidak') ? 'selected' : ''; ?>>Tidak</option>
                  <option value="ya" <?php echo ($data1['status'] == 'ya') ? 'selected' : ''; ?>>Ya</option>
                </select>
              </div>
              <button type="submit" name="paketppp" class="btn btn-success" style="margin-bottom: 10px;">
                Simpan
              </button>
            </form>
          </div>
        </div>
      </div>

      <?php
      if (isset($_POST['paketppp'])) {
        $sPaketPpp = $_POST['paketpppoe'];

        $cekPaketMikrotik = $koneksi->query("SELECT * FROM tbl_paketmikrotik");

        if ($cekPaketMikrotik->num_rows > 0) {
          $pakQuery = $koneksi->query("UPDATE tbl_paketmikrotik SET status = '$sPaketPpp' WHERE id_paketmikrotik = 1");
        } else {
          $pakQuery = $koneksi->query("INSERT INTO tbl_paketmikrotik (id_paketmikrotik , status) VALUES (1, '$sPaketPpp')");
        }

        if ($pakQuery) {
          echo "
            <script>
                setTimeout(function() {
                    swal({
                        title: 'Data Paket',
                        text: 'Berhasil Dikaitkan Dengan Mikrotik!',
                        type: 'success'
                    }, function() {
                        window.location = '?page=paket';
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

    <!-- Second Column -->
    <div class="col-md-6">
      <div class="box box-primary box-solid">
        <div class="box-header with-border">
          Gunakan PPN
        </div>
        <div class="panel-body">
          <form method="post" action="">
            <div class="form-group">
              <label>Aktifkan Fitur PPN</label>
              <select class="form-control" name="ppn">
                <option value="tidak" <?php echo ($data1['ppn'] == 'tidak') ? 'selected' : ''; ?>>Tidak Aktif</option>
                <option value="aktif" <?php echo ($data1['ppn'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
              </select>
            </div>
            <button type="submit" name="ppsn" class="btn btn-success" style="margin-bottom: 10px;">
              Simpan
            </button>
          </form>
        </div>
      </div>
    </div>

    <?php
    if (isset($_POST['ppsn'])) {
      $ppn = $_POST['ppn'];

      // $cekPPN = $koneksi->query("SELECT * FROM tbl_paketmikrotik");

      $c = $koneksi->query("UPDATE tbl_paketmikrotik SET ppn = '$ppn' WHERE id_paketmikrotik = 1");

      if ($c) {
        echo "
            <script>
                setTimeout(function() {
                    swal({
                        title: 'PPN',
                        text: 'Berhasil Di Ubah',
                        type: 'success'
                    }, function() {
                        window.location = '?page=paket';
                    });
                }, 300);
            </script>
        ";
      }
    }
    ?>



  </div>


  <div class="row">
    <div class="col-md-12">
      <!-- Advanced Tables -->
      <div class="box box-primary box-solid">
        <div class="box-header with-border">
          Data Paket
        </div>
        <div class="panel-body">
          <a href="?page=paket&aksi=tambah_paket" type="button" class="btn btn-info" style="margin-bottom: 10px;">
            Tambah
          </a>

          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="example1">

              <thead>
                <tr>
                  <th class="text-center">No</th>
                  <th class="text-center">Paket</th>
                  <th class="text-center">Harga</th>
                  <?php if ($data1['ppn'] == 'aktif') { ?>
                    <th class="text-center">PPN</th>
                    <th class="text-center">Total Harga</th>
                  <?php } ?>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>

                <?php

                $no = 1;

                $sql = $koneksi->query("select * from tb_paket order by id_paket desc");

                while ($data = $sql->fetch_assoc()) {

                ?>
                  <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td class="text-center"><?php echo $data['nama_paket'] ?></td>
                    <td class="text-center"><?php echo number_format($data['harga'], 0, ",", ".") ?></td>
                    <?php if ($data1['ppn'] == 'aktif') { ?>
                      <?php
                      // Ganti 'ppn' dengan nama kolom yang sesuai pada tabel Anda
                      $nilai_ppn_database = $data['ppn'];

                      // Ubah nilai dari database ke bentuk persentase jika tidak NULL
                      $nilai_ppn_tampilan = ($nilai_ppn_database !== NULL) ? $nilai_ppn_database * 100 : NULL;
                      $fix = ($nilai_ppn_tampilan !== NULL) ? $nilai_ppn_tampilan . " %" : "";

                      // Perbarui cara menetapkan nilai ke atribut 'value'
                      ?>
                      <td class="text-center"><?= $fix ?></td>
                      <?php
                      $ppn = $data['harga'] * $data['ppn'];
                      $total_harga = $data['harga'] + $ppn;
                      ?>
                      <td class="text-center"><?= number_format($total_harga, 0, ",", ".") ?></td>
                    <?php } ?>
                    <td class="text-center">

                      <a href="?page=paket&aksi=ubah_paket&id=<?= $data['id_paket']; ?>" type="button" class="btn btn-info"> <i class="fa fa-edit"></i> Ubah</a>

                      <a href="?page=paket&aksi=hapus&id=<?php echo $data['id_paket']; ?>" class="btn btn-danger" title=""><i class="fa fa-trash"></i> Hapus</a>

                    </td>

                  </tr>

                <?php } ?>

              </tbody>

            </table>

          </div>
        </div>
      </div>

    <?php } else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
  } ?>