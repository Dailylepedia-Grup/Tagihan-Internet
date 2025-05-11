<?php if ($_SESSION['admin']) { ?>
  <section class="content">
    <div class="row">
      <div class="box box-primary box-solid">
        <div class="box-header">
          <h3 class="box-title">Data Pengguna</h3>
        </div>

        <!-- /.box-header -->
        <div class="box-body">
          <a href="?page=pengguna&aksi=tambah" class="btn btn-info" style="margin-bottom: 10px;" title="">Tambah</a>
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="text-center">No</th>
                  <th class="text-center">Username</th>
                  <th class="text-center">Nama</th>
                  <th class="text-center">Password</th>
                  <th class="text-center">Level</th>
                  <th class="text-center">Foto</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>

                <?php

                $no = 1;

                $sql = $koneksi->query("select * from tb_user");

                while ($data = $sql->fetch_assoc()) {


                ?>


                  <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td class="text-center"><?php echo $data['username'] ?></td>
                    <td class="text-center"><?php echo $data['nama_user'] ?></td>
                    <td class="text-center"><?php echo $data['password'] ?></td>
                    <td class="text-center"><?php echo $data['level'] ?></td>
                    <td class="text-center"><img src="images/<?php echo $data['foto'] ?>" widht="75" height="75" alt=""></td>

                    <td class="text-center">

                      <a href="?page=pengguna&aksi=ubah&id=<?php echo $data['id']; ?>&username=<?= $data['username'] ?>" class="btn btn-success" title=""><i class="fa fa-edit"></i> Ubah</a>

                      <a href="?page=pengguna&aksi=hapus&id=<?php echo $data['id']; ?>" class="btn btn-danger" title=""><i class="fa fa-trash"></i> Hapus</a>


                    </td>

                  </tr>


                <?php } ?>

              </tbody>

            </table>

          </div>
        </div>
      </div>
  </section>

<?php } else {
  echo "Anda Tidak Berhak Mengakses Halaman Ini";
} ?>