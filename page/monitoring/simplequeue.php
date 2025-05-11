<?php
session_start();
if (isset($_SESSION['admin']) || $_SESSION['teknisi'] == true) {
?>

    <?php
    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $API = new RouterosAPI();

    if ($API->connect($row['ip'], $row['username'], $row['password'])) { ?>

        <div class="row">

            <?php
            $API->write('/queue/simple/print');
            $simpleQueues = $API->read();
            ?>

            <div class="col-md-12">

                <div class="alert alert-danger" role="alert">
                    <b>CATATAN : <br> ~ UNTUK NAMA GANTI SPASI DENGAN - (STRIP) CONTOH : PELANGGAN-1 BUKAN PELANGGAN 1<br> ~ UPLOAD & DOWNLOAD MENGIKUTI FORMAT YANG ADA DI MIKROTIK SEPERTI 1M dan 1k & UNLIMITED ISI NILAI 0 (NOL)</b>
                </div>
                <div class="box box-primary box-solid mt-3">
                    <div class="box-header with-border">
                        Daftar Simple Queues
                    </div>
                    <div class="panel-body">
                        <a href="?page=tambahsimplequeue" type="button" class="btn btn-success" style="margin-bottom: 10px;">
                            Tambah
                        </a>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Target</th>
                                        <th>Parent</th>
                                        <th>Batas Download</th>
                                        <th>Batas Upload</th>
                                        <th>Download Terpakai</th>
                                        <th>Upload Terpakai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($simpleQueues as $sq) : ?>

                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $sq['name'] ?></td>
                                            <td><?= $sq['target'] ?></td>
                                            <td><?= $sq['parent'] ?></td>

                                            <?php
                                            $max = $sq['max-limit'];
                                            list($downloadMax, $uploadMax) = explode('/', $max);

                                            // Konversi uploadLimit
                                            if ($uploadMax >= 1000000) {
                                                $uploadLimitFormatted = round($uploadMax / 1000000, 2) . 'M'; // Konversi ke Mbps dan tambahkan satuan 'M'
                                            } elseif ($uploadMax >= 1000) {
                                                $uploadLimitFormatted = round($uploadMax / 1000, 2) . 'k'; // Konversi ke Kbps dan tambahkan satuan 'k'
                                            } else {
                                                $uploadLimitFormatted = 'unlimited'; // Nilai tetap dalam bps jika kurang dari 1000

                                            }

                                            // Konversi downloadLimit
                                            if ($downloadMax >= 1000000) {
                                                $downloadLimitFormatted = round($downloadMax / 1000000, 2) . 'M'; // Konversi ke Mbps dan tambahkan satuan 'M'
                                            } elseif ($downloadMax >= 1000) {
                                                $downloadLimitFormatted = round($downloadMax / 1000, 2) . 'k'; // Konversi ke Kbps dan tambahkan satuan 'k'
                                            } else {
                                                $downloadLimitFormatted = 'unlimited'; // Nilai tetap dalam bps jika kurang dari 1000
                                            }
                                            ?>

                                            <td><?= $uploadLimitFormatted ?></td>
                                            <td><?= $downloadLimitFormatted ?></td>

                                            <?php
                                            $data = $sq['bytes'];
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

                                            <td><?= $uploadFormatted ?></td>
                                            <td><?= $downloadFormatted ?></td>
                                            <td>
                                                <a href="?page=ubahsimplequeue&nama=<?= $sq['name'] ?>" type="button" class="btn btn-warning"> Ubah </a>

                                                <a onclick="return confirm('Apakah Anda Yakin Mengahpus Data Ini')" href="?page=simplequeue&aksi=hapus&id=<?= $sq['.id'] ?>" class="btn btn-danger" title=""> Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
        $API->disconnect();
    } ?>

<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>