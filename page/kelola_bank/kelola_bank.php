<?php
session_start(); // Pastikan memulai sesi
if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {

    $query = $koneksi->query("SELECT * FROM tbl_pgate");
    $data = $query->fetch_assoc();

    $queryy = $koneksi->query("SELECT * FROM tbl_badmin");
    $dataa = $queryy->fetch_assoc();

?>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class=" box-title">Token Payment Midtrans</h3>
                </div>
                <div class="box-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="hidden" value="<?= $data['id_pgat '] ?>" name="id_pgat">
                            <label for="clientkey">Client Key</label>
                            <input type="text" class="form-control" name="clientkey" id="clientkey" value="<?php echo $data['tclientkey'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="serverkey">Server Key</label>
                            <input type="text" class="form-control" name="serverkey" id="serverkey" value="<?php echo $data['tserverkey'] ?>">
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="simpanpayment" class="btn btn-primary">Simpan</button>
                            <a href="?page=kelola_bank&aksi=hapus&id=<?= $data['id_pgat']; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus Token</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class=" box-title">Biaya Admin</h3>
                </div>
                <div class="box-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="hidden" value="<?= $dataa['id_badmin '] ?>" name="id_badmin">
                            <label for="exampleFormControlSelect1">Tanggung Biaya Admin Oleh</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="tanggung">
                                <option value="pelanggan" <?= ($dataa['status'] == 'pelanggan') ? 'selected' : '' ?>>Pelanggan</option>
                                <option value="saya" <?= ($dataa['status'] == 'saya') ? 'selected' : '' ?>>Ditanggung Saya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="clientkey">Biaya Admin (Hanya Angka 5000)</label>
                            <input type="number" class="form-control" name="harga" value="<?= $dataa['harga'] ?>" id="clientkey">
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="biayaadmin" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php

    if (isset($_POST['biayaadmin'])) {
        $hargaAdmin = $_POST['harga'] ?? NULL;
        $tanggung = $_POST['tanggung'];

        if (!empty($tanggung) || !empty($hargaAdmin)) {
            $cekAdmin = $koneksi->query("SELECT * FROM tbl_badmin");
            $rows = $cekAdmin->fetch_assoc();

            if ($rows) {
                $sqlAdmin = $koneksi->query("UPDATE tbl_badmin SET harga='$hargaAdmin', status='$tanggung'");
                $message = 'Berhasil Diperbarui!';
            } else {
                // Jika data token belum ada, lakukan INSERT
                $sqlAdmin = $koneksi->query("INSERT INTO tbl_badmin (id_badmin, harga, status) VALUES (1, '$hargaAdmin', '$tanggung')");
                $message = 'Berhasil Disimpan!';
            }
        }

        if ($sqlAdmin) {
    ?>
            <script>
                setTimeout(function() {
                    swal({
                        title: 'Data Biaya Admin',
                        text: '<?php echo $message; ?>',
                        type: 'success'
                    }, function() {
                        window.location = '?page=kelola_bank';
                    });
                }, 300);
            </script>
    <?php }
    }
    ?>

    <?php
    if (isset($_POST['simpanpayment'])) {
        $clientkey = $_POST['clientkey'];
        $serverkey = $_POST['serverkey'];

        if (!empty($clientkey) && !empty($serverkey)) {
            $cekToken = $koneksi->query("SELECT * FROM tbl_pgate");
            $row = $cekToken->fetch_assoc();

            if ($row) {
                // Jika data token sudah ada, lakukan UPDATE
                $sqlMidtrans = $koneksi->query("UPDATE tbl_pgate SET tclientkey='$clientkey', tserverkey='$serverkey'");
                $message = 'Berhasil Diperbarui!';
            } else {
                // Jika data token belum ada, lakukan INSERT
                $sqlMidtrans = $koneksi->query("INSERT INTO tbl_pgate (id_pgat, tclientkey, tserverkey) VALUES (1, '$clientkey', '$serverkey')");
                $message = 'Berhasil Disimpan!';
            }
        }
    }
    if ($sqlMidtrans) {
    ?>
        <script>
            setTimeout(function() {
                swal({
                    title: 'Data Payment Gateway',
                    text: '<?php echo $message; ?>',
                    type: 'success'
                }, function() {
                    window.location = '?page=kelola_bank';
                });
            }, 300);
        </script>
    <?php } ?>
<?php } ?>