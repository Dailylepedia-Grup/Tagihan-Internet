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
            $API->write('/ppp/secret/print');
            $pppSecrets = $API->read();

            ?>
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        Daftar PPPOE Secrets
                    </div>
                    <div class="panel-body">
                        <a href="?page=tambahpppoesecret" type="button" class="btn btn-success" style="margin-bottom: 10px;">
                            Tambah
                        </a>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Password</th>
                                        <th>Service</th>
                                        <th>Profile</th>
                                        <th>Local Address</th>
                                        <th>Remote Address</th>
                                        <th>Mac Address</th>
                                        <th>Last Disconnect</th>
                                        <th>Terakhir Keluar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($pppSecrets as $ppp) { ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $ppp['name'] ?></td>
                                            <td><?= $ppp['password'] ?></td>
                                            <td><?= $ppp['service'] ?></td>
                                            <td><?= $ppp['profile'] ?></td>
                                            <td><?= $ppp['local-address'] ?></td>
                                            <td><?= $ppp['remote-address'] ?></td>
                                            <td><?= $ppp['last-caller-id'] ?></td>
                                            <td><?= $ppp['last-disconnect-reason'] ?></td>
                                            <td><?= $ppp['last-logged-out'] ?></td>
                                            <td>
                                                <a href="?page=ubahpppoesecrect&nama=<?= $ppp['name'] ?>" type="button" class="btn btn-warning">
                                                    Ubah
                                                </a>

                                                <a onclick="return confirm('Apakah Anda Yakin Mengahpus Data Ini')" href="?page=pppoe&aksi=hapus&id=<?= $ppp['.id'] ?>" class="btn btn-danger" title=""> Hapus</a>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        $API->disconnect();
    }
    ?>

<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>