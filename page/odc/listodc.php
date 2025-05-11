<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                Daftar Lengkap ODC
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="example1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama ODC</th>
                                <th class="text-center">Jumlah Port</th>
                                <th class="text-center">Port Terpakai</th>
                                <th class="text-center">Perangkat ODC</th>
                                <th class="text-center">Lokasi</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = $koneksi->query("SELECT * FROM tbl_odc ORDER BY id_odc DESC");
                            while ($getAll = $query->fetch_assoc()) {

                                if (!empty($getAll['location'])) {
                                    $coord_parts = explode(',', $getAll['location']);
                                    $lat = floatval(trim($coord_parts[0]));
                                    $lng = floatval(trim($coord_parts[1]));
                                }

                                // Menghitung jumlah ODC dari masing-masing ID ODC
                                $odc_id = $getAll['id_odc'];
                                $sql = $koneksi->query("SELECT COUNT(*) AS total_odp FROM tbl_odp WHERE odc = $odc_id");
                                $count = $sql->fetch_assoc();
                                $total_odp = $count['total_odp'];

                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center"><?php echo $getAll['nama_odc'] ?></td>
                                    <td class="text-center"><?php echo $getAll['port_odc'] ?></td>
                                    <td class="text-center"><?php echo $total_odp; ?></td>
                                    <td class="text-center"><?php echo $getAll['perangkat_odc'] ?></td>
                                    <td class="text-center">
                                        <a href="https://www.google.com/maps?q=<?php echo $lat ?>,<?php echo $lng ?>" target="_blank">Buka GMaps</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="?page=odc&aksi=ubahodc&id=<?php echo $getAll['id_odc'] ?>" type="button" class="btn btn-info" title=""><i class=" fa fa-edit"></i></a>
                                        <a href="?page=odc&aksi=hapus&id=<?php echo $getAll['id_odc']; ?>" class="btn btn-danger alert_notif" title=""><i class="fa fa-trash"></i></a>
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