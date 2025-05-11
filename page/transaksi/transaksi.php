<?php if ($_SESSION['admin'] || $_SESSION['kasir']) { ?>
  <?php
  function tglIndonesia3($str2)
  {
    $tr2   = trim($str2);
    $str2    = str_replace(array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'), array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'), $tr2);
    return $str2;
  }

  $conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
  $checkUser = $conPelanggan->fetch_assoc();

  if (isset($_POST['filter'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $bulantahun = $bulan . $tahun;
  } else {
    $bulan = date('m');
    $tahun = date('Y');
    $bulantahun = $bulan . $tahun;
  }

  if ($bulantahun != "") {

    $ll = $koneksi->query("SELECT COUNT(*) AS total_lunas FROM tb_tagihan WHERE status_bayar = 1 AND SUBSTRING(bulan_tahun, 1, 6) = '$bulantahun'");
    $data_ll = $ll->fetch_assoc();
    $total_lunas = $data_ll['total_lunas'];

    $belum_bayar = $koneksi->query("SELECT COUNT(*) AS total_belum_bayar FROM tb_tagihan WHERE status_bayar IS NULL AND SUBSTRING(bulan_tahun, 1, 6) = '$bulantahun'");
    $data_belum_bayar = $belum_bayar->fetch_assoc();
    $total_belum_bayar = $data_belum_bayar['total_belum_bayar'];

    $total_lunass = 0;
    $query_lunas = $koneksi->query("SELECT SUM(jml_bayar) AS total_lunas FROM tb_tagihan WHERE status_bayar = 1 AND SUBSTRING(bulan_tahun, 1, 6) = '$bulantahun'");
    $data_lunas = $query_lunas->fetch_assoc();
    $total_lunass = $data_lunas['total_lunas'];

    $belumBayar = 0;
    $query_belum_bayar = $koneksi->query("SELECT SUM(jml_bayar) AS total_belum_bayar FROM tb_tagihan WHERE status_bayar IS NULL AND SUBSTRING(bulan_tahun, 1, 6) = '$bulantahun'");
    $dataBelumBayar = $query_belum_bayar->fetch_assoc();
    $belum_bayar = $dataBelumBayar['total_belum_bayar'];

    $q = $koneksi->query("SELECT * FROM tb_tagihan WHERE bulan_tahun='$bulantahun'");
    $cek = $q->fetch_assoc();
  }

  ?>

  <?php if ($_SESSION['admin']) { ?>

    <div class="row">
      <div class="col-md-6 col-sm-6 col-xs-12">

        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><b>Detail Pembayaran Lunas</b></span>
            <span class="info-box-text">Jumlah Yang Bayar : <?= $total_lunas ?> </span>
            <span class="info-box-text">Nominal Terbayar : <?= number_format($total_lunass, 0, ",", ".") ?> </span>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-sm-6 col-xs-12">

        <div class="info-box bg-red">
          <span class="info-box-icon"><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><b>Detail Pembayaran Belum Lunas</b></span>
            <span class="info-box-text">Jumlah Yang Belum Bayar : <?= $total_belum_bayar ?> </span>
            <span class="info-box-text">Nominal Tidak Terbayar : <?= number_format($belum_bayar, 0, ",", ".") ?> </span>
          </div>
        </div>
      </div>

    </div>

  <?php } ?>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary box-solid">
        <div class="box-header with-border">
          Data Tagihan
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="example1">
              <div class="col-md-12">
                <form method="POST">
                  <div class="col-md-3">
                    <div class="form-group">
                      <br><label style="color: white; font-weight: bold;">Bulan</label> <br>
                      <select required="" class="form-control" name="bulan">
                        <option value="">--Pilih Bulan--</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <br><label style="color: white; font-weight: bold;">Tahun</label> <br>
                      <select required="" class="form-control" name="tahun">
                        <option value="">--Pilih Tahun--</option>
                        <?php
                        $tahun = date("Y");
                        for ($i = $tahun - 3; $i <= $tahun; $i++) {
                          echo '
                            <option value="' . $i . '">' . $i . '</option>
                          ';
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      <button type="submit" name="filter" style="margin-top: 45px;" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <a href="?page=transaksi&aksi=tambah" class="btn btn-default" style="margin-top: 45px;" title=""><i class="fa fa-plus"></i> Tambah Tagihan</a>
                    </div>
                  </div>

                </form>
              </div>
              <thead>
                <tr>

                  <th>No</th>
                  <th>Kode Pelanggan</th>
                  <th>Cetak Invoice</th>
                  <th>Nama Pelanggan</th>
                  <th>Nama Paket</th>
                  <th>Bulan/Tahun</th>
                  <th>Tagihan</th>
                  <th>Status Bayar</th>
                  <th>Jatuh Tempo</th>
                  <th>Pesan Tagih</th>
                  <th>Status Blokir</th>
                  <th>Aksi</th>
                  <th>Blokir</th>
                  <th>Hapus Tagihan</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (isset($_POST['filter'])) {
                  $bulan = $_POST['bulan'];
                  $tahun = $_POST['tahun'];
                  $bulantahun = $bulan . $tahun;
                } else {
                  $bulan = date('m');
                  $tahun = date('Y');
                  $bulantahun = $bulan . $tahun;
                }
                ?>
                <div class="callout callout-warning">
                  <p style="margin-left: 10px; font-size: 20px;">Bulan <?php echo $bulan ?>, Tahun <?php echo $tahun ?> </p>
                </div>
                <?php
                if ($bulantahun != "") {
                  $no = 1;
                  $sql = $koneksi->query("SELECT 
                  tb_tagihan.*, 
                  tb_pelanggan.nama_pelanggan, 
                  tb_pelanggan.alamat, 
                  tb_pelanggan.ip_address, 
                  tb_pelanggan.jatuh_tempo, 
                  tb_paket.nama_paket, 
                  tb_pelanggan.no_telp, 
                  tb_pelanggan.kode_pelanggan,
                  tbl_buktibayar.id_buktibayar, 
                  tbl_buktibayar.id_rekening, 
                  COALESCE(tbl_buktibayar.id_tagihan, tb_tagihan.id_tagihan) AS id_tagihan, 
                  tbl_buktibayar.gambar, 
                  tbl_buktibayar.keterangan, 
                  tbl_buktibayar.tanggal_terima,
                  tbl_rekening.id_rekening, 
                  tbl_rekening.nama_bank, 
                  tbl_rekening.nomor_rekening, 
                  tbl_rekening.nama_rekening,
                  tb_user.username
              FROM tb_tagihan
              INNER JOIN tb_pelanggan ON tb_tagihan.id_pelanggan = tb_pelanggan.id_pelanggan
              INNER JOIN tb_paket ON tb_pelanggan.paket = tb_paket.id_paket
              LEFT JOIN tbl_buktibayar ON tb_tagihan.id_tagihan = tbl_buktibayar.id_tagihan
              LEFT JOIN tbl_rekening ON tbl_buktibayar.id_rekening = tbl_rekening.id_rekening
              LEFT JOIN tb_user ON tb_pelanggan.id_pelanggan = tb_user.id_pelanggan
              WHERE tb_tagihan.bulan_tahun = '$bulantahun'
              ORDER BY tb_tagihan.status_bayar ASC");

                  while ($data = $sql->fetch_assoc()) {

                    $jumlah_data = $sql->num_rows;
                    $id = $data['id_tagihan'];
                    $no_hp = $data['no_telp'];
                    $status = $data['status_bayar'];
                    $nama = $data['nama_pelanggan'];
                    $paket = $data['nama_paket'];
                    $tagihan = number_format($data['jml_bayar'], 0, ",", ".");
                    $waktu_indonesia = date('d F Y H:i:s');
                    $jatuh_tempo = $data['jatuh_tempo'];
                    $blokir = $data['blokir_status'];

                    // Get the current protocol (http or https)
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

                    // Get the current host (e.g., localhost or www.example.com)
                    $host = $_SERVER['HTTP_HOST'];

                    // Get the base URL of the current script
                    $baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

                    // Construct the full URL for the invoice
                    $invoice = $protocol . $host . $baseUrl . "page/transaksi/cetak.php?id_tagihan=" . urlencode($data['id_tagihan']);

                    if ($status == NULL) {
                      $status_t = "Belum Lunas";
                      $color = "red";
                    } else {
                      $status_t = "Lunas";
                      $color = "green";
                    }

                    if ($blokir == null) {
                      $blokir_t = "Tidak Terblokir";
                      $blokir_color = "green";
                    } else {
                      $blokir_t = "Terblokir";
                      $blokir_color = "red";
                    }

                    if ($checkUser['ippelanggan'] == 'dynamic') {
                      $ip = $data['username'];
                    } else {
                      $ip = $data['ip_address'];
                    }

                ?>
                    <tr>

                      <td style="color: <?php echo $color ?>"><?php echo $no++; ?></td>
                      <td style="color: <?php echo $color ?>"><?php echo $data['kode_pelanggan'] ?></td>
                      <td><a target="blank" href="page/transaksi/cetak.php?id_tagihan=<?php echo $data['id_tagihan']; ?>" class="btn btn-info btn-xs" title=""><i class="fa fa-money"></i> Cetak Invoice</a></td>
                      <td style="color: <?php echo $color ?>"><?php echo $data['nama_pelanggan'] ?></td>
                      <td style="color: <?php echo $color ?>"><?php echo $data['nama_paket'] ?></td>
                      <td style="color: <?php echo $color ?>"><?php echo $bulan ?> / <?php echo $tahun ?></td>
                      <td style="color: <?php echo $color ?>"><?php echo number_format($data['jml_bayar'], 0, ",", ".") ?></td>
                      <td style="color: <?php echo $color ?>"><?php echo $status_t; ?></td>
                      <td style="color: <?php echo $color ?>"><?php echo $jatuh_tempo; ?></td>
                      <td style="color: <?php echo $color ?>"><?php echo $data['terkirim']; ?></td>
                      <!-- <td style="color: <?php echo $color ?>"><?php echo $data['ip_address']; ?></td> -->
                      <td style="color: <?php echo $blokir_color ?>"><?php echo $blokir_t; ?></td>
                      <?php if ($status == 0) { ?>
                        <td>
                          <a href="" type="button" class="bayarBtn btn btn-success btn-xs" data-idtagihan="<?php echo $id ?>" data-idpelanggan="<?php echo $data['id_tagihan']; ?>" data-nohp="<?php echo $no_hp; ?>" data-ip_pelanggan="<?php echo $ip; ?>" data-nama="<?php echo $data['nama_pelanggan']; ?>" data-kode="<?php echo $data['kode_pelanggan'] ?>" data-tagihan="<?php echo $tagihan; ?>" data-waktu="<?php echo $waktu_indonesia ?>" data-invoice="<?php echo $invoice ?>"><i class="fa fa-money"></i> Bayar</a>
                          <a href="" type="button" class="tagihBtn btn btn-warning btn-xs" data-jatuhtempo="<?php echo $jatuh_tempo ?>" data-kode="<?php echo $data['kode_pelanggan'] ?>" data-idpelanggan="<?php echo $data['id_tagihan']; ?>" data-nohp="<?php echo $no_hp; ?>" data-nama="<?php echo $data['nama_pelanggan']; ?>" data-tagihan="<?php echo $tagihan; ?>" data-waktu="<?php echo $waktu_indonesia ?>"><i class="fa fa-whatsapp"></i> Tagih</a>
                        </td>
                      <?php } else { ?>
                        <td>
                          <a href="?page=transaksi&aksi=hapus&id=<?php echo $data['id_tagihan']; ?>" class="btn btn-danger btn-xs mb-3" title=""><i class="fa fa-trash"></i> Batal Bayar</a>
                          <a target="blank" href="page/transaksi/cetak.php?id_tagihan=<?php echo $data['id_tagihan']; ?>" class="btn btn-info btn-xs" title=""><i class="fa fa-money"></i> Cetak Invoice</a>
                        </td>
                      <?php } ?>
                      <td>
                        <?php if ($blokir != null) { ?>

                          <a href="" type="button" class="openBlokirBtn btn btn-success btn-xs" data-idtagihan="<?php echo $id ?>" data-ip_pelanggan="<?= $ip; ?>" data-nohp="<?php echo $no_hp; ?>" data-nama="<?php echo $data['nama_pelanggan']; ?>" data-kode="<?php echo $data['kode_pelanggan'] ?>" data-tagihan="<?php echo $tagihan; ?>" data-waktu="<?php echo $waktu_indonesia ?>"><i class="fa fa-money"></i> Buka Blokir</a>
                        <?php } else { ?>
                          <a href="" type="button" class="blokirBtn btn btn-danger btn-xs" data-idtagihan="<?php echo $id ?>" data-ip_pelanggan="<?= $ip; ?>" data-nohp="<?php echo $no_hp; ?>" data-nama="<?php echo $data['nama_pelanggan']; ?>" data-kode="<?php echo $data['kode_pelanggan'] ?>" data-tagihan="<?php echo $tagihan; ?>" data-waktu="<?php echo $waktu_indonesia ?>"><i class="fa fa-power-off"></i> Blokir</a>
                        <?php } ?>
                      </td>
                      <td>
                        <a onclick="return confirm('Apakah anda ingin menghapus tagihan Ini')" href="?page=transaksi&aksi=hapuss&id=<?= $id ?>" type="button" class="btn btn-danger btn-xs">
                          <i class="fa fa-trash"></i> Hapus Tagihan
                        </a>
                      </td>
                    </tr>
                <?php }
                } ?>
              </tbody>
            </table>
          </div>
          <?php if ($jumlah_data > 0) { ?>
            <a target="blank" href="page/transaksi/rekap_transaksi.php?bulan=<?php echo $bulan ?>&tahun=<?php echo $tahun ?>" class="btn btn-info" style="margin-top: 10px;" title=""><i class="fa fa-print"></i> Rekap Semua</a>
            <a target="blank" href="page/transaksi/rekap_transaksi_lunas.php?bulan=<?php echo $bulan ?>&tahun=<?php echo $tahun ?>" class="btn btn-success" style="margin-top: 10px;" title=""><i class="fa fa-print"></i> Rekap Sudah Bayar</a>
            <a target="blank" href="page/transaksi/rekap_transaksi_belum_lunas.php?bulan=<?php echo $bulan ?>&tahun=<?php echo $tahun ?>" class="btn btn-danger" style="margin-top: 10px;" title=""><i class="fa fa-print"></i> Rekap Belum Bayar</a>
            ||
            <a target="blank" href="page/transaksi/cetak_semua.php?bulan=<?php echo $bulan ?>&tahun=<?php echo $tahun ?>" class="btn btn-info" style="margin-top: 10px;" title=""><i class="fa fa-print"></i> Cetak Semua Invoice</a>
            <a target="blank" href="page/transaksi/cetak_semua_bayar.php?bulan=<?php echo $bulan ?>&tahun=<?php echo $tahun ?>&status_bayar=<?php echo $status ?>" class="btn btn-success" style="margin-top: 10px;" title=""><i class="fa fa-print"></i> Cetak Invoice Terbayar</a>
            <a target="blank" href="page/transaksi/cetak_semua_belum_bayar.php?bulan=<?php echo $bulan ?>&tahun=<?php echo $tahun ?>" class="btn btn-danger" style="margin-top: 10px;" title=""><i class="fa fa-print"></i> Cetak Invoice Belum Bayar</a>
          <?php }  ?>
        </div>
      </div>
    </div>
  </div>
  <?php $qq = $koneksi->query("SELECT 
    tb_tagihan.*, 
    tb_pelanggan.nama_pelanggan, 
    tb_pelanggan.alamat, 
    tb_pelanggan.ip_address, 
    tb_pelanggan.jatuh_tempo, 
    tb_paket.nama_paket, 
    tb_pelanggan.no_telp, 
    tbl_buktibayar.id_buktibayar, 
    tbl_buktibayar.id_rekening, 
    COALESCE(tbl_buktibayar.id_tagihan, tb_tagihan.id_tagihan) AS id_tagihan, 
    tbl_buktibayar.gambar, 
    tbl_buktibayar.keterangan, 
    tbl_buktibayar.tanggal_terima,
    tbl_rekening.id_rekening, 
    tbl_rekening.nama_bank, 
    tbl_rekening.nomor_rekening, 
    tbl_rekening.nama_rekening
FROM tb_tagihan
INNER JOIN tb_pelanggan ON tb_tagihan.id_pelanggan = tb_pelanggan.id_pelanggan
INNER JOIN tb_paket ON tb_pelanggan.paket = tb_paket.id_paket
LEFT JOIN tbl_buktibayar ON tb_tagihan.id_tagihan = tbl_buktibayar.id_tagihan
LEFT JOIN tbl_rekening ON tbl_buktibayar.id_rekening = tbl_rekening.id_rekening
WHERE tb_tagihan.bulan_tahun = '$bulantahun'
ORDER BY tb_tagihan.status_bayar ASC");

  while ($data = $qq->fetch_assoc()) {
  ?>
    <div class="modal fade" id="exampleModal<?= $data['id_buktibayar'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title" id="exampleModalLabel">Bukti Transfer</h5>
          </div>
          <div class="modal-body">
            <p>
              Nama Pelanggan : <b><?= $data['nama_pelanggan']; ?></b><br>
              Transfer Via : <b><?= $data['nama_bank'] ?></b><br>
              Tanggal Bukti : <b><?= $data['tanggal_terima'] ?></b><br>
              Pesan : <b><?= $data['keterangan'] ?></b><br>
              Bukti Transfer :
            </p>
            <img src="images/bukti_tf/<?= $data['gambar']; ?>" alt="Gambar Bukti Transfer" style="max-width: 100%; max-height: 100%;">
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

<?php } else {
  echo "Anda Tidak Berhak Mengakses Halaman Ini";
} ?>