<div class="row">
    <div class="col-md-12">

        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                Data Perangkat Saya
            </div>
            <div class="panel-body">

                <a href="?page=perangkat&aksi=tambah_perangkat" type="button" class="btn btn-success" style="margin-bottom: 10px;">
                    Tambah
                </a>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="example1">

                        <thead>

                            <tr>
                                <th style="text-align: center;">No</th>
                                <th style="text-align: center;">Nama Perangkat</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>

                        </thead>
                        <tbody>

                            <?php
                            $i = 1;
                            $cp = $koneksi->query("SELECT * FROM tb_perangkat ORDER BY id_perangkat DESC");
                            while ($s = $cp->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td style="text-align: center;"><?= $i++; ?></td>
                                    <td style="text-align: center;"><?= $s['nama_perangkat'] ?></td>
                                    <td style="text-align: center;">
                                        <a href="?page=perangkat&aksi=ubah_perangkat&id=<?php echo $s['id_perangkat'] ?>" type="button" class="btn btn-info"><i class="fa fa-edit"></i> Ubah</a>
                                        <a href="?page=perangkat&aksi=hapus&id=<?php echo $s['id_perangkat']; ?>" class="btn btn-danger alert_notif" title=""><i class="fa fa-trash"></i> Hapus</a>
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