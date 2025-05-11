<?php
$id = $_GET['id'];
$get = $koneksi->query("SELECT * FROM tbl_odp WHERE id_odp = $id");
$ambil = $get->fetch_assoc();
?>

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">Ubah Data ODP</h3>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="box-body">

                    <div class="form-group">
                        <input type="hidden" name="id_odp" value="<?= $ambil['id_odp'] ?>" class="form-control" required>
                        <label>Nama ODP</label>
                        <input type="text" name="nama_odp" value="<?= $ambil['nama_odp'] ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Port ODP</label>
                        <input type="text" name="port_odp" value="<?= $ambil['port_odp'] ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="mapping" value="<?= $ambil['location'] ?>" class="form-control" id="odp">
                    </div>

                    <div class="form-group">

                        <label>Dari ODC</label> <br>
                        <select class="form-control" name="dari_odc">
                            <?php

                            $queryy = $koneksi->query("SELECT * FROM tbl_odc");

                            while ($tampil_tt = $queryy->fetch_assoc()) {
                                $pilih_t = ($tampil_tt['id_odc'] == $ambil['odc'] ? "selected" : "");
                                echo "<option value='$tampil_tt[id_odc]' $pilih_t>" . $tampil_tt['nama_odc'] . " - " . $tampil_tt['perangkat_odc'] . "</option>";
                            }

                            ?>

                        </select>

                    </div>

                    <div class="form-group">
                        <label>Lokasi ODP</label>
                        <div class="box-body">
                            <div id="titikodp" style="height: 600px;"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="submit" name="ubah" class="btn btn-block btn-primary btn-lg">Simpan</button>

                </div>
            </form>

        </div>

    </div>

</div>

<?php

if (isset($_POST['ubah'])) {
    $id_odp = $_POST['id_odp'];
    $nama_odp = $_POST['nama_odp'];
    $port_odp = $_POST['port_odp'];
    $maps = $_POST['mapping'];
    // $dari_odc = isset($_POST['dari_odc']) ? $_POST['dari_odc'] : "";
    $dari_odc = $_POST['dari_odc'];

    // Periksa jika $dari_odc kosong
    if (empty($dari_odc)) {
        // Insert data ODP tanpa memeriksa batas port ODC
        $sql = $koneksi->query("INSERT INTO tbl_odp (nama_odp, port_odp, location) VALUES ('$nama_odp', '$port_odp', '$maps')");
        if ($sql) {
            echo "
                    <script>
                        setTimeout(function() {
                            swal({
                                title: 'Data ODP',
                                text: 'Berhasil Disimpan!',
                                type: 'success'
                            }, function() {
                                window.location = '?page=odp';
                            });
                        }, 300);
                    </script>
                ";
        } else {
            echo "
                    <script>
                        setTimeout(function() {
                            swal({
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat menyimpan data ODP',
                                type: 'error'
                            }, function() {
                                window.location = '?page=odp';
                            });
                        }, 300);
                    </script>
                ";
        }
    } else {
        // Memeriksa batas port ODC jika $dari_odc tidak kosong
        // Mengambil nilai port maksimum dari tabel odc
        $cek = $koneksi->query("SELECT port_odc FROM tbl_odc WHERE id_odc = $dari_odc");
        $cekk = $cek->fetch_assoc();
        $maxPort = $cekk['port_odc'];

        // Menghitung total odp pada odc tertentu
        $sql1 = $koneksi->query("SELECT COUNT(*) AS total_odp FROM tbl_odp WHERE odc = $dari_odc");
        $count = $sql1->fetch_assoc();
        $total_odp = $count['total_odp'];

        // Periksa apakah port_odp yang dimasukkan oleh pengguna tidak melebihi total_odp
        if ($total_odp < $maxPort) {
            // $sql = $koneksi->query("INSERT INTO tbl_odp (nama_odp, port_odp, location, odc) VALUES ('$nama_odp', '$port_odp', '$maps', '$dari_odc')");
            $sql = $koneksi->query("UPDATE tbl_odp SET nama_odp = '$nama_odp', port_odp = '$port_odp', location = '$maps', odc = '$dari_odc' WHERE id_odp = '$id_odp'");
            if ($sql) {
                echo "
                        <script>
                            setTimeout(function() {
                                swal({
                                    title: 'Data ODP',
                                    text: 'Berhasil Disimpan!',
                                    type: 'success'
                                }, function() {
                                    window.location = '?page=odp';
                                });
                            }, 300);
                        </script>
                    ";
            } else {
                echo "
                        <script>
                            setTimeout(function() {
                                swal({
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan saat menyimpan data ODP',
                                    type: 'error'
                                }, function() {
                                    window.location = '?page=odp';
                                });
                            }, 300);
                        </script>
                    ";
            }
        } else {
            echo "
                    <script>
                        setTimeout(function() {
                            swal({
                                title: 'Gagal',
                                text: 'Melebihi Batas Port ODC',
                                type: 'error'
                            }, function() {
                                window.location = '?page=odp';
                            });
                        }, 300);
                    </script>
                ";
        }
    }
}

?>