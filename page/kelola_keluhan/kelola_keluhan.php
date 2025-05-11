<?php
session_start(); // Pastikan memulai sesi

if (isset($_SESSION['admin']) || $_SESSION['teknisi'] == true) {
?>
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    Daftar Keluhan
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="example1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Tiket</th>
                                    <th>Dari</th>
                                    <th>Keluhan</th>
                                    <th>Bukti Gambar</th>
                                    <th>Isi Keluhan</th>
                                    <th>No Wa</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $sql = $koneksi->query("SELECT * FROM tb_pelanggan 
                                INNER JOIN tbl_keluhan ON tb_pelanggan.id_pelanggan = tbl_keluhan.id_pelanggan
                                LEFT JOIN tb_user ON tb_user.id = tbl_keluhan.user_id
                                ORDER BY tbl_keluhan.id_keluhan DESC");

                                while ($data = $sql->fetch_assoc()) {

                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td class="text-center"><?= $data['nomor_tiket'] ?></td>
                                        <td class="text-center"><?= $data['nama_pelanggan'] ?></td>
                                        <td class="text-center"><?= $data['judul_keluhan'] ?></td>
                                        <td class="text-center"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal2<?= $data['id_keluhan'] ?>"><i class="fa fa-eye" aria-hidden="true"></i></button></td>
                                        <td class="text-center"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal<?= $data['id_keluhan'] ?>"><i class="fa fa-eye" aria-hidden="true"></i></button></td>
                                        <td class="text-center"><?= $data['no_wa'] ?></td>
                                        <td class="text-center"><?= $data['tanggal'] ?></td>
                                        <td class="text-center">
                                            <?php if ($data['status_keluhan'] == 'selesai') { ?>
                                                Ditangani Oleh : <?= $data['nama_user'] ?> Sebagai <?= $data['level'] ?>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1<?= $data['id_keluhan'] ?>">
                                                    Komentar
                                                </button>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center"><?= $data['status_keluhan'] ?></td>
                                        <td class="text-center">
                                            <?php if ($data['status_keluhan'] == 'menunggu') { ?>
                                                <a href="?page=kelola_keluhan&aksi=proses&id_pelanggan=<?php echo $data['id_pelanggan']; ?>&id_keluhan=<?= $data['id_keluhan'] ?>&no_telp=<?= $data['no_telp'] ?>&nama_user=<?= $data['nama_pelanggan'] ?>&nomor_tiket=<?= $data['nomor_tiket'] ?>" class="btn btn-warning" title="">Proses</a>
                                            <?php } else if ($data['status_keluhan'] == 'proses' && $data['masalah'] !== null) { ?>
                                                <a href="?page=kelola_keluhan&aksi=selesai&id_pelanggan=<?php echo $data['id_pelanggan']; ?>&id_keluhan=<?= $data['id_keluhan'] ?> &no_telp=<?= $data['no_telp'] ?>&nama_user=<?= $data['nama_pelanggan'] ?>&nomor_tiket=<?= $data['nomor_tiket'] ?>" class="btn btn-success" title="">Selesai</a>
                                            <?php } else if ($data['status_keluhan'] == 'proses' && $data['masalah'] === null) { ?>
                                                <div class="text-danger">Berikan Komentar Terlebih Dahulu</div>
                                            <?php } else if ($data['status_keluhan'] == 'selesai') { ?>
                                                Masalah Selesai
                                            <?php } ?>
                                        </td>

                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $dataa = $koneksi->query("SELECT * FROM tb_pelanggan INNER JOIN tbl_keluhan ON tb_pelanggan.id_pelanggan=tbl_keluhan.id_pelanggan "); ?>
    <?php foreach ($dataa as $dtf) : ?>
        <div class="modal fade" id="exampleModal2<?= $dtf['id_keluhan'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="" method="post">
                    <div class="modal-content">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="exampleModalLabel">Gambar Keluhan</h5>
                                Dari : <b><?= $dtf['nama_pelanggan'] ?></b><br>

                            </div>
                            <div class="modal-body">
                                <?php if ($dtf['gambar'] != null) : ?>
                                    <img src="images/keluhan/<?= $dtf['gambar']; ?>" alt="Gambar Keluhan" style="max-width: 100%; max-height: 100%;">
                                <?php else : ?>
                                    Tidak Ada Gambar Terlampir
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach ?>


    <?php $dataa = $koneksi->query("SELECT * FROM tb_pelanggan INNER JOIN tbl_keluhan ON tb_pelanggan.id_pelanggan=tbl_keluhan.id_pelanggan "); ?>
    <?php foreach ($dataa as $dtt) : ?>
        <div class="modal fade" id="exampleModal1<?= $dtt['id_keluhan'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title" id="exampleModalLabel">Keterangan</h5>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_keluhan" value="<?= $dtt['id_keluhan'] ?>">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Komentar Keluhan</label>
                                <textarea class="form-control" name="keterangan" id="exampleFormControlTextarea1" rows="3" placeholder="Kosongkan Jika tidak mau memberi komentar keluhan"><?= $dtt['masalah'] ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="komentar" class="btn btn-primary">Balas</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php
        if (isset($_POST['komentar'])) {
            $id_keluhan = $_POST['id_keluhan'];
            $keterangan = $_POST['keterangan'];
            $idUser = $id_user;
            $query = $koneksi->query("UPDATE tbl_keluhan SET masalah='$keterangan', user_id='$idUser' WHERE id_keluhan='$id_keluhan'");
            if ($query) {
                echo "
                    <script>
                        setTimeout(function() {
                            swal({
                                title: 'Komentar',
                                text: 'Berhasil Dibalas!',
                                type: 'success'
                            }, function() {
                                window.location = '?page=kelola_keluhan';
                            });
                        }, 300);
                    </script>
                ";
            }
        }
        ?>
    <?php endforeach ?>

    <?php $data = $koneksi->query("SELECT * FROM tb_pelanggan INNER JOIN tbl_keluhan ON tb_pelanggan.id_pelanggan=tbl_keluhan.id_pelanggan "); ?>
    <?php foreach ($data as $dt) : ?>
        <div class="modal fade" id="exampleModal<?= $dt['id_keluhan'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLabel">Pesan Keluhan</h5>
                        Dari : <b><?= $dt['nama_pelanggan'] ?></b><br>
                    </div>
                    <div class="modal-body">
                        <?= $dt['isi_keluhan'] ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>