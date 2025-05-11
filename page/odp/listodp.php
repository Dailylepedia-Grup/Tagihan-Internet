<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                Daftar Lengkap ODP
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="example1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama ODP</th>
                                <th class="text-center">Dari ODC</th>
                                <th class="text-center">Jumlah Port</th>
                                <th class="text-center">Port Terpakai</th>
                                <th class="text-center">List Pelanggan</th>
                                <th class="text-center">Lokasi</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            // $query = $koneksi->query("SELECT * FROM tbl_odp ORDER BY id_odp DESC");
                            $query = $koneksi->query("SELECT * FROM tbl_odc INNER JOIN tbl_odp ON tbl_odc.id_odc = tbl_odp.odc");
                            while ($getAll = $query->fetch_assoc()) {

                                if (!empty($getAll['location'])) {
                                    $coord_parts = explode(',', $getAll['location']);
                                    $lat = floatval(trim($coord_parts[0]));
                                    $lng = floatval(trim($coord_parts[1]));
                                }

                                // Menghitung jumlah ODC dari masing-masing ID ODC
                                $odp_id = $getAll['id_odp'];

                                $sql = $koneksi->query("SELECT COUNT(*) AS total_odp FROM tb_pelanggan WHERE odp = $odp_id");
                                $count = $sql->fetch_assoc();
                                $total_odp = $count['total_odp'];

                                // Ambil nama2 yang menggunakan odp
                                $sql2 = $koneksi->query("SELECT * FROM tbl_odp WHERE id_odp = $odp_id");
                                $getDat = $sql2->fetch_assoc();

                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center"><?php echo $getAll['nama_odp'] ?></td>
                                    <td class="text-center"><?php echo $getAll['nama_odc'] ?></td>
                                    <td class="text-center"><?php echo $getAll['port_odp'] ?></td>
                                    <td class="text-center"><?php echo $total_odp; ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?= $getDat['id_odp'] ?>">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <a href="https://www.google.com/maps?q=<?php echo $lat ?>,<?php echo $lng ?>" target="_blank">Buka GMaps</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="?page=odp&aksi=ubahodp&id=<?php echo $getAll['id_odp'] ?>" type="button" class="btn btn-info" title=""><i class=" fa fa-edit"></i></a>
                                        <a href="?page=odp&aksi=hapus&id=<?php echo $getAll['id_odp']; ?>" class="btn btn-danger alert_notif" title=""><i class="fa fa-trash"></i></a>
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

<?php
$sql3 = $koneksi->query("SELECT * FROM tbl_odp");
while ($getDatt = $sql3->fetch_assoc()) {
    $id = $getDatt['id_odp'];
?>
    <div class="modal fade" id="exampleModal<?= $getDatt['id_odp'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="exampleModalLabel">Daftar Pelanggan ODP <b><?= $getDatt['nama_odp'] ?></b></h5>
                </div>
                <div class="modal-body">

                    <?php
                    $userName = $koneksi->query("SELECT * FROM tb_pelanggan WHERE odp = $id");
                    while ($getName = $userName->fetch_assoc()) {
                    ?>
                        <ul>
                            <li><?php echo $getName['nama_pelanggan']; ?></li>
                        </ul>
                    <?php
                    }
                    ?>


                </div>
            </div>
        </div>
    </div>
<?php
}
?>