<?php
session_start();
if (isset($_SESSION['admin']) || $_SESSION['teknisi'] == true) {
?>

    <?php
    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $API = new RouterosAPI();

    if ($API->connect($row['ip'], $row['username'], $row['password'])) {

        $API->write("/ip/hotspot/active/print");
        $s = $API->read();

        // var_dump($s);

    ?>

        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-12">
                                Details Active Hotspot
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Server</td>
                                        <td>Users</td>
                                        <td>Address</td>
                                        <td>Up Time</td>
                                        <td>Idle Time</td>
                                        <td>Session Active</td>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $i = 1;
                                    foreach ($s as $p) :
                                    ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $p['server'] ?></td>
                                            <td><?= $p['user'] ?></td>
                                            <td><?= $p['address'] ?></td>
                                            <td><?= $p['uptime'] ?></td>
                                            <td><?= $p['idle-time'] ?></td>
                                            <td><?= $p['session-time-left'] ?></td>
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