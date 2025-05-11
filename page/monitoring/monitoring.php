<?php
session_start();
if (isset($_SESSION['admin']) || $_SESSION['teknisi'] == true) {
?>

    <section class="content">

        <?php
        $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
        $result = mysqli_query($koneksi, $sql_mikrotik);
        $row = mysqli_fetch_assoc($result);

        $API = new RouterosAPI();

        if ($API->connect($row['ip'], $row['username'], $row['password'])) { ?>

            <div class="row">

                <?php
                $API->write('/system/clock/print');
                $clockInfo = $API->read();

                if (!empty($clockInfo) && isset($clockInfo[0]['time'])) {
                    $dateTime = $clockInfo[0]['time'];
                    $date = date('Y-m-d', strtotime($dateTime)); // Format tanggal
                    $time = date('H:i:s', strtotime($dateTime)); // Format waktu

                } else {
                    echo "Gagal mengambil tanggal dan waktu MikroTik.";
                }

                $API->write('/system/resource/print');
                $uptimeInfo = $API->read();

                if (!empty($uptimeInfo) && isset($uptimeInfo[0]['uptime'])) {
                    $uptime = $uptimeInfo[0]['uptime'];
                    if (preg_match('/(\d+)d(\d+)h(\d+)m/', $uptime, $matches)) {
                        $days = $matches[1];
                        $hours = $matches[2];
                        $minutes = $matches[3];

                        $formattedUptime = "{$days} Hari {$hours} Jam {$minutes} Menit";
                    } else {
                        // echo "Format waktu aktif tidak valid.";
                    }
                } else {
                    echo "Gagal mengambil informasi Uptime MikroTik.";
                }

                ?>

                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="info-box bg-yellow">
                        <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><b>Tanggal Dan Waktu</b></span>
                            <span class="info-box-text"><?= $date . ' ' . $time ?></span>
                            <span class="progress-description">
                                Uptime : <?= $uptimeInfo[0]['uptime'] ?>
                            </span>
                        </div>
                    </div>
                </div>


                <?php
                $API->write('/system/routerboard/print');
                $routerName = $API->read();

                $API->write('/system/resource/print');
                $versiRouterOS = $API->read();
                ?>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="info-box bg-green">
                        <span class="info-box-icon"><i class="fa fa-info" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><b>Perangkat Saya</b></span>
                            <span class="info-box-text">Nama Board : <?= $versiRouterOS[0]['board-name'] ?></span>
                            <span class="info-box-text">Model : <?= $routerName[0]['model']; ?></span>
                            <span class="info-box-text">Versi : <?= $versiRouterOS[0]['version'] ?></span>
                        </div>
                    </div>
                </div>

                <?php
                $API->write('/system/resource/print');
                $resourceInfo = $API->read();

                if (!empty($resourceInfo)) {
                    // Mengambil nilai 'free-memory' dalam byte dari hasil resource print
                    $freeMemoryInBytes = (int)$resourceInfo[0]['free-memory'];

                    // Mengkonversi byte menjadi megabyte (MB)
                    $freeMemoryInMB = $freeMemoryInBytes / 1048576; // 1 MB = 1048576 byte

                    // Bulatkan nilai dan format dengan satu desimal
                    $formattedMemory = number_format($freeMemoryInMB, 1);
                } else {
                    echo "Failed to retrieve resource information";
                }

                if (!empty($resourceInfo)) {
                    // Mengambil nilai 'total-hdd-space' dalam byte dari hasil resource print
                    $totalHddSpaceInBytes = (int)$resourceInfo[0]['total-hdd-space'];

                    // Mengkonversi byte menjadi KiB (kibibyte)
                    $totalHddSpaceInKiB = $totalHddSpaceInBytes / 1024; // 1 KiB = 1024 byte

                    // Konversi KiB menjadi KB
                    $totalHddSpaceInKB = $totalHddSpaceInKiB / 1.024; // 1 KB = 1.024 KiB

                    // Bulatkan nilai dan tampilkan dengan format "KB"
                    $formattedStorage = number_format($totalHddSpaceInKB, 0) . " KB";
                } else {
                    echo "Failed to retrieve resource information";
                }
                ?>

                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="info-box bg-light-blue">
                        <span class="info-box-icon"><i class="fa fa-server" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><b>SUMBER MIKROTIK</b></span>
                            <span class="info-box-text">CPU Terpakai : <?= $cpuUsage = $resourceInfo[0]['cpu-load']; ?> %</span>
                            <span class="info-box-text">Ram Bebas : <?= $formattedMemory ?> MB</span>
                            <span class="info-box-text">HDD Tersisa : <?= $formattedStorage ?> </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php
                $API->write('/log/print');
                $logs = $API->read();

                $lastLogs = array_slice($logs, -100);

                $lastLogs = array_reverse($lastLogs);
                ?>

                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            Log Mikrotik
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="example1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Time</th>
                                            <th>Topics</th>
                                            <th>Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($lastLogs as $log) {
                                        ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $log['time'] ?></td>
                                                <td><?= $log['topics'] ?></td>
                                                <td><?= $log['message'] ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <?php }
        $API->disconnect();
        ?>
    </section>

<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>