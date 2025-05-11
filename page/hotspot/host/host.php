<?php

function formatBytes($bytes)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }

    return round($bytes, 2) . ' ' . $units[$i];
}


session_start();
if (isset($_SESSION['admin']) || $_SESSION['teknisi'] == true) {
?>

    <?php
    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $API = new RouterosAPI();

    if ($API->connect($row['ip'], $row['username'], $row['password'])) {

        $API->write("/ip/hotspot/host/print");
        $s = $API->read();

    ?>

        <div class="row">

            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <b>Host Hotspot</b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mac Address</th>
                                        <th>Address</th>
                                        <th>To Address</th>
                                        <th>Server</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($s as $e) :
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $e['mac-address'] ?></td>
                                            <td><?= $e['address'] ?></td>
                                            <td><?= $e['to-address'] ?></td>
                                            <td><?= $e['server'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
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

<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>