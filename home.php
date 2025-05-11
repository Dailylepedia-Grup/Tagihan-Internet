<?php if ($_SESSION['admin']) { ?>

  <?php

  $tgl = date("Y-m-d");
  $sql = $koneksi->query("select * from tb_kas where tgl_kas = '$tgl'");

  while ($tampil = $sql->fetch_assoc()) {
    $kas_hari_ini = $kas_hari_ini + $tampil['penerimaan'];
  }

  $tahunbulan = date("Y-m");
  $queryBulan = $koneksi->query("SELECT * FROM tb_kas WHERE SUBSTRING(tgl_kas, 1, 7) = '$tahunbulan'");
  while ($qq = $queryBulan->fetch_assoc()) {
    $kasbulanini = $kasbulanini + $qq['penerimaan'];
    $keluaranbulanini = $keluaranbulanini + $qq['pengeluaran'];
    $saldo = $kasbulanini - $keluaranbulanini;
  }

  $sql2 = $koneksi->query("select * from tb_kas");

  while ($tampil2 = $sql2->fetch_assoc()) {

    $penerimaan = $penerimaan + $tampil2['penerimaan'];
    $pengeluaran = $pengeluaran + $tampil2['pengeluaran'];
    // $saldo = $penerimaan - $pengeluaran;
  }

  $sql3 = $koneksi->query("SELECT COUNT(*) as status_keluhan FROM tbl_keluhan WHERE status_keluhan = 'menunggu'");

  while ($tampi3 = $sql3->fetch_assoc()) {
    $total_menunggu = $tampi3['status_keluhan'];
  }

  $sql4 = $koneksi->query("SELECT COUNT(*) as status_keluhan FROM tbl_keluhan WHERE status_keluhan = 'proses'");
  while ($tampi4 = $sql4->fetch_assoc()) {
    $total_proses = $tampi4['status_keluhan'];
  }

  $sql5 = $koneksi->query("SELECT COUNT(*) as status_keluhan FROM tbl_keluhan WHERE status_keluhan = 'selesai'");
  while ($tampi5 = $sql5->fetch_assoc()) {
    $total_selesai = $tampi5['status_keluhan'];
  }

  $sql6 = $koneksi->query("SELECT COUNT(*) as status_keluhan FROM tbl_keluhan WHERE status_keluhan = 'tidak merespon'");
  while ($tampi6 = $sql6->fetch_assoc()) {
    $total_kadaluarsa = $tampi6['status_keluhan'];
  }

  $sql7 = $koneksi->query("SELECT COUNT(*) as jml_pelanggan FROM tb_pelanggan");
  while ($tampil7 = $sql7->fetch_assoc()) {
    $totalPelanggan = $tampil7['jml_pelanggan'];
  }

  $sqlMikrotik = $koneksi->query("SELECT * FROM tbl_mikrotik");
  $cekMikrotik = $sqlMikrotik->fetch_assoc();

  $saatIni = date("mY");

  $ll = $koneksi->query("SELECT COUNT(*) AS total_lunas FROM tb_tagihan WHERE status_bayar = 1 AND SUBSTRING(bulan_tahun, 1, 6) = '$saatIni'");
  $data_ll = $ll->fetch_assoc();
  $total_lunas = $data_ll['total_lunas'];

  $belum_bayar = $koneksi->query("SELECT COUNT(*) AS total_belum_bayar FROM tb_tagihan WHERE status_bayar IS NULL AND SUBSTRING(bulan_tahun, 1, 6) = '$saatIni'");
  $data_belum_bayar = $belum_bayar->fetch_assoc();
  $total_belum_bayar = $data_belum_bayar['total_belum_bayar'];

  $total_lunass = 0;
  $query_lunas = $koneksi->query("SELECT SUM(jml_bayar) AS total_lunas FROM tb_tagihan WHERE status_bayar = 1 AND SUBSTRING(bulan_tahun, 1, 6) = '$saatIni'");
  $data_lunas = $query_lunas->fetch_assoc();
  $total_lunass = $data_lunas['total_lunas'];

  $belumBayar = 0;
  $query_belum_bayar = $koneksi->query("SELECT SUM(jml_bayar) AS total_belum_bayar FROM tb_tagihan WHERE status_bayar IS NULL AND SUBSTRING(bulan_tahun, 1, 6) = '$saatIni'");
  $dataBelumBayar = $query_belum_bayar->fetch_assoc();
  $belum_bayar = $dataBelumBayar['total_belum_bayar'];

  $prediksii = 0;
  $query_prediksi = $koneksi->query("SELECT SUM(jml_bayar) AS prediksi_bayar FROM tb_tagihan WHERE SUBSTRING(bulan_tahun, 1, 6) = '$saatIni'");
  $prediksi = $query_prediksi->fetch_assoc();
  $prediksi_total = $prediksi['prediksi_bayar'];

  ?>

  <section class="content-header">
    <?php
    if (empty($cekMikrotik)) {
    ?>
      <div class="alert alert-danger" role="alert">
        <b>INFORMASI : <br> ~ HARAP KONEKSIKAN TERLEBIH DAHULU WA API DAN JUGA MIKROTIK AGAR BERJALAN SEBAGAIMANA MESTINYA <br> !!! JANGAN MEMBUKA FITUR MIKROTIK JIKA BELUM MELAKUKAN KONEKSI MIKROTIK API AKAN MENGAKIBATKAN APLIKASI TAGIHAN TIDAK BISA TERBUKA !!!</b>
      </div>
    <?php
    }
    ?>
    <h1>
      Dashboard
      <small>Control panel</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?= ($kas_hari_ini !== NULL) ? number_format($kas_hari_ini, 0, ",", ".") : '0'; ?></h3>
            <p>Pemasukan Hari Ini</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?= ($kasbulanini != NULL) ? number_format($kasbulanini, 0, ",", ".") : '0'; ?><sup style="font-size: 20px"></sup></h3>
            <p>Pemasukan Bulan Ini</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?= ($keluaranbulanini != NULL) ? number_format($keluaranbulanini, 0, ",", ".") : '0'; ?></h3>
            <p>Pengeluaran Bulan Ini </p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?= ($saldo != NULL) ? number_format($saldo, 0, ",", ".") : '0'; ?></h3>
            <p>SALDO Bulan Ini</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?php echo number_format($total_menunggu, 0, ",", ".") ?></h3>
            <p>Menunggu Keluhan</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?php echo number_format($total_proses, 0, ",", ".") ?></h3>
            <p>Proses Keluhan</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?php echo number_format($total_selesai, 0, ",", ".") ?></h3>
            <p>Keluhan Selesai</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?php echo $totalPelanggan; ?></h3>
            <p>Jumlah Pelanggan</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?= number_format($total_lunass, 0, ',', '.') ?></h3>
            <p><?= $total_lunas ?> Pelanggan Bayar Bulan Ini</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?= number_format($belum_bayar, 0, ',', '.') ?></h3>
            <p><?= $total_belum_bayar ?> Pelanggan Belum Bayar Bulan Ini</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?= number_format($prediksi_total, 0, ',', '.') ?></h3>
            <p>Prediksi Pendapatan Bulan Ini</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>



    </div>

    <div class="row">
      <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="box box-primary box-solid">
          <div class="box-header with-border">
            <div class="text-left">Transaksi Terbaru</div>
          </div>

          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover" id="example1">

                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Pelanggan</th>
                    <th class="text-center">Bulan/Tahun</th>
                    <th class="text-center">Tagihan</th>
                    <th class="text-center">Waktu</th>
                    <th class="text-center">Level</th>
                    <th class="text-center">Diterima Oleh</th>
                  </tr>
                </thead>
                <tbody>

                  <?php

                  $i = 1;
                  $queryDataBaru = $koneksi->query("SELECT * FROM tb_tagihan 
                  INNER JOIN tb_pelanggan ON tb_tagihan.id_pelanggan=tb_pelanggan.id_pelanggan
                  LEFT JOIN tb_user ON tb_tagihan.user_id=tb_user.id
                  WHERE tb_tagihan.waktu_bayar IS NOT NULL
                  ORDER BY tb_tagihan.waktu_bayar DESC");

                  while ($Trans = $queryDataBaru->fetch_assoc()) {
                  ?>

                    <tr>
                      <td class="text-center"><?= $i++; ?></td>
                      <td class="text-center"><?= $Trans['nama_pelanggan']; ?></td>
                      <td class="text-center"><?= substr($Trans['bulan_tahun'], 0, 2) . ' - ' . substr($Trans['bulan_tahun'], 2); ?></td>
                      <td class="text-center">Rp. <?= number_format($Trans['jml_bayar'], 0, ',', '.') ?></td>
                      <td class="text-center"><?= $Trans['waktu_bayar']; ?></td>

                      <?php if ($Trans['user_id'] === NULL) { ?>
                        <td class="text-center">Payment Gateway</td>
                      <?php } else { ?>
                        <td class="text-center"><?= $Trans['level']; ?></td>
                      <?php } ?>

                      <?php if ($Trans['user_id'] === NULL) { ?>
                        <td class="text-center">Midtrans</td>
                      <?php } else { ?>
                        <td class="text-center"><?= $Trans['nama_user']; ?></td>
                      <?php } ?>

                    </tr>

                  <?php
                  }
                  ?>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php } ?>

  <!-- TEKNISI -->
  <?php if ($_SESSION['teknisi']) { ?>
    <?php
    $sql3 = $koneksi->query("SELECT COUNT(*) as status_keluhan FROM tbl_keluhan WHERE status_keluhan = 'menunggu'");

    while ($tampi3 = $sql3->fetch_assoc()) {
      $total_menunggu = $tampi3['status_keluhan'];
    }

    $sql4 = $koneksi->query("SELECT COUNT(*) as status_keluhan FROM tbl_keluhan WHERE status_keluhan = 'proses'");
    while ($tampi4 = $sql4->fetch_assoc()) {
      $total_proses = $tampi4['status_keluhan'];
    }

    $sql5 = $koneksi->query("SELECT COUNT(*) as status_keluhan FROM tbl_keluhan WHERE status_keluhan = 'selesai'");
    while ($tampi5 = $sql5->fetch_assoc()) {
      $total_selesai = $tampi5['status_keluhan'];
    }

    $sql6 = $koneksi->query("SELECT COUNT(*) as status_keluhan FROM tbl_keluhan WHERE status_keluhan = 'tidak merespon'");
    while ($tampi6 = $sql6->fetch_assoc()) {
      $total_kadaluarsa = $tampi6['status_keluhan'];
    }
    ?>

    <section class="content-header">
      <h1>
        Dashboard
        <small>Teknisi</small>
      </h1>
    </section>

    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo number_format($total_menunggu, 0, ",", ".") ?></h3>
              <p>Menunggu Keluhan</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo number_format($total_proses, 0, ",", ".") ?></h3>
              <p>Proses Keluhan</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo number_format($total_selesai, 0, ",", ".") ?></h3>
              <p>Keluhan Selesai</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo number_format($total_kadaluarsa, 0, ",", ".") ?></h3>
              <p>Keluhan Kadaluarsa</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      <?php } ?>

      <!-- END TEKNISI -->

      <?php if ($_SESSION['user']) { ?>

        <?php

        function tglIndonesia3($str2)
        {
          $tr2   = trim($str2);
          $str2    = str_replace(array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'), array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'), $tr2);
          return $str2;
        }

        ?>

        <?php
        // Query untuk mengambil informasi yang paling baru
        $sql_informasi = $koneksi->query("SELECT * FROM tbl_informasi ORDER BY id_informasi DESC LIMIT 1");

        // Memeriksa apakah ada informasi yang ditemukan
        if ($sql_informasi->num_rows > 0) {
          $informasi = $sql_informasi->fetch_assoc();
        ?>
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary box-solid">
                <div class="box-header with-border">
                  Papan Informasi Terbaru
                </div>
                <div class="panel-body">
                  <h4><b><?= $informasi['judul_informasi'] ?></b></h4>
                  <p><?= $informasi['isi_informasi'] ?></p>
                </div>
              </div>
            </div>

          </div>
        <?php
        } else {
        ?>
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary box-solid">
                <div class="box-header with-border">
                  Papan Informasi Terbaru
                </div>
                <div class="panel-body">
                  <h4><b>Tidak Ada Informasi Terbaru</b></h4>
                </div>
              </div>
            </div>
          </div>
        <?php
        }
        ?>

        <!-- query mikrotik  -->

        <?php
        $queryUsers = $koneksi->query("SELECT * FROM tb_pelanggan WHERE id_pelanggan=$id_pelanggan");
        $cekIp = $queryUsers->fetch_assoc();

        $ipPelanggan = $cekIp['ip_address'];

        $ipPelanggan .= '/32'; // sesuaikan subnet

        $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
        $result = mysqli_query($koneksi, $sql_mikrotik);
        $row = mysqli_fetch_assoc($result);

        $API = new RouterosAPI();

        if (!empty($row)) {
          if ($API->connect($row['ip'], $row['username'], $row['password'])) {

            $ARRAY = $API->comm("/queue/simple/print", array(
              "?target" => $ipPelanggan,
            ));

            if (count($ARRAY) > 0) {

              $data = $ARRAY[0]['bytes'];

              list($download, $upload) = explode('/', $data);

              // Mengkonversi nilai ke dalam format yang lebih mudah dibaca
              $downloadInMB = round($download / (1024 * 1024), 2); // Mengkonversi ke MB dengan pembulatan 2 desimal
              $uploadInMB = round($upload / (1024 * 1024), 2); // Mengkonversi ke MB dengan pembulatan 2 desimal

              // Ganti ke GB jika lebih dari 1024 MB (1 GB)
              if ($downloadInMB > 1024) {
                $downloadInGB = round($downloadInMB / 1024, 2);
                if ($downloadInGB > 1000) {
                  $downloadFormatted = round($downloadInGB / 1024, 2) . ' TB'; // Konversi ke TB dan tambahkan satuan TB
                } else {
                  $downloadFormatted = $downloadInGB . ' GB'; // Tampilkan dalam satuan GB
                }
              } else {
                $downloadFormatted = $downloadInMB . ' MB';
              }

              if ($uploadInMB > 1024) {
                $uploadInGB = round($uploadInMB / 1024, 2);
                if ($uploadInGB > 1000) {
                  $uploadFormatted = round($uploadInGB / 1024, 2) . ' TB'; // Konversi ke TB dan tambahkan satuan TB
                } else {
                  $uploadFormatted = $uploadInGB . ' GB'; // Tampilkan dalam satuan GB
                }
              } else {
                $uploadFormatted = $uploadInMB . ' MB';
              }
        ?>
              <div class="row">

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">UPLOAD TERPAKAI</span>
                      <span class="info-box-number"><?= $downloadFormatted ?></span>
                      <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                      </div>
                      <span class="progress-description">
                        Akan Menjadi 0 Jika Server Mati
                      </span>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">DOWNLOAD TERPAKAI</span>
                      <span class="info-box-number"><?= $uploadFormatted ?></span>
                      <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                      </div>
                      <span class="progress-description">
                        Akan Menjadi 0 Jika Server Mati
                      </span>
                    </div>
                  </div>
                </div>

              </div>
        <?php
            }
            $API->disconnect();
          }
        }
        ?>

        <!-- end query mikrotik  -->
        <div class="row">
          <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="box box-primary box-solid">
              <div class="box-header with-border">
                <div class="text-left">Data Tagihan</div>
              </div>

              <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover" id="example1">

                    <?php
                    $try = $koneksi->query("SELECT * FROM tbl_pgate WHERE id_pgat=1");
                    $checking = $try->fetch_assoc();
                    ?>

                    <thead>
                      <tr>
                        <th>No</th>
                        <th>#</th>
                        <th>Nama Pelanggan</th>
                        <th>Nama Paket</th>
                        <th>Bulan/Tahun</th>
                        <th>Tagihan</th>
                        <th>Status Bayar</th>
                        <th>Jatuh Tempo</th>
                        <th>Status Internet</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php

                      $no = 1;

                      $sql = $koneksi->query("select tb_tagihan.*, tb_pelanggan.nama_pelanggan, tb_pelanggan.alamat, tb_pelanggan.ip_address, tb_paket.nama_paket, tb_pelanggan.no_telp, tb_pelanggan.jatuh_tempo
                      from tb_tagihan
                      inner join tb_pelanggan on tb_tagihan.id_pelanggan=tb_pelanggan.id_pelanggan
                      inner join tb_paket on tb_pelanggan.paket=tb_paket.id_paket
                      where tb_tagihan.id_pelanggan='$id_pelanggan'
                      order by tb_tagihan.id_tagihan desc
                    ");

                      while ($data = $sql->fetch_assoc()) {

                        $no_hp = $data['no_telp'];
                        $status = $data['status_bayar'];
                        $blokir = $data['blokir_status'];

                        if ($status == 0) {
                          $status_t = "Belum Lunas";
                          $color = "red";
                        } else {
                          $status_t = "Lunas";
                          $color = "green";
                        }

                        if ($blokir == null) {
                          $blokir_t = "Aktif";
                          $blokir_color = "green";
                        } else {
                          $blokir_t = "Terblokir";
                          $blokir_color = "red";
                        }

                        $bulan_tahun = $data['bulan_tahun'];

                        $tahun  = str_split($bulan_tahun);

                        $tahun1 = $tahun[0];
                        $tahun2 = $tahun[1];
                        $tahun3 = $tahun[2];
                        $tahun4 = $tahun[3];
                        $tahun5 = $tahun[4];
                        $tahun6 = $tahun[5];

                        $bulan = $tahun1 . $tahun2;

                        $tahun = $tahun3 . $tahun4 . $tahun5 . $tahun6;
                      ?>
                        <tr>
                          <td style="color: <?php echo $color ?>"><?php echo $no++; ?></td>


                          <?php if ($status == 0 || $status == NULL) { ?>

                            <?php if (!empty($checking)) { ?>
                              <td>
                                <form action="<?php echo $base ?>payment/details-payment.php" method="POST">
                                  <input type="hidden" name="id_tagihan" value="<?php echo $data['id_tagihan']; ?>">
                                  <input type="hidden" name="nama_pelanggan" value="<?php echo $data['nama_pelanggan']; ?>">
                                  <button class="btn btn-success" style="margin-bottom: 10px;">Bayar</button>
                                </form>
                              </td>
                            <?php } else { ?>

                              <td>
                                <!-- <a class="btn btn-info btn-sm" title=""><i class="fa fa-money"></i> Cetak Bukti Bayar</a> -->
                                <a target=" blank" href="page/transaksi/cetak.php?id_tagihan=<?php echo $data['id_tagihan']; ?>" class="btn btn-info btn-sm" title=""><i class="fa fa-money"></i> Cetak Bukti Bayar</a>
                              </td>

                            <?php } ?>

                          <?php } else { ?>

                            <td>
                              <a target=" blank" href="page/transaksi/cetak.php?id_tagihan=<?php echo $data['id_tagihan']; ?>" class="btn btn-info btn-sm" title=""><i class="fa fa-money"></i> Cetak Bukti Bayar</a>
                            </td>

                          <?php } ?>

                          <td style="color: <?php echo $color ?>"><?php echo $data['nama_pelanggan'] ?></td>
                          <td style="color: <?php echo $color ?>"><?php echo $data['nama_paket'] ?></td>
                          <td style="color: <?php echo $color ?>"><?php echo $bulan ?>/<?php echo $tahun ?> </td>
                          <td style="color: <?php echo $color ?>"><?php echo number_format($data['jml_bayar'], 0, ",", ".") ?></td>
                          <td style="color: <?php echo $color ?>"><?php echo $status_t; ?></td>
                          <td style="color: <?php echo $color ?>"><?php echo $data['jatuh_tempo']; ?></td>
                          <td style="color: <?php echo $blokir_color ?>"><?php echo $blokir_t; ?></td>
                        </tr>

                      <?php  } ?>

                    </tbody>

                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <form role="form" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h5 class="modal-title" id="exampleModalLabel">Bukti Pembayaran</h5>
                </div>
                <div class="modal-body">

                  <div class="form-group">
                    <label for="exampleFormControlSelect">Bulan</label>
                    <select class="form-control" id="exampleFormControlSelect" name="id_tagihan">
                      <?php
                      $bulan_sekarang = date('mY');
                      $tagihan = $koneksi->query("SELECT * FROM tb_tagihan WHERE id_pelanggan = '$id_pelanggan' ORDER BY bulan_tahun DESC");

                      while ($tt = $tagihan->fetch_assoc()) {

                        $bulan_tahun = $tt['bulan_tahun'];
                        $bulan = substr($bulan_tahun, 0, 2); // Mengambil dua digit pertama sebagai bulan
                        $tahun = substr($bulan_tahun, 2); // Mengambil digit tersisa sebagai tahun
                        $bulan_tahun_format = $bulan . '/' . $tahun; // Menggabungkan bulan dan tahun dalam format "09/2023"

                        // Tambahkan opsi tagihan
                        echo '<option value="' . $tt['id_tagihan'] . '">' . $bulan_tahun_format;

                        // Tambahkan teks "Lunas" jika status bayar adalah 1
                        if ($bulan_sekarang == $tt['bulan_tahun']) {
                          if ($tt['status_bayar'] == 1) {
                            echo ' - Lunas';
                          } else {
                            echo ' - Belum Lunas';
                          }
                        }

                        echo '</option>';
                      }
                      ?>
                    </select>

                  </div>

                  <div class="form-group">
                    <label for="exampleFormControlSelect1">Transfer Ke</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="transfer_ke">
                      <option value="">Pilih Bank</option>
                      <?php
                      $daftar_bank = $koneksi->query("SELECT * FROM tbl_rekening");
                      // $daftar_bank = $koneksi->query("SELECT * FROM tb_tagihan INNER JOIN tbl_buktibayar ON tb_tagihan.id_tagihan=tbl_buktibayar.id_tagihan");
                      while ($daftar = $daftar_bank->fetch_assoc()) {
                      ?>
                        <option value="<?= $daftar['id_rekening'] ?>" data-nama-bank="<?= $daftar['nama_bank'] ?>" data-nomor-rekening="<?= $daftar['nomor_rekening'] ?>" data-nama-rekening="<?= $daftar['nama_rekening'] ?>">
                          <?= $daftar['nama_bank'] ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>

                  <p id="keterangan"></p>

                  <div class="form-group">
                    <label for="exampleFormControlFile1">Bukti Transfer</label>
                    <input type="file" class="form-control-file" name="gambar">
                  </div>

                  <div class="form-group">
                    <label for="exampleFormControlTextarea1">Keterangan</label>
                    <textarea class="form-control" name="keterangan" id="exampleFormControlTextarea1" rows="3" placeholder="Kosongkan Jika Tidak Ada."></textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                  <button type="submit" name="bukti_tf" class="btn btn-primary">Kirim Bukti</button>
                </div>
              </form>
            </div>
          </div>
        </div>


        <?php

        $ff = $koneksi->query("SELECT * FROM tb_pelanggan WHERE id_pelanggan='$id_pelanggan'");
        $ss = $ff->fetch_assoc();

        $nama_user = $ss['nama_pelanggan'];

        $test = $koneksi->query("SELECT * FROM tbl_nomorphone");
        $qq = $test->fetch_assoc();

        $nama_pemilik = $qq['nama_pemilik'];

        $sql_token = "SELECT * FROM tbl_token WHERE id_token = 1"; // Sesuaikan dengan query yang sesuai
        $result = mysqli_query($koneksi, $sql_token);
        $row = mysqli_fetch_assoc($result);
        $authorizationToken = $row['token'];

        if (isset($_POST['bukti_tf'])) {
          $transfer_ke = $_POST['transfer_ke'];
          $pelanggan = $id_pelanggan;
          $id_tagihan = $_POST['id_tagihan'];
          $gambar = $_FILES['gambar']['name'];
          $gambar_tmp = $_FILES['gambar']['tmp_name'];
          $tanggal_terima = (new DateTime())->format('Y-m-d H:i:s');
          $keterangan = $_POST['keterangan'];

          if (!empty($gambar)) {
            // Direktori tempat menyimpan gambar
            $upload_directory = "images/bukti_tf/"; // Gantilah dengan direktori yang sesuai

            // Membuat nama unik untuk gambar
            $gambar_unik = uniqid() . '_' . $gambar;

            // Pindahkan gambar ke direktori yang ditentukan
            if (move_uploaded_file($gambar_tmp, $upload_directory . $gambar_unik)) {
              // Simpan nama gambar dalam database
              $gambar = $gambar_unik;

              $koneksi->query("INSERT INTO tbl_buktibayar (id_rekening, id_pelanggan, id_tagihan, gambar, keterangan, tanggal_terima) VALUES ('$transfer_ke', '$pelanggan', '$id_tagihan', '$gambar', '$keterangan', '$tanggal_terima')");

              $curl = curl_init();

              curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                  'target' => $qq['my_number'],
                  'message' => 'Halo ' . $nama_pemilik . ' Atas Nama ' . $nama_user . ' Sudah Melakukan Pembayaran Melalui Trasfer Bank, Harap Segera Periksa Segera.',
                ),
                CURLOPT_HTTPHEADER => array(
                  'Authorization: ' . $authorizationToken
                ),
              ));

              $response = curl_exec($curl);

              curl_close($curl);
              // echo $response;

              echo "
    <script>
      setTimeout(function() {
        swal({
          title: 'Transfer',
          text: 'Berhasil Mengirim Bukti',
          type: 'success'
        }, function() {
          window.location = 'index.php';
        });
      }, 300);
    </script>
  ";
            } else {
              echo "
    <script>
      swal({
        title: 'Gagal Upload',
        text: 'Gagal mengunggah gambar bukti transfer.',
        type: 'error'
      });
    </script>
  ";
            }
          } else {
            echo "
  <script>
    swal({
      title: 'Gagal Upload',
      text: 'Silakan pilih gambar bukti transfer terlebih dahulu.',
      type: 'error'
    });
  </script>
";
          }
        }
        ?>

      <?php } ?>

      <?php if ($_SESSION['kasir']) { ?>

        <section class="content-header">

        </section>

        <div class="pad margin no-print">
          <div class="callout callout-info" style="margin-bottom: 0!important; font-size: 25px;">

            Selamat Datang Di Aplikasi Tagihan Internet
          </div>
        </div>
      <?php } ?>