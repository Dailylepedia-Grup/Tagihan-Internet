<?php
session_start();
if (isset($_SESSION['admin']) || $_SESSION['teknisi'] == true) {
    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $API = new RouterosAPI();

    if ($API->connect($row['ip'], $row['username'], $row['password'])) {
        $getName = $_GET['nama'];

        // Retrieve the Simple Queues
        $API->write('/queue/simple/print');
        $simpleQueues = $API->read();

        // Find the queue based on "name" (assuming "name" is unique)
        $selectedQueue = null;
        foreach ($simpleQueues as $qw) {
            if ($qw['name'] == $getName) {
                $selectedQueue = $qw;
                break;
            }
        }

        if ($selectedQueue) {
?>
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ubah Simple Queue</h3>
                        </div>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="box-body">
                                <!-- Use the correct values for these fields -->
                                <input type="hidden" class="form-control" value="<?= $selectedQueue['.id'] ?>" id="queueIdUpdate" name="queueIdUpdate">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Nama</label>
                                    <input type="text" class="form-control" value="<?= $selectedQueue['name'] ?>" id="queueName" name="queueNameUpdate">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Target IP</label>
                                    <input type="text" class="form-control" value="<?= $selectedQueue['target'] ?>" id="target" name="targetUpdate">
                                </div>

                                <div class="row">

                                    <?php
                                    $maxLimit = $selectedQueue['max-limit'];
                                    list($uploadLimit, $downloadLimit) = explode('/', $maxLimit);

                                    // Konversi uploadLimit
                                    if ($uploadLimit >= 1000000) {
                                        $uploadLimitFormatted = round($uploadLimit / 1000000, 2) . 'M'; // Konversi ke Mbps dan tambahkan satuan 'M'
                                    } elseif ($uploadLimit >= 1000) {
                                        $uploadLimitFormatted = round($uploadLimit / 1000, 2) . 'k'; // Konversi ke Kbps dan tambahkan satuan 'k'
                                    } else {
                                        $uploadLimitFormatted = $uploadLimit; // Nilai tetap dalam bps jika kurang dari 1000
                                    }

                                    // Konversi downloadLimit
                                    if ($downloadLimit >= 1000000) {
                                        $downloadLimitFormatted = round($downloadLimit / 1000000, 2) . 'M'; // Konversi ke Mbps dan tambahkan satuan 'M'
                                    } elseif ($downloadLimit >= 1000) {
                                        $downloadLimitFormatted = round($downloadLimit / 1000, 2) . 'k'; // Konversi ke Kbps dan tambahkan satuan 'k'
                                    } else {
                                        $downloadLimitFormatted = $downloadLimit; // Nilai tetap dalam bps jika kurang dari 1000
                                    }
                                    ?>

                                    <div class="form-group col-md-6">
                                        <label for="uploadLimit">Upload Limit (0/512k/1M)</label>
                                        <input type="text" class="form-control" id="uploadLimit" value="<?= $uploadLimitFormatted ?>" name="uploadLimit">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="downloadLimit">Download Limit (0/512k/1M)</label>
                                        <input type="text" class="form-control" id="downloadLimit" value="<?= $downloadLimitFormatted ?>" name="downloadLimit">
                                    </div>

                                </div>

                                <div class="row">

                                    <?php
                                    $burstLimit = $selectedQueue['burst-limit'];
                                    list($burstLimitUpload, $burstLimitDownload) = explode('/', $burstLimit);

                                    // Konversi uploadLimit
                                    if ($burstLimitUpload >= 1000000) {
                                        $burstLimitUploadFormated = round($burstLimitUpload / 1000000, 2) . 'M'; // Konversi ke Mbps dan tambahkan satuan 'M'
                                    } elseif ($burstLimitUpload >= 1000) {
                                        $burstLimitUploadFormated = round($burstLimitUpload / 1000, 2) . 'k'; // Konversi ke Kbps dan tambahkan satuan 'k'
                                    } else {
                                        $burstLimitUploadFormated = $burstLimitUpload; // Nilai tetap dalam bps jika kurang dari 1000
                                    }

                                    // Konversi downloadLimit
                                    if ($burstLimitDownload >= 1000000) {
                                        $burstLimitDownloadFormated = round($burstLimitDownload / 1000000, 2) . 'M'; // Konversi ke Mbps dan tambahkan satuan 'M'
                                    } elseif ($burstLimitDownload >= 1000) {
                                        $burstLimitDownloadFormated = round($burstLimitDownload / 1000, 2) . 'k'; // Konversi ke Kbps dan tambahkan satuan 'k'
                                    } else {
                                        $burstLimitDownloadFormated = $burstLimitDownload; // Nilai tetap dalam bps jika kurang dari 1000
                                    }
                                    ?>

                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Burst Limit Upload (0/512k/1M)</label>
                                        <input type="text" class="form-control" id="burst-limit-upload" name="burst-limit-upload" value="<?= $burstLimitUploadFormated ?>" placeholder="Kosongkan Jika Unlimited">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Burst Limit Download (0/512k/1M)</label>
                                        <input type="text" class="form-control" id="burst-limit-download" name="burst-limit-download" value="<?= $burstLimitDownloadFormated ?>" placeholder="Kosongkan Jika Unlimited">
                                    </div>

                                </div>

                                <div class="row">

                                    <?php
                                    $threshold = $selectedQueue['burst-threshold'];
                                    list($thresholdUpload, $thresholdDownload) = explode('/', $threshold);

                                    // Konversi uploadLimit
                                    if ($thresholdUpload >= 1000000) {
                                        $thresholdUploadFormated = round($thresholdUpload / 1000000, 2) . 'M'; // Konversi ke Mbps dan tambahkan satuan 'M'
                                    } elseif ($thresholdUpload >= 1000) {
                                        $thresholdUploadFormated = round($thresholdUpload / 1000, 2) . 'k'; // Konversi ke Kbps dan tambahkan satuan 'k'
                                    } else {
                                        $thresholdUploadFormated = $thresholdUpload; // Nilai tetap dalam bps jika kurang dari 1000
                                    }

                                    // Konversi downloadLimit
                                    if ($thresholdDownload >= 1000000) {
                                        $thresholdDownloadFormated = round($thresholdDownload / 1000000, 2) . 'M'; // Konversi ke Mbps dan tambahkan satuan 'M'
                                    } elseif ($thresholdDownload >= 1000) {
                                        $thresholdDownloadFormated = round($thresholdDownload / 1000, 2) . 'k'; // Konversi ke Kbps dan tambahkan satuan 'k'
                                    } else {
                                        $thresholdDownloadFormated = $thresholdDownload; // Nilai tetap dalam bps jika kurang dari 1000
                                    }
                                    ?>

                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Burst Threshold Upload (0/512k/1M)</label>
                                        <input type="text" class="form-control" id="burst-threshold-upload" name="burst-threshold-upload" value="<?= $thresholdUploadFormated ?>" placeholder="Kosongkan Jika Unlimited">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Burst Threshold Download (0/512k/1M)</label>
                                        <input type="text" class="form-control" id="burst-threshold-download" name="burst-threshold-download" value="<?= $thresholdDownloadFormated ?>" placeholder="Kosongkan Jika Unlimited">
                                    </div>

                                </div>

                                <div class="row">

                                    <?php
                                    $timeBurst = $selectedQueue['burst-time'];
                                    list($timeUpload, $timeDownload) = explode('/', $timeBurst);
                                    ?>

                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Burst Time Upload (Kosongkan Jika Default)</label>
                                        <input type="text" class="form-control" id="burst-time-upload" name="burst-time-upload" value="<?= $timeUpload ?>" placeholder="0s">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Burst Time Download (Kosongkan Jika Default)</label>
                                        <input type="text" class="form-control" id="burst-time-download" name="burst-time-download" value="<?= $timeDownload ?>" placeholder="0s">
                                    </div>

                                </div>

                                <div class="row">

                                    <?php
                                    $limitAt = $selectedQueue['limit-at'];
                                    list($limitAtUpload, $limitAtDownload) = explode('/', $limitAt);

                                    // Konversi uploadLimit
                                    if ($limitAtUpload >= 1000000) {
                                        $limitAtUploadFormated = round($limitAtUpload / 1000000, 2) . 'M'; // Konversi ke Mbps dan tambahkan satuan 'M'
                                    } elseif ($limitAtUpload >= 1000) {
                                        $limitAtUploadFormated = round($limitAtUpload / 1000, 2) . 'k'; // Konversi ke Kbps dan tambahkan satuan 'k'
                                    } else {
                                        $limitAtUploadFormated = $limitAtUpload; // Nilai tetap dalam bps jika kurang dari 1000
                                    }

                                    // Konversi downloadLimit
                                    if ($limitAtDownload >= 1000000) {
                                        $limitAtDownloadFormated = round($limitAtDownload / 1000000, 2) . 'M'; // Konversi ke Mbps dan tambahkan satuan 'M'
                                    } elseif ($limitAtDownload >= 1000) {
                                        $limitAtDownloadFormated = round($limitAtDownload / 1000, 2) . 'k'; // Konversi ke Kbps dan tambahkan satuan 'k'
                                    } else {
                                        $limitAtDownloadFormated = $limitAtDownload; // Nilai tetap dalam bps jika kurang dari 1000
                                    }
                                    ?>

                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Limit At Upload (0/512k/1M)</label>
                                        <input type="text" class="form-control" id="limit-at-upload" name="limit-at-upload" value="<?= $limitAtUploadFormated  ?>" placeholder="Kosongkan Jika Unlimited">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Limit At Download (0/512k/1M)</label>
                                        <input type="text" class="form-control" id="limit-at-download" name="limit-at-download" value="<?= $limitAtDownloadFormated ?>" placeholder="Kosongkan Jika Unlimited">
                                    </div>

                                </div>

                                <div class="form-group">
                                    <?php
                                    $API->write('/queue/simple/print');
                                    $simpleQueues = $API->read();
                                    ?>
                                    <label for="parentQueueName">Parent</label>
                                    <select class="form-control" id="parentQueueName" name="parentQueueNameUpdate">
                                        <option value="none">none</option> <!-- Tambahkan ini -->
                                        <?php foreach ($simpleQueues as $qw) { ?>
                                            <?php if ($qw['name'] == $selectedQueue['parent']) { ?>
                                                <option value="<?= $qw['name'] ?>" selected><?= $qw['name'] ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $qw['name'] ?>"><?= $qw['name'] ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="goBack()">Kembali</button>
                                    <button type="submit" name="updateSimpleQueue" class="btn btn-primary">Ubah Simple Queue</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }

        if (isset($_POST['updateSimpleQueue'])) {

            $queueId = $_POST['queueIdUpdate'];
            $queueName = $_POST['queueNameUpdate'];
            $target = $_POST['targetUpdate'];

            $uploadLimit = isset($_POST['uploadLimit']) && $_POST['uploadLimit'] !== '' ? $_POST['uploadLimit'] : 0;
            $downloadLimit = isset($_POST['downloadLimit']) && $_POST['downloadLimit'] !== '' ? $_POST['downloadLimit'] : 0;

            $burstLimitUpload = isset($_POST['burst-limit-upload']) && $_POST['burst-limit-upload'] !== '' ? $_POST['burst-limit-upload'] : 0;
            $burstLimitDownload = isset($_POST['burst-limit-download']) && $_POST['burst-limit-download'] !== '' ? $_POST['burst-limit-download'] : 0;

            $burstThresholdUpload = isset($_POST['burst-threshold-upload']) && $_POST['burst-threshold-upload'] !== '' ? $_POST['burst-threshold-upload'] : 0;
            $burstThresholdDownload = isset($_POST['burst-threshold-download']) && $_POST['burst-threshold-download'] !== '' ? $_POST['burst-threshold-download'] : 0;

            $burstTimeUpload = isset($_POST['burst-time-upload']) && $_POST['burst-time-upload'] !== '' ? $_POST['burst-time-upload'] : 0;
            $burstTimeDownload = isset($_POST['burst-time-download']) && $_POST['burst-time-download'] !== '' ? $_POST['burst-time-download'] : 0;

            $limitAtUpload = isset($_POST['limit-at-upload']) && $_POST['limit-at-upload'] !== '' ? $_POST['limit-at-upload'] : 0;
            $limitAtDownload = isset($_POST['limit-at-download']) && $_POST['limit-at-download'] !== '' ? $_POST['limit-at-download'] : 0;

            $parentQueueName = $_POST['parentQueueNameUpdate'];

            $berhasilUpdate = $API->comm("/queue/simple/set", array(
                ".id" => $queueId,
                "name" => $queueName,
                "target" => $target,
                "max-limit" => "$uploadLimit/$downloadLimit",
                "burst-limit" => "$burstLimitUpload/$burstLimitDownload",
                "burst-threshold" => "$burstThresholdUpload/$burstThresholdDownload",
                "burst-time" => "$burstTimeUpload/$burstTimeDownload",
                "limit-at" => "$limitAtUpload/$limitAtDownload",
                "parent" => $parentQueueName,
            ));

            // Periksa jika operasi penghapusan berhasil
            if (count($berhasilUpdate) == 0) {
            ?>

                <script>
                    setTimeout(function() {
                        swal({
                            title: 'Simple Queues',
                            text: 'Data Berhasil Di Update',
                            type: 'success'
                        }, function() {
                            window.location = '?page=simplequeue';
                        });
                    }, 300);
                </script>

<?php
            } else {
                // Handle the error if the update fails
                // echo "Gagal Mengubah Simple Queue. Pesan kesalahan: " . $API->error;
                echo "  <script>
                setTimeout(function() {
                    swal({
                        title: 'Simple Queues',
                        text: 'Gagal Di Update',
                        type: 'error'
                    }, function() {
                        window.location = '?page=simplequeue';
                    });
                }, 300);
            </script>";
            }
        }
        $API->disconnect();
    }
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>