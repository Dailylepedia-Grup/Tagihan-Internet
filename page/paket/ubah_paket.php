<div class="row">

    <div class="col-md-6">

        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">Ubah Data Paket</h3>
            </div>

            <form role="form" method="POST">
                <div class="box-body">
                    <?php

                    $id_paket = $_GET['id'];

                    $sql1 = $koneksi->query("select * from tb_paket where id_paket='$id_paket'");

                    while ($data1 = $sql1->fetch_assoc()) {

                    ?>

                        <input type="hidden" name="id_paket" value="<?php echo $data1['id_paket']; ?>">

                        <?php
                        $cekStatus = $koneksi->query("SELECT * FROM tbl_paketmikrotik WHERE id_paketmikrotik = 1");
                        $ceks = $cekStatus->fetch_assoc();

                        if ($ceks['status'] == 'ya') {

                            $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
                            $result = mysqli_query($koneksi, $sql_mikrotik);
                            $row = mysqli_fetch_assoc($result);

                            $API = new RouterosAPI();
                            if ($API->connect($row['ip'], $row['username'], $row['password'])) {
                                $API->write('/ppp/profile/print');
                                $pppProfile = $API->read();
                        ?>
                                <div class="form-group">
                                    <label for="paketPPP">Dari Profile PPPOE Profile</label>
                                    <select class="form-control" name="profileppp" id="paketPPPu">
                                        <?php foreach ($pppProfile as $paketProfile) : ?>
                                            <?php if ($paketProfile['.id'] == $data1['id_pmikrotik']) { ?>
                                                <option value="<?= $paketProfile['.id'] ?>" data-nama="<?= $paketProfile['name'] ?>" selected><?= $paketProfile['name'] ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $paketProfile['.id'] ?>" data-nama="<?= $paketProfile['name'] ?>"><?= $paketProfile['name'] ?></option>
                                            <?php } ?>
                                        <?php endforeach; ?>
                                        <?php if ($data1['id_pmikrotik'] == NULL) { ?>
                                            <option value="NULL" selected>Tidak Ada di Dalam PPPOE Profile</option>
                                        <?php } else { ?>
                                            <option value="NULL">Tidak Ada di Dalam PPPOE Profile</option>
                                        <?php } ?>
                                    </select>
                                </div>

                        <?php
                            }
                        }

                        ?>

                        <div class="form-group">
                            <label>Paket</label>
                            <input required="" type="text" name="nama_paket" id="nama_paketu" class="form-control" value="<?php echo $data1['nama_paket']; ?>">
                        </div>

                        <div class="form-group">
                            <label>Harga Paket</label>
                            <input required="" type="text" name="harga" class="form-control uang" value="<?php echo $data1['harga']; ?>">
                        </div>

                        <div class="form-group">
                            <label>PPN</label>
                            <?php
                            // Ganti 'ppn' dengan nama kolom yang sesuai pada tabel Anda
                            $nilai_ppn_database = $data1['ppn'];

                            // Ubah nilai dari database ke bentuk persentase jika tidak NULL
                            $nilai_ppn_tampilan = ($nilai_ppn_database !== NULL) ? $nilai_ppn_database * 100 : NULL;
                            $fix = ($nilai_ppn_tampilan !== NULL) ? $nilai_ppn_tampilan . "%" : "";

                            // Perbarui cara menetapkan nilai ke atribut 'value'
                            ?>
                            <input required="" type="text" name="tarif_ppn" class="form-control" value="<?php echo $fix; ?>">
                        </div>




                        <div class="modal-footer">
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>

                        </div>

                    <?php } ?>

                </div>
            </form>

        </div>

    </div>

</div>

<?php

if (isset($_POST['simpan'])) {
    $id_paket_ubah = $_POST['id_paket'];
    $nama_paket = htmlspecialchars(strip_tags($_POST['nama_paket']));
    $harga = $_POST['harga'];
    $harga_oke = str_replace(".", "", $harga);

    $pmikrotik = $_POST['profileppp'];
    $pmikrotik = ($pmikrotik === "NULL") ? NULL : $pmikrotik;

    // Mendapatkan nilai PPN dari formulir
    $persentase_ppn = isset($_POST['tarif_ppn']) ? $_POST['tarif_ppn'] : NULL;

    // Mengonversi persentase ke nilai desimal
    $nilai_ppn = ($persentase_ppn !== NULL) ? $persentase_ppn / 100 : NULL;

    $sql = $koneksi->query("update tb_paket set nama_paket='$nama_paket', harga='$harga_oke', ppn='$nilai_ppn', id_pmikrotik='$pmikrotik' where id_paket='$id_paket_ubah'");

    if ($sql) {
        echo "

      <script>
          setTimeout(function() {
              swal({
                  title: 'Data Paket',
                  text: 'Berhasil Diubah!',
                  type: 'success'
              }, function() {
                  window.location = '?page=paket';
              });
          }, 300);
      </script>

  ";
    }
}

?>