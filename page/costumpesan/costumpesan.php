<?php if ($_SESSION['admin']) { ?>

    <?php
    $ambil_notif = $koneksi->query("SELECT * FROM tbl_notif");
    $get_notif = $ambil_notif->fetch_assoc();

    $ambil_blokir = $koneksi->query("SELECT * FROM tbl_blokir");
    $get_blokir = $ambil_blokir->fetch_assoc();

    $ambil_notifbayar = $koneksi->query("SELECT * FROM tbl_notifbayar");
    $get_notifBayar = $ambil_notifbayar->fetch_assoc();

    $ambil_bukaBlokir = $koneksi->query("SELECT * FROM tbl_bukablokir");
    $get_bukaBayar = $ambil_bukaBlokir->fetch_assoc();

    $ambil_pemasangan = $koneksi->query("SELECT * FROM tbl_npemasangan");
    $get_pemasangan = $ambil_pemasangan->fetch_assoc();

    ?>

    <div class="row">
        <div class="col-md-6">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Notifikasi Tagihan Otomatis</h3>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Status Notifikasi</label>
                            <select class="form-control" name="status" id="exampleFormControlSelect1">
                                <option value="aktif" <?php if ($get_notif['status_notifikasi'] === 'aktif') echo 'selected'; ?>>Aktif</option>
                                <option value="tidakaktif" <?php if ($get_notif['status_notifikasi'] === 'tidakaktif') echo 'selected'; ?>>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notifTextarea">Isi Pesan Notifikasi Otomatis</label>
                            <textarea class="form-control" id="notifTextarea" name="pesan_notifikasi" rows="9" placeholder="Contoh: Mohon Maaf Kepada Pelanggan Sudah Jatuh Tempo"><?= $get_notif['pesan_notifikasi'] ?></textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" name="simpan_notifikasi" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                            Format Tulisan
                        </button>
                    </div>
                </form>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Notifikasi Pembayaran</h3>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="id_notifbayar" value="<?= $get_notifBayar['id_notifbayar'] ?>">
                        <div class="form-group">
                            <label for="notifTextarea1">Pesan Pembayaran</label>
                            <textarea class="form-control" id="notifTextarea1" name="pesan_bayar" rows="9" placeholder="Contoh: Terima Kasih Kepada Pelanggan Telah Melakukan Pembayaran"><?= $get_notifBayar['pesan_bayar'] ?></textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" name="simpan_bayar" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal2">
                            Format Tulisan
                        </button>
                    </div>
                </form>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Notifikasi Awal Pemasangan</h3>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Status Notifikasi</label>
                            <select class="form-control" name="status_npemasangan" id="exampleFormControlSelect1">
                                <option value="aktif" <?php if ($get_pemasangan['status_notif'] === 'aktif') echo 'selected'; ?>>Aktif</option>
                                <option value="tidak" <?php if ($get_pemasangan['status_notif'] === 'tidak') echo 'selected'; ?>>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notifTextarea">Isi Pesan Notifikasi Pemasangan</label>
                            <textarea class="form-control" id="notifTextarea" name="pesan_npemasangan" rows="9" placeholder="Contoh: Mohon Maaf Kepada Pelanggan Sudah Jatuh Tempo"><?= $get_pemasangan['pesan_notif'] ?></textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" name="simpan_npemasangan" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#notifPasang">
                            Format Tulisan
                        </button>
                    </div>
                </form>
            </div>


        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel">Contoh Format Tulisan</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <!-- <label for="exampleFormControlTextarea"></label> -->
                            <textarea class="form-control" id="exampleFormControlTextarea" name="pesan_notifikasi" rows="9" disabled>
$nama : Untuk Mengambil Nama Pelanggan
$jatuh_tempo : Untuk Mengambil Tanggal Jatuh Tempo
$tagihan : Untuk Mengambil Harga 
$no_telp : Untuk Mengambil Nomor Telepon Pelanggan

Contoh Penggunaan 

Notifikasi Wifi 
Kepada Pelanggan Yth, Saat Ini Wifi Atas Nama $nama Telah memasuki Jatuh Tempo pada Tanggal $jatuh_tempo Harap Lunasi Tagihan Sebesar $tagihan. Jika Belum melakukan Pembayaran Akan otomatis terisolir dalam 1x24 Jam. Terima Kasih. Pembayaran Dapat Akses di https://paynow.biz.id Masukan Username dan Password Berdasarkan nomor yang menerima Wa ini
                            </textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="notifPasang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel">Contoh Format Tulisan Notif Pasang</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <!-- <label for="exampleFormControlTextarea"></label> -->
                            <textarea class="form-control" id="exampleFormControlTextarea" name="pesan_notifikasi" rows="9" disabled>
$nama : Untuk Mengambil Nama Pelanggan
$alamat : Untuk Mengambil Alamat Pelanggan
$no_telp : Untuk Mengambil Nomor Telepon Pelanggan
$paket : Untuk Mengambil Paket Pilihan Pelanggan
$tgl_pemasangan : Untuk Mengambil Tanggal Pemasangan Pelanggan
                            </textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel">Contoh Format Tulisan</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <!-- <label for="exampleFormControlTextarea"></label> -->
                            <textarea class="form-control" id="exampleFormControlTextarea" name="pesan_notifikasi" rows="9" disabled>
$nama : Untuk Mengambil Nama Pelanggan
$jatuh_tempo : Untuk Mengambil Tanggal Jatuh Tempo
$tagihan : Untuk Mengambil Harga 
$no_telp : Untuk Mengambil Nomor Telepon Pelanggan
$harinin : Untuk Mengambil Waktu dan Tanggal Hari Ini

Contoh Penggunaan 

Notifikasi Wifi 
Terima Kasih Kepada $nama Telah Melakukan Pembayaran Sebesar $tagihan Pada $harinin. Invoice Pembayaran Dapat Diambil di https://paynow.biz.id username $no_hp password $no_hp.

Mohon untuk tidak membalas Pesan ini
                            </textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Blokir Otomatis</h3>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="box-body">

                        <div class="form-group">
                            <label for="inputState">Status Blokir</label>
                            <select id="inputState" name="status_blokir" class="form-control">
                                <option value="aktif" <?php if ($get_blokir['status_blokir'] === 'aktif') echo 'selected'; ?>>Aktif</option>
                                <option value="tidakaktif" <?php if ($get_blokir['status_blokir'] === 'tidakaktif') echo 'selected'; ?>>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="row">

                            <div class="form-group col-md-8">
                                <label for="inputCity">Waktu Blokir</label>
                                <input type="number" name="berapa" value="<?= $get_blokir['set_waktu'] ?>" class="form-control" id="inputCity">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputState"> <br></label>
                                <select id="inputState" class="form-control" name="kapan">
                                    <option value="minutes" <?php if ($get_blokir['set_waktu2'] == 'minutes') echo 'selected'; ?>>Menit</option>
                                    <option value="hour" <?php if ($get_blokir['set_waktu2'] == 'hour') echo 'selected'; ?>>Jam</option>
                                    <option value="day" <?php if ($get_blokir['set_waktu2'] == 'day') echo 'selected'; ?>>Hari</option>
                                </select>
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Isi Pesan Blokir Otomatis</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="pesan_blokir" rows="9" placeholder="Contoh: Mohon Maaf Kepada Pelanggan Saat Ini Wifi sudah di isolir"><?= $get_blokir['pesan_blokir'] ?></textarea>
                        </div>

                    </div>
                    <div class="box-footer">
                        <button type="submit" name="simpan_blokir" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal1">
                            Format Tulisan
                        </button>
                    </div>
                </form>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Notifikasi Buka Blokir</h3>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="id_bukablokir" value="<?= $get_bukaBayar['id_bukablokir'] ?>">
                        <div class="form-group">
                            <label for="notifTextarea1">Pesan Buka Blokir</label>
                            <textarea class="form-control" name="pesan_bukablokir" rows="9" placeholder="Contoh: Wifi Sudah Kembali Di Aktifkan"><?= $get_bukaBayar['pesan_bukablokir'] ?></textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" name="simpan_bukablokir" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal2">
                            Format Tulisan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="exampleModalLabel">Contoh Format Tulisan</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <!-- <label for="exampleFormControlTextarea"></label> -->
                        <textarea class="form-control" id="exampleFormControlTextarea" name="pesan_notifikasi" rows="9" disabled>
$nama : Untuk Mengambil Nama Pelanggan
$jatuh_tempo : Untuk Mengambil Tanggal Jatuh Tempo
$tagihan : Untuk Mengambil Harga 
$no_telp : Untuk Mengambil Nomor Telepon Pelanggan
$sekarang_format : Untuk Mendapatkan Waktu Sekarang

Contoh Penggunaan 

Notifikasi Wifi 
Mohon Maaf Kepada Pelanggan Atas nama $nama, Saat Ini Wifi telah di isolir secara otomatis pada tanggal $tanggal_format. Harap Lunasi pembayaran untuk bisa menikmati Akses internet kembali sejumlah $tagihan. Untuk Pembayaran Dapat di bayar di https://paynow.biz.id
                            </textarea>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <?php if (isset($_POST['simpan_npemasangan'])) {
        $status_pemasangan = $_POST['status_npemasangan'];
        $pesan_npemasangan = $_POST['pesan_npemasangan'];

        if (!empty($pesan_npemasangan)) {
            $cekStatus2 = $koneksi->query("SELECT * FROM tbl_npemasangan");
            $row2 = $cekStatus2->fetch_assoc();

            if ($row2) {
                $sql_notifikasi2 = $koneksi->query("UPDATE tbl_npemasangan SET status_notif='$status_pemasangan', pesan_notif='$pesan_npemasangan'");
                $message = 'Berhasil Diperbarui!';
            } else {
                $sql_notifikasi2 = $koneksi->query("INSERT INTO tbl_npemasangan (status_notif, pesan_notif) VALUES ('$status_pemasangan', '$pesan_npemasangan')");
                $message = 'Berhasil Di Tambahkan!';
            }

            if ($sql_notifikasi2) {
    ?>
                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Notifikasi Pemasangan Awal',
                            text: '<?php echo $message; ?>',
                            type: 'success'
                        }, function() {
                            window.location = '?page=costumpesan';
                        });
                    }, 300);
                </script>
    <?php
            }
        }
    }
    ?>


    <?php if (isset($_POST['simpan_notifikasi'])) {
        $status = $_POST['status'];
        $pesanNotifikasi = $_POST['pesan_notifikasi'];

        if (!empty($status)) {
            $cekStatus = $koneksi->query("SELECT * FROM tbl_notif");
            $row = $cekStatus->fetch_assoc();

            if ($row) {
                $sql_notifikasi = $koneksi->query("UPDATE tbl_notif SET status_notifikasi='$status', pesan_notifikasi='$pesanNotifikasi'");
                $message = 'Berhasil Diperbarui!';
            } else {
                $sql_notifikasi = $koneksi->query("INSERT INTO tbl_notif (status_notifikasi, pesan_notifikasi) VALUES ('$status', '$pesanNotifikasi')");
                $message = 'Berhasil Di Tambahkan!';
            }

            if ($sql_notifikasi) {
    ?>
                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Notifikasi Otomatis',
                            text: '<?php echo $message; ?>',
                            type: 'success'
                        }, function() {
                            window.location = '?page=costumpesan';
                        });
                    }, 300);
                </script>
    <?php
            }
        }
    }
    ?>

    <?php if (isset($_POST['simpan_blokir'])) {
        $status_blokir = $_POST['status_blokir'];
        $berapa = $_POST['berapa'];
        $kapan = $_POST['kapan'];
        $pesanBlokir = $_POST['pesan_blokir'] ?? null;


        if (!empty($status_blokir)) {
            $cekBlokir = $koneksi->query("SELECT * FROM tbl_blokir");
            $blokir = $cekBlokir->fetch_assoc();

            if ($blokir) {
                $sql_blokir = $koneksi->query("UPDATE tbl_blokir SET status_blokir='$status_blokir', set_waktu='$berapa', set_waktu2='$kapan', pesan_blokir='$pesanBlokir'");
                $message = 'Berhasil Diperbarui!';
            } else {
                $sql_blokir = $koneksi->query("INSERT INTO tbl_blokir (status_blokir, set_waktu, set_waktu2, pesan_blokir) VALUES ('$status_blokir', '$berapa', '$kapan', '" . ($pesanBlokir ?? null) . "')");
                $message = 'Berhasil Di Tambahkan!';
            }

            if ($sql_blokir) {
    ?>
                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Blokir Otomatis',
                            text: '<?php echo $message; ?>',
                            type: 'success'
                        }, function() {
                            window.location = '?page=costumpesan';
                        });
                    }, 300);
                </script>
    <?php
            }
        }
    } ?>

    <?php if (isset($_POST['simpan_bayar'])) {
        $id_notifbayar = $_POST['id_notifbayar'];
        $pesanBayar = $_POST['pesan_bayar'];

        if (!empty($id_notifbayar . $pesanBayar)) {
            $cekPesan = $koneksi->query("SELECT * FROM tbl_notifbayar");
            $testPesan = $cekPesan->fetch_assoc();

            if ($testPesan) {
                $queryPesan = $koneksi->query("UPDATE tbl_notifbayar SET pesan_bayar='$pesanBayar' WHERE id_notifbayar='$id_notifbayar'");
                $message = 'Berhasil Diperbarui!';
            } else {
                $queryPesan = $koneksi->query("INSERT INTO tbl_notifbayar (pesan_bayar) VALUES ('$pesanBayar')");
                $message = 'Berhasil Di Tambahkan!';
            }

            if ($queryPesan) {
    ?>
                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Pesan Bayar',
                            text: '<?php echo $message; ?>',
                            type: 'success'
                        }, function() {
                            window.location = '?page=costumpesan';
                        });
                    }, 300);
                </script>
    <?php
            }
        }
    } ?>

    <?php if (isset($_POST['simpan_bukablokir'])) {
        $id_bukablokir = $_POST['id_bukablokir'];
        $bukaBlokir = $_POST['pesan_bukablokir'];

        if (!empty($id_bukablokir . $bukaBlokir)) {
            $cekBukaBlokir = $koneksi->query("SELECT * FROM tbl_bukablokir");
            $testBuka = $cekBukaBlokir->fetch_assoc();

            if ($testBuka) {
                $queryBlokirPesan = $koneksi->query("UPDATE tbl_bukablokir SET pesan_bukablokir='$bukaBlokir' WHERE id_bukablokir='$id_bukablokir'");
                $message = 'Berhasil Diperbarui!';
            } else {
                $queryBlokirPesan = $koneksi->query("INSERT INTO tbl_bukablokir (pesan_bukablokir) VALUES ('$bukaBlokir')");
                $message = 'Berhasil Di Tambahkan!';
            }

            if ($queryBlokirPesan) {
    ?>
                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Pesan Buka Blokir',
                            text: '<?php echo $message; ?>',
                            type: 'success'
                        }, function() {
                            window.location = '?page=costumpesan';
                        });
                    }, 300);
                </script>
    <?php
            }
        }
    } ?>

<?php } ?>