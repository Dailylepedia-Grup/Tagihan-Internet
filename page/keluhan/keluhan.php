<?php if ($_SESSION['user']) { ?>
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    Keluhan Saya
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="example1">
                            <button type="button" class="btn btn-success" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal-default">
                                Kirim Laporan
                            </button>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Tiket</th>
                                    <th>Permasalahan</th>
                                    <th>Pesan Keluhan</th>
                                    <th>Gambar</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Balasan Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $sql = $koneksi->query("SELECT * FROM tb_pelanggan 
                                    INNER JOIN tbl_keluhan ON tb_pelanggan.id_pelanggan = tbl_keluhan.id_pelanggan
                                    LEFT JOIN tb_user ON tb_user.id = tbl_keluhan.user_id
                                    WHERE tb_pelanggan.id_pelanggan = $id_pelanggan
                                    ORDER BY tbl_keluhan.id_keluhan DESC");
                                while ($data = $sql->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td class="text-center"><?= $data['nomor_tiket']; ?></td>
                                        <td class="text-center"><?= $data['judul_keluhan']; ?></td>
                                        <td class="text-center"><?= $data['isi_keluhan']; ?></td>
                                        <td class="text-center">
                                            <?php if ($data['gambar'] != null) : ?>
                                                <img src="images/keluhan/<?= $data['gambar']; ?>" alt="Gambar Keluhan" height="50" width="50">
                                            <?php else : ?>
                                                Tidak Melampirkan Gambar
                                            <?php endif ?>
                                        </td>
                                        <td class="text-center"><?= $data['tanggal']; ?></td>
                                        <td class="text-center"><?= $data['status_keluhan']; ?></td>
                                        <td class="text-center">
                                            <?php if ($data['status_keluhan'] == 'menunggu' || $data['status_keluhan'] == 'proses') { ?>
                                                <b>Menunggu Respon</b>
                                            <?php } else if ($data['status_keluhan'] == 'selesai') { ?>
                                                <?php if ($data['masalah'] != null) { ?>
                                                    <!-- <b><?= $data['level'] ?></b> : <?= $data['masalah'] ?> -->
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal<?= $data['id_keluhan'] ?>"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                <?php } else { ?>
                                                    Tidak Ada Komentar
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php $data = $koneksi->query("SELECT * FROM tb_pelanggan 
                                    INNER JOIN tbl_keluhan ON tb_pelanggan.id_pelanggan = tbl_keluhan.id_pelanggan
                                    LEFT JOIN tb_user ON tb_user.id = tbl_keluhan.user_id
                                    WHERE tb_pelanggan.id_pelanggan = $id_pelanggan
                                    ORDER BY tbl_keluhan.id_keluhan DESC"); ?>

            <?php foreach ($data as $dt) : ?>
                <div class="modal fade" id="exampleModal<?= $dt['id_keluhan'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="exampleModalLabel">Balasan Keluhan</h5>
                                Dari : <b><?= $dt['level'] ?></b><br>
                                Nama : <b> <?= $dt['nama_user'] ?></b><br>
                                Nomor Tiket : <b><?= $dt['nomor_tiket'] ?></b>
                            </div>
                            <div class="modal-body">
                                <?= $dt['masalah'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- AWAL TAMBAH DATA SISWA -->

            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="box box-primary box-solid">
                            <div class="box-header with-border">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                Kirim Keluhan
                            </div>

                            <div class="modal-body">
                                <form role="form" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Permasalahan</label>
                                        <input type="text" name="judul_keluhan" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Jelaskan</label>
                                        <textarea class="form-control" rows="3" name="isi_keluhan" required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Gambar</label>
                                        <input type="file" name="gambar" accept="image/*">
                                        <span style="color: red;"><i>Lampirkan Gambar Jika Perlu</i></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Nomor yang Dapat Dihubungi</label>
                                        <input type="number" name="no_wa" class="form-control" required>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="tambah" class="btn btn-block btn-primary btn-lg">Kirim Keluhan</button>
                            </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </div>

        <?php

        $ww = $koneksi->query("SELECT * FROM tbl_keluhan WHERE id_pelanggan='$id_pelanggan'");
        $sa = $ww->fetch_assoc();

        $ff = $koneksi->query("SELECT * FROM tb_pelanggan WHERE id_pelanggan='$id_pelanggan'");
        $ss = $ff->fetch_assoc();

        $nama_user = $ss['nama_pelanggan'];

        $test = $koneksi->query("SELECT * FROM tb_user WHERE level='teknisi'");

        if ($test->num_rows > 0) {
            while ($qq = $test->fetch_assoc()) {
                $phone_teknisi = $qq['phone_number'];
                $nama_teknisi = $qq['nama_user'];
                $nomor_telepon_teknisi[] = $phone_teknisi;
                $nama_pemilikl[] = $nama_teknisi;
            }
            $nomor_telepon_teknisi_string = implode(', ', $nomor_telepon_teknisi);
            $nama_pemilik = implode(',',  $nama_pemilikl);
        } else {
            $testt = $koneksi->query("SELECT * FROM tbl_nomorphone");
            $qq = $testt->fetch_assoc();
            $nomor_telepon_teknisi_string = $qq['my_number'];
            $nama_pemilik = $qq['nama_pemilik'];
        }

        $sql_token = "SELECT * FROM tbl_token WHERE id_token = 1"; // Sesuaikan dengan query yang sesuai
        $result = mysqli_query($koneksi, $sql_token);
        $row = mysqli_fetch_assoc($result);
        $authorizationToken = $row['token'];

        if (isset($_POST['tambah'])) {

            $query = $koneksi->query("SELECT status_keluhan FROM tbl_keluhan WHERE id_pelanggan = '$id_pelanggan' ORDER BY tanggal DESC LIMIT 1");
            $result = $query->fetch_assoc();

            if ($result) {
                $status_keluhan_terakhir = $result['status_keluhan'];

                // Periksa apakah status keluhan terakhir adalah 'menunggu' atau 'proses'
                if ($status_keluhan_terakhir === 'menunggu' || $status_keluhan_terakhir === 'proses') {
                    // Keluhan sebelumnya masih dalam proses atau menunggu, tampilkan pesan kesalahan
                    echo "
                        <script>
                            setTimeout(function() {
                                swal({
                                    title: 'Gagal Mengirim',
                                    text: 'Masih Dalam Proses Pengerjaan atau Menunggu, Harap Bersabar!',
                                    type: 'error'
                                }, function() {
                                    window.location = '?page=keluhan';
                                });
                            }, 300);
                        </script>
                    ";
                } else {

                    $lastTicketQuery = $koneksi->query("SELECT MAX(nomor_tiket) AS last_ticket FROM tbl_keluhan");
                    $lastTicketData = $lastTicketQuery->fetch_assoc();
                    $lastTicket = $lastTicketData['last_ticket'];

                    // Jika tidak ada nomor tiket sebelumnya, mulai dari "pengaduan-001"
                    if (!$lastTicket) {
                        $nextTicket = 'pengaduan-001';
                    } else {
                        // Mengekstrak nomor urutan dari nomor tiket terakhir
                        $lastTicketNumber = intval(substr($lastTicket, strrpos($lastTicket, '-') + 1));

                        // Meningkatkan nomor tiket
                        $nextTicketNumber = $lastTicketNumber + 1;

                        // Membuat nomor tiket baru dengan format 'pengaduan-XXX'
                        $nextTicket = 'pengaduan-' . sprintf('%03d', $nextTicketNumber);
                    }

                    $id = $id_pelanggan;
                    $username = htmlspecialchars(strip_tags($_POST['judul_keluhan']));
                    $password = htmlspecialchars(strip_tags($_POST['isi_keluhan']));
                    $nama = htmlspecialchars(strip_tags($_POST['no_wa']));
                    $tgl_pemasangan = (new DateTime())->format('Y-m-d H:i:s');
                    $gambar = $_FILES['gambar']['name'];
                    $gambar_tmp = $_FILES['gambar']['tmp_name'];

                    if (!empty($gambar)) {
                        // Direktori tempat menyimpan gambar
                        $upload_directory = "images/keluhan/"; // Gantilah dengan direktori yang sesuai

                        // Membuat nama unik untuk gambar
                        $gambar_unik = uniqid() . '_' . $gambar;

                        // Pindahkan gambar ke direktori yang ditentukan
                        move_uploaded_file($gambar_tmp, $upload_directory . $gambar_unik);

                        // Simpan nama gambar dalam database
                        $gambar = $gambar_unik;
                    } else {
                        $gambar = ""; // Jika tidak ada gambar yang diunggah
                    }

                    $sql = $koneksi->query("INSERT INTO tbl_keluhan (id_pelanggan, judul_keluhan, nomor_tiket, isi_keluhan, no_wa, tanggal, gambar) VALUES ('$id', '$username', '$nextTicket', '$password', '$nama', '$tgl_pemasangan', '$gambar')");
                    // $sql = $koneksi->query("INSERT INTO tbl_keluhan (id_pelanggan, judul_keluhan, nomor_tiket, isi_keluhan, no_wa, tanggal) VALUES ('$id', '$username', '$nextTicket', '$password', '$nama', '$tgl_pemasangan')");

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
                            'target' => $ss['no_telp'],
                            'message' => 'Halo ' . $nama_user . ', Pengaduan Wifi Kamu Sudah Kami Terima Dengan Nomor Tiket ' . $nextTicket . ' Pada Tanggal ' . $tgl_pemasangan . ' Kami akan Merespon Secepat Mungkin. Terima Kasih' . "\n\n" . '_Jangan Balas Pesan Ini Pesan Otomatis_',
                        ),
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: ' . $authorizationToken
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    // echo $response;

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
                            'target' => $nomor_telepon_teknisi_string,
                            'message' => 'Halo, Kepada Teknisi Sepertinya Kamu Mendapatkan Keluhan Baru dengan nomor tiket ' . $nextTicket . ' pada tanggal ' . $tgl_pemasangan . ' Mohon untuk segera diperiksa',
                        ),
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: ' . $authorizationToken
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    // echo $response;

                    if ($sql) {
                        echo "
                    <script>
                        setTimeout(function() {
                            swal({
                                title: 'Keluhan',
                                text: 'Berhasil Mengirim Keluhan!',
                                type: 'success'
                            }, function() {
                                window.location = '?page=keluhan';
                            });
                        }, 300);
                    </script>
                ";
                    }
                }
            } else {

                $lastTicketQuery = $koneksi->query("SELECT MAX(nomor_tiket) AS last_ticket FROM tbl_keluhan");
                $lastTicketData = $lastTicketQuery->fetch_assoc();
                $lastTicket = $lastTicketData['last_ticket'];

                // Jika tidak ada nomor tiket sebelumnya, mulai dari "pengaduan-001"
                if (!$lastTicket) {
                    $nextTicket = 'pengaduan-001';
                } else {
                    // Mengekstrak nomor urutan dari nomor tiket terakhir
                    $lastTicketNumber = intval(substr($lastTicket, strrpos($lastTicket, '-') + 1));

                    // Meningkatkan nomor tiket
                    $nextTicketNumber = $lastTicketNumber + 1;

                    // Membuat nomor tiket baru dengan format 'pengaduan-XXX'
                    $nextTicket = 'pengaduan-' . sprintf('%03d', $nextTicketNumber);
                }

                $id = $id_pelanggan;
                $username = htmlspecialchars(strip_tags($_POST['judul_keluhan']));
                $password = htmlspecialchars(strip_tags($_POST['isi_keluhan']));
                $nama = htmlspecialchars(strip_tags($_POST['no_wa']));
                $tgl_pemasangan = (new DateTime())->format('Y-m-d H:i:s');
                $gambar = $_FILES['gambar']['name'];
                $gambar_tmp = $_FILES['gambar']['tmp_name'];

                if (!empty($gambar)) {
                    // Direktori tempat menyimpan gambar
                    $upload_directory = "images/keluhan/"; // Gantilah dengan direktori yang sesuai

                    // Membuat nama unik untuk gambar
                    $gambar_unik = uniqid() . '_' . $gambar;

                    // Pindahkan gambar ke direktori yang ditentukan
                    move_uploaded_file($gambar_tmp, $upload_directory . $gambar_unik);

                    // Simpan nama gambar dalam database
                    $gambar = $gambar_unik;
                } else {
                    $gambar = ""; // Jika tidak ada gambar yang diunggah
                }

                $sql = $koneksi->query("INSERT INTO tbl_keluhan (id_pelanggan, judul_keluhan, nomor_tiket, isi_keluhan, no_wa, tanggal, gambar) VALUES ('$id', '$username', '$nextTicket', '$password', '$nama', '$tgl_pemasangan', '$gambar')");
                // $sql = $koneksi->query("INSERT INTO tbl_keluhan (id_pelanggan, judul_keluhan, nomor_tiket, isi_keluhan, no_wa, tanggal) VALUES ('$id', '$username', '$nextTicket', '$password', '$nama', '$tgl_pemasangan')");

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
                        'target' => $ss['no_telp'],
                        'message' => 'Halo ' . $nama_user . ', Pengaduan Wifi Kamu Sudah Kami Terima Dengan Nomor Tiket ' . $nextTicket . ' Pada Tanggal ' . $tgl_pemasangan . ' Kami akan Merespon Secepat Mungkin. Terima Kasih' . "\n\n" . '_Jangan Balas Pesan Ini Pesan Otomatis_',
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: ' . $authorizationToken
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                // echo $response;

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
                        'target' => $nomor_telepon_teknisi_string,
                        'message' => 'Halo, Kepada Teknisi Sepertinya Kamu Mendapatkan Keluhan Baru dengan nomor tiket ' . $nextTicket . ' pada tanggal ' . $tgl_pemasangan . ' Mohon untuk segera diperiksa',
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: ' . $authorizationToken
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                // echo $response;

                if ($sql) {
                    echo "
                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Keluhan',
                            text: 'Berhasil Mengirim Keluhan!',
                            type: 'success'
                        }, function() {
                            window.location = '?page=keluhan';
                        });
                    }, 300);
                </script>
            ";
                }
            }
        }
    }

        ?>