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

        $API->write("/ip/hotspot/user/print");
        $s = $API->read();
    ?>

        <div class="row">

            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <b>Users Hotspot</b> &nbsp; | &nbsp;
                        <a href="?page=users-hotspot&aksi=add_users"> <b><i class="fa fa-user-plus" aria-hidden="true"></i> Add Users</b></a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Profile</th>
                                        <th>Bytes In</th>
                                        <th>Bytes Out</th>
                                        <th>Up Time</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($s as $p) :
                                    ?>
                                        <tr>
                                            <td style='text-align:center;'>
                                                <a href=""><i class='fa fa-trash text-danger pointer'></i></a>
                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                <a href=""><i class='fa fa-minus-square text-danger pointer'></i></a>
                                            </td>
                                            <td><?= $i++ ?></td>
                                            <td><?= $p['name'] ?></td>
                                            <td><?= $p['profile'] ?></td>
                                            <td><?= ($p['uptime'] == '0s') ? '00:00:00' : ($p['uptime']) ?></td>
                                            <td><?= formatBytes($p['bytes-in']) ?></td>
                                            <td><?= formatBytes($p['bytes-out']) ?></td>
                                            <td><?= $p['comment'] ?></td>
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