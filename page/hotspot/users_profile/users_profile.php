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

        $API->write("/ip/hotspot/user/profile/print");
        $j = $API->read();

    ?>

        <div class="row">

            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <b>Users Profile</b> &nbsp; | &nbsp;
                        <a href="?page=users-profile&aksi=add_profile"> <b><i class="fa fa-user-plus" aria-hidden="true"></i> Add Profile</b></a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Shared Users</th>
                                        <th class="text-center">Rate Limit</th>
                                        <th class="text-center">Parent Queue</th>
                                        <th class="text-center">IP Pool</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($j as $t) :
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $i++; ?></td>
                                            <td class="text-center"><?= $t['name'] ?></td>
                                            <td class="text-center"><?= $t['shared-users'] ?></td>
                                            <td class="text-center"><?= $t['rate-limit'] ?></td>
                                            <td class="text-center"><?= $t['parent-queue'] ?></td>
                                            <td class="text-center"><?= $t['address-pool'] ?></td>
                                            <td class="text-center">
                                                <a href="?page=users-profile&aksi=edit_profile&id=<?= $t['.id'] ?>" class="btn btn-success"><i class="fa fa-edit"></i> </a>
                                                <a href="?page=users-profile&aksi=delete_profile&id=<?= $t['.id'] ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
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

    <?php }
    $API->disconnect();
    ?>

<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>