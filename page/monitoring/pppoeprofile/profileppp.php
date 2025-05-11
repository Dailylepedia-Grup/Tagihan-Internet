<?php
session_start();
if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
?>

    <?php
    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $API = new RouterosAPI();

    if ($API->connect($row['ip'], $row['username'], $row['password'])) { ?>

        <div class="row">

            <?php
            $API->write('/ppp/profile/print');
            $pppProfile = $API->read();
            ?>

            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        Daftar PPPOE Profile
                    </div>
                    <div class="panel-body">
                        <a href="?page=pppoeprofile&aksi=tambah_ppprofile" type="button" class="btn btn-success" style="margin-bottom: 10px;">
                            Tambah
                        </a>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Local Address</th>
                                        <th>Remote Address</th>
                                        <th>Rate Limit</th>
                                        <th>Only One</th>
                                        <th>Insert Queue Bottom</th>
                                        <th>Parent Queue</th>
                                        <th>Script On Up</th>
                                        <th>Script On Down</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($pppProfile as $ppProv) : ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $ppProv['name'] ?></td>
                                            <td><?= $ppProv['local-address'] ?></td>
                                            <td><?= $ppProv['remote-address'] ?></td>
                                            <td><?= $ppProv['rate-limit'] ?></td>
                                            <td><?= $ppProv['only-one'] ?></td>
                                            <td><?= $ppProv['insert-queue-before'] ?></td>
                                            <td><?= $ppProv['parent-queue'] ?></td>
                                            <td><?= $ppProv['on-up'] ?></td>
                                            <td><?= $ppProv['on-down'] ?></td>
                                            <td>
                                                <a href="?page=pppoeprofile&aksi=ubah_pprofile&name=<?= $ppProv['name'] ?>" type="button" class="btn btn-warning"> Ubah </a>
                                                <a onclick="return confirm('Apakah Anda Yakin Mengahpus Data Ini')" href="?page=pppoeprofile&aksi=hapuspppprofile&id=<?= $ppProv['.id'] ?>" class="btn btn-danger" title=""> Hapus</a>
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
    }
    ?>

<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>