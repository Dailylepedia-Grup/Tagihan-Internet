<?php if ($_SESSION['admin']) { ?>
  <div class="row">
    <div class="col-md-12">
      <!-- Advanced Tables -->
      <div class="box box-primary box-solid">
        <div class="box-header with-border">
          Data Kas Masuk dan Keluar
        </div>
        <div class="panel-body">
          <button type="button" class="btn btn-info" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal-default">
            Tambah
          </button>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="example1">

              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Keterangan</th>
                  <th>Kas Masuk</th>
                  <th>Kas Keluar</th>
                  <th>Ubah</th>
                  <th>Hapus</th>
                </tr>
              </thead>
              <tbody>

                <?php

                $no = 1;

                $sql = $koneksi->query("select * from tb_kas order by id_kas desc");

                while ($data = $sql->fetch_assoc()) {

                  $status = $data['status'];

                  $t_masuk = $data['penerimaan'];
                  $t_Keluar = $data['pengeluaran'];

                  $total_masuk = $total_masuk + $t_masuk;
                  $total_keluar = $total_keluar + $t_Keluar;
                  $saldo = $total_masuk - $total_keluar;


                ?>


                  <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo tglIndonesia2(date('d F Y', strtotime($data['tgl_kas']))) ?></td>
                    <td><?php echo $data['keterangan'] ?></td>
                    <td align="right"><?php echo number_format($data['penerimaan'], 0, ",", ".") ?></td>
                    <td align="right"><?php echo number_format($data['pengeluaran'], 0, ",", ".") ?></td>

                    <?php if ($status == "1") { ?>
                      <td>

                        <a href="#" type="button" class="btn btn-info" data-toggle="modal" data-target="#mymodal<?php echo $data['id_kas']; ?>"><i class="fa fa-edit"></i> Ubah</a>

                      </td>

                      <td>
                        <form method="POST">

                          <input type="hidden" name="id_kas" value="<?php echo $data['id_kas']; ?>">

                          <button type="submit" name="hapus" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>

                        </form>
                      </td>

                    <?php } else { ?>

                      <td>

                        <a href="#" disabled="" class="btn btn-info"><i class="fa fa-edit"></i> Ubah</a>

                      </td>

                      <td>
                        <a href="#" disabled="" class="btn btn-danger"><i class="fa fa-danger"></i> Hapus</a>
                      </td>

                    <?php } ?>

                  </tr>

                  <div class="modal fade" id="mymodal<?php echo $data['id_kas']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="box box-primary box-solid">
                          <div class="box-header with-border">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span></button>
                            Ubah Data Kas
                          </div>
                          <div class="modal-body">

                            <form role="form" method="POST">
                              <?php

                              $id_kas = $data['id_kas'];

                              $sql1 = $koneksi->query("select * from tb_kas where id_kas='$id_kas'");

                              while ($data1 = $sql1->fetch_assoc()) {

                              ?>

                                <input type="hidden" name="id_kas" value="<?php echo $data1['id_kas']; ?>">
                                <div class="form-group">
                                  <label>Tanggal</label>
                                  <input required="" type="date" name="tgl_kas" class="form-control" value="<?php echo $data1['tgl_kas']; ?>">
                                </div>

                                <div class="form-group">
                                  <label>Keterangan</label>
                                  <input required="" type="text" name="keterangan" class="form-control" value="<?php echo $data1['keterangan']; ?>">
                                </div>

                                <div class="form-group">
                                  <label>Pamasukan</label>
                                  <input required="" type="text" autocomplete="off" name="penerimaan" class="form-control uang" value="<?php echo $data1['penerimaan']; ?>">
                                </div>

                                <div class="form-group">
                                  <label>Pengeluaran</label>
                                  <input required="" type="text" autocomplete="off" name="pengeluaran" class="form-control uang" value="<?php echo $data1['pengeluaran']; ?>">
                                </div>

                          </div>
                          <div class="modal-footer">
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>

                          </div>

                        <?php } ?>

                        </form>

                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>


                  <?php } ?>

                  <?php

                  if (isset($_POST['simpan'])) {
                    $id_kas_ubah = $_POST['id_kas'];
                    $tgl_kas = $_POST['tgl_kas'];
                    $keterangan = htmlspecialchars(strip_tags($_POST['keterangan']));
                    $penerimaan = htmlspecialchars(strip_tags($_POST['penerimaan']));
                    $pengeluaran = htmlspecialchars(strip_tags($_POST['pengeluaran']));

                    $penerimaan_oke = str_replace(".", "", $penerimaan);
                    $pengeluaran_oke = str_replace(".", "", $pengeluaran);



                    $sql = $koneksi->query("update  tb_kas set keterangan='$keterangan', tgl_kas='$tgl_kas', pengeluaran='$pengeluaran_oke', penerimaan='$penerimaan_oke' where id_kas='$id_kas_ubah'");



                    if ($sql) {
                      echo "

                        <script>
                            setTimeout(function() {
                                swal({
                                    title: 'Data Kas',
                                    text: 'Berhasil Diubah!',
                                    type: 'success'
                                }, function() {
                                    window.location = '?page=kas';
                                });
                            }, 300);
                        </script>

                    ";
                    }
                  }

                  ?>

              </tbody>

              <tr>
                <td colspan="3" style="text-align: center; font-weight: bold; font-size: 16px">Total</td>
                <td align="right"><?php echo ($total_masuk !== null) ? number_format($total_masuk, 0, ",", ".") : '0'; ?></td>
                <td align="right"><?php echo ($total_keluar !== null) ? number_format($total_keluar, 0, ",", ".") : '0'; ?></td>
                <td colspan="2" align="center"></td>
              </tr>

              <tr>
                <td colspan="3" style="text-align: center; font-weight: bold; font-size: 16px">Saldo</td>
                <td colspan="2" align="center"><?php echo ($saldo !== null) ? number_format($saldo, 0, ",", ".") : '0'; ?></td>
                <td colspan="2" align="center"></td>
              </tr>


            </table>

          </div>
        </div>
      </div>


      <!-- AWAL TAMBAH DATA TAHUN AJARAN -->

      <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="box box-primary box-solid">
              <div class="box-header with-border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                Tambah Kas
              </div>

              <?php $tgl = date('Y-m-d'); ?>

              <div class="modal-body">
                <form role="form" method="POST">
                  <div class="form-group">
                    <label>Tanggal</label>
                    <input required="" type="date" name="tgl_kas" class="form-control" value="<?php echo $tgl ?>">
                  </div>

                  <div class="form-group">
                    <label>Keterangan</label>
                    <input required="" type="text" name="keterangan" class="form-control">
                  </div>



                  <div class="form-group">
                    <label>Pamasukan</label>
                    <input required="" type="text" autocomplete="off" name="penerimaan" value="0" class="form-control uang">
                  </div>

                  <div class="form-group">
                    <label>Pengeluaran</label>
                    <input required="" type="text" autocomplete="off" name="pengeluaran" value="0" class="form-control uang">
                  </div>


                  <div class="modal-footer">
                    <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>

                  </div>



                </form>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>



          <?php

          if (isset($_POST['tambah'])) {

            $tahun_ajaran = $_POST['tahun_ajaran'];
            $tgl_kas = $_POST['tgl_kas'];
            $keterangan = htmlspecialchars(strip_tags($_POST['keterangan']));
            $penerimaan2 = htmlspecialchars(strip_tags($_POST['penerimaan']));
            $pengeluaran2 = htmlspecialchars(strip_tags($_POST['pengeluaran']));

            $penerimaan_oke2 = str_replace(".", "", $penerimaan2);
            $pengeluaran_oke2 = str_replace(".", "", $pengeluaran2);

            $sql = $koneksi->query("insert into tb_kas (tgl_kas, keterangan, penerimaan, pengeluaran, status)values('$tgl_kas', '$keterangan', '$penerimaan_oke2', '$pengeluaran_oke2', 1) ");

            if ($sql) {
              echo "

                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Data Kas',
                            text: 'Berhasil Disimpan!',
                            type: 'success'
                        }, function() {
                            window.location = '?page=kas';
                        });
                    }, 300);
                </script>

            ";
            }
          }

          ?>


          <!-- AKHIR TAMBAH DATA TAHUN AJARAN -->


          <?php


          if (isset($_POST['hapus'])) {
            $id_kas = $_POST['id_kas'];

            $sql = $koneksi->query("delete from tb_kas where id_kas='$id_kas'");

            if ($sql) {
              echo "

                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Data Kas',
                            text: 'Berhasil Dihapus!',
                            type: 'success'
                        }, function() {
                            window.location = '?page=kas';
                        });
                    }, 300);
                </script>

            ";
            }
          }

          ?>


        <?php } else {
        echo "Anda Tidak Berhak Mengakses Halaman Ini";
      } ?>