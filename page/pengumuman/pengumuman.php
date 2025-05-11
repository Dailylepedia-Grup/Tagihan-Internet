<?php if ($_SESSION['admin']) { ?>

    <?php

    $sql_token = "SELECT * FROM tbl_token WHERE id_token = 1"; // Sesuaikan dengan query yang sesuai
    $result = mysqli_query($koneksi, $sql_token);
    $row = mysqli_fetch_assoc($result);
    $authorizationToken = $row['token'];

    $sql_pelanggan = "SELECT no_telp FROM tb_pelanggan";
    $result = mysqli_query($koneksi, $sql_pelanggan);

    $sql_informasi = $koneksi->query("SELECT * FROM tbl_informasi");
    $informasi = $sql_informasi->fetch_assoc();

    ?>

    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Pesan Siaran Whatsapp</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <p style="color: red;"><i>*Mengirim Pesan Siaran Ke Seluruh Pelanggan yang Terdaftar</i></p>
                        <p style="color: red;"><i>*Setelah Kirim Pesan Di Tekan, Harap Tunggu Sampai Ada Notif Berhasil</i>
                        </p>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Judul Pesan</label>
                            <input type="text" class="form-control" name="judul_siaran" placeholder="Contoh: Sedang Gangguan">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Isi Pesan</label>
                            <textarea class="form-control" rows="10" name="isi_siaran" placeholder="Contoh: Mohon Maaf Sedang Terjadi Gangguan Koneksi Internet"></textarea>
                        </div>

                    </div>
                    <div class="box-footer">
                        <button type="submit" name="simpan_pesan_siaran" class="btn btn-primary">Kirim Pesan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- right column -->
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Papan Pengumuman Pelanggan</h3>
                </div>
                <div class="box-body">
                    <p style="color: red;"><i>*Mengirim Pengumuman Ke Pelanggan Ketika Membuka Halaman Tagihan</i></p>
                    <p style="color: red;"><i>*Setelah Bagikan Informasi Di Tekan, Harap Tunggu Sampai Ada Notif Berhasil</i>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_informasi" value="<?= $informasi['id_informasi'] ?>">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Judul Pengumuman</label>
                            <input type="text" class="form-control" name="judul_informasi" placeholder="Contoh: Perubahan Tanggal Pembayaran" value="<?= $informasi['judul_informasi'] ?>">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Isi Pengumuman</label>
                            <textarea class="form-control" rows="10" name="isi_informasi" placeholder="Contoh: Perubahan Pembayaran Pada Tanggal 00-00-0000"><?= $informasi['isi_informasi'] ?></textarea>
                        </div>

                        <div class="box-footer">
                            <button type="submit" name="simpan_informasi" class="btn btn-primary">Bagikan Informasi</button>
                            <button type="submit" name="hapus_informasi" class="btn btn-primary">Hapus Informasi </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>


    <?php
    if (isset($_POST['hapus_informasi'])) {

        $id_informasi = $_POST['id_informasi'];
        if (!empty($id_informasi)) {
            $sql_informasi = $koneksi->query("DELETE FROM tbl_informasi WHERE id_informasi = $id_informasi");
            $message = "Berhasil Menghapus Informasi";
            if ($sql_informasi) {
    ?>
                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Berhasil',
                            text: '<?php echo $message; ?>',
                            type: 'success'
                        }, function() {
                            window.location = '?page=pengumuman';
                        });
                    }, 300);
                </script>
    <?php
            }
        }
    }
    ?>


    <?php
    if (isset($_POST['simpan_informasi'])) {

        $judul_informasi = $_POST['judul_informasi'];
        $isi_informasi = $_POST['isi_informasi'];

        if (!empty($judul_informasi) && !empty($isi_informasi)) {
            $cek_informasi = $koneksi->query("SELECT * FROM tbl_informasi");
            $row = $cek_informasi->fetch_assoc();

            if ($row) {
                // Jika data token sudah ada, lakukan UPDATE
                $informasi = $koneksi->query("UPDATE tbl_informasi SET judul_informasi='$judul_informasi', isi_informasi='$isi_informasi'");
                $message = 'Informasi Berhasil Di Update!';
            } else {
                // Jika data token belum ada, lakukan INSERT
                $informasi = $koneksi->query("INSERT INTO tbl_informasi (judul_informasi, isi_informasi) VALUES ('$judul_informasi', '$isi_informasi')");
                $message = 'Berhasil Menambah Informasi!';
            }

            if ($informasi) {
    ?>
                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Berhasil',
                            text: '<?php echo $message; ?>',
                            type: 'success'
                        }, function() {
                            window.location = '?page=pengumuman';
                        });
                    }, 300);
                </script>
    <?php
            }
        }
    }
    ?>


    <?php
    if (isset($_POST['simpan_pesan_siaran'])) {

        $judul_siaran = htmlspecialchars(strip_tags($_POST['judul_siaran']));
        $isi_siaran = htmlspecialchars(strip_tags($_POST['isi_siaran']));

        $curl = curl_init();

        $headers = array(
            'Authorization: ' . $authorizationToken, // Gantilah TOKEN dengan token Anda
            'Content-Type: application/x-www-form-urlencoded', // Menggunakan URL-encoded data
        );

        // Query untuk mengambil nomor telepon dari database
        $sql_pelanggan = "SELECT no_telp FROM tb_pelanggan";
        $result = mysqli_query($koneksi, $sql_pelanggan);

        while ($row = mysqli_fetch_assoc($result)) {
            // Dalam setiap iterasi, ambil nomor telepon dari hasil query
            $no_telp = $row['no_telp'];

            // Kemudian, kirim pesan WhatsApp dengan nomor telepon ini
            $data = array(
                'target' => $no_telp,
                'message' => "***" . $judul_siaran . "***" . "\n\n" . $isi_siaran,
                'delay' => 2,
                'countryCode' => '62', // Opsional
            );

            // Mengkodekan data sebagai string URL-encoded
            $postFields = http_build_query($data);

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postFields, // Menggunakan URL-encoded data
                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);

            // Tunggu beberapa detik sebelum mengirim pesan berikutnya (opsional)
            sleep(2); // Misalnya, tunggu 2 detik antara pengiriman pesan
        }

        curl_close($curl);

        if (!empty($judul_siaran) && !empty($isi_siaran)) {
            $sql_pesan_siaran = $koneksi->query("INSERT INTO tbl_pesan_siaran (judul_pesan_siaran, isi_pesan) VALUES ('$judul_siaran', '$isi_siaran')");
            $message = 'Berhasil Mengirim Pesan Siaran';
            if ($sql_pesan_siaran) {
    ?>
                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Berhasil',
                            text: '<?php echo $message; ?>',
                            type: 'success'
                        }, function() {
                            window.location = '?page=pengumuman';
                        });
                    }, 300);
                </script>
<?php
            }
        }
    }
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>