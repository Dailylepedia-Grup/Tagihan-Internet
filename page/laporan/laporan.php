<?php if ($_SESSION['admin']) { ?>

  <div class="row">
    <div class="col-md-12">
      <!-- Form Elements -->
      <div class="box box-primary box-solid">
        <div class="box-header with-border">
          Laporan Kas Masuk dan Keluar
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">
              <!-- /.box-header -->
              <!-- form start -->
              <form role="form" method="POST" target="blank" action="page/laporan/rekap_kas.php">
                <div class="form-group">
                  <label>Tanggal Awal</label>
                  <input type="date" name="tgl_awal" required="" class="form-control">
                </div>

                <div class="form-group">
                  <label>Tanggal Akhir</label>
                  <input type="date" name="tgl_akhir" required="" class="form-control">
                </div>

                <div class="modal-footer">
                  <button type="submit" name="tambah" class="btn btn-primary"><i class="fa fa-print"></i> Cetak</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <!-- Advanced Tables -->
      <div class="box box-primary box-solid">
        <div class="box-header with-border">
          Riwayat Pendapatan Bulanan
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="example1">

              <thead>
                <tr>
                  <th class="text-center">No</th>
                  <th class="text-center">Bulan/Tahun</th>
                  <th class="text-center">Jumlah Transaksi</th>
                  <th class="text-center">Masukan</th>
                  <th class="text-center">Pengeluaran</th>
                  <th class="text-center">Pendapatan Bersih</th>
                </tr>
              </thead>

              <tbody>
                <?php
                $i = 1;
                //direct admin
                $queryBulan = $koneksi->query("SELECT DISTINCT DATE_FORMAT(tgl_kas, '%Y-%m') AS bulan_tahun FROM tb_kas ORDER BY bulan_tahun DESC");

                // cpanel
                // $queryBulan = $koneksi->query("SELECT DISTINCT DATE_FORMAT(tgl_kas, '%Y-%m') AS bulan_tahun FROM tb_kas ORDER BY tgl_kas DESC");

                while ($row = $queryBulan->fetch_assoc()) {
                  $bulan_tahun = $row['bulan_tahun'];
                  $queryTransaksi = $koneksi->query("SELECT COUNT(*) AS jumlah_transaksi, SUM(penerimaan) AS total_penerimaan, SUM(pengeluaran) AS total_pengeluaran FROM tb_kas WHERE DATE_FORMAT(tgl_kas, '%Y-%m') = '$bulan_tahun'");
                  $data = $queryTransaksi->fetch_assoc();

                  // Hitung total pendapatan bersih
                  $total_pendapatan_bersih = $data['total_penerimaan'] - $data['total_pengeluaran'];
                ?>
                  <tr>
                    <td class="text-center"><?= $i++; ?></td>
                    <td class="text-center"><?= $bulan_tahun ?></td>
                    <td class="text-center"><?= $data['jumlah_transaksi'] ?></td>
                    <td class="text-center"><?= 'Rp ' . number_format($data['total_penerimaan'], 0, ',', '.') ?></td>
                    <td class="text-center"><?= 'Rp ' . number_format($data['total_pengeluaran'], 0, ',', '.') ?></td>
                    <td class="text-center"><?= 'Rp ' . number_format($total_pendapatan_bersih, 0, ',', '.') ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
          <!-- <a href="/page/laporan/cetak_riwayat.php" target="_blank" class="btn btn-info" style="margin-top: 10px;" title=""><i class="fa fa-print"></i> Cetak Pendapatan</a> -->
        </div>
      </div>
    </div>

  </div>


<?php } else {
  echo "Anda Tidak Berhak Mengakses Halaman Ini";
} ?>