<?php

if (isset($_POST['download'])) {

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($backup_file_name));
    ob_clean();
    flush();
    readfile($backup_file_name);

    unlink($backup_file_name);
}

?>


<div class="row">

    <div class="col-md-12">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                Backup Database
            </div>
            <div class="panel-body">

                <!-- <button class="btn btn-success" id="downloadscsr" style="margin-bottom: 10px;"><i class="fa fa-download"></i> Download </button> -->
                <a href="?page=backup&aksi=now" class="btn btn-warning" id="downloadscsr" style="margin-bottom: 10px;"><i class="fa fa-hdd-o" aria-hidden="true"></i> Cadangkan Data</a>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="example1">

                        <thead>

                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Database</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Unduh Database</th>
                            </tr>

                        </thead>

                        <tbody>
                            <?php
                            $no = 1;
                            $sql = $koneksi->query("SELECT * FROM riwayat_backupdb ORDER BY id_backup DESC");
                            while ($data = $sql->fetch_assoc()) { ?>

                                <tr class="text-center">
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['nama_db'] ?></td>
                                    <td><?= $data['tanggal']; ?></td>
                                    <td>
                                        <a href="#" class="btn btn-success download-link" data-file="<?= $data['nama_db'] ?>"><i class="fa fa-download"></i> </a>
                                        <a href="?page=backup&aksi=hapus&id=<?= $data['id_backup'] ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>