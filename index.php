<?php

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "include/koneksi.php";
require("include/routeros_api.php");

session_start();


$satu_hari        = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

function tglIndonesia2($str)
{
  $tr   = trim($str);
  $str    = str_replace(array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'), array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'), $tr);
  return $str;
}

$sql_token = "SELECT * FROM tbl_token WHERE id_token = 1"; // Sesuaikan dengan query yang sesuai
$result = mysqli_query($koneksi, $sql_token);
$row = mysqli_fetch_assoc($result);
$authorizationToken = $row['token'];

$sql_jatuhtempo = $koneksi->query("SELECT * FROM tb_pelanggan");
$waktu = $sql_jatuhtempo->fetch_assoc();

$get_pesan = $koneksi->query("SELECT * FROM tbl_notifbayar INNER JOIN tb_pelanggan");
$ambil_pesan = $get_pesan->fetch_assoc();
$pesan = $ambil_pesan['pesan_bayar'];

$get_notif = $koneksi->query("SELECT * FROM tbl_notif INNER JOIN tb_pelanggan");
$ambil_notif = $get_notif->fetch_assoc();
$notif = $ambil_notif['pesan_notifikasi'];

$ss = $koneksi->query("SELECT * FROM tbl_blokir INNER JOIN tb_pelanggan");
$qwe = $ss->fetch_assoc();

$qqq = $koneksi->query("SELECT * FROM tbl_bukablokir INNER JOIN tb_pelanggan");
$asd = $qqq->fetch_assoc();

$sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
$result = mysqli_query($koneksi, $sql_mikrotik);
$row = mysqli_fetch_assoc($result);


if ($_SESSION['admin'] || $_SESSION['user'] || $_SESSION['kasir'] || $_SESSION['teknisi']) {

?>

  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aplikasi Tagihan</title>
    <!-- Tell the browser to be responsive to screen width -->
    <!-- <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  </head>

  <style>
    /* CSS untuk menyembunyikan scrollbar di browser berbasis WebKit (seperti Chrome) */
    .sidebar::-webkit-scrollbar {
      width: 0 !important;
      /* Lebar scrollbar menjadi 0 */
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: transparent !important;
      /* Warna background scrollbar menjadi transparan */
    }

    #map {
      height: 100vh;
      /* Set height to 100% of viewport height */
    }

    /* Optional: Style adjustments for mobile */
    @media (max-width: 600px) {
      #map {
        height: 50vh;
        /* Adjust height for smaller screens */
      }
    }
  </style>

  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <link rel="stylesheet" type="text/css" href="sw/dist/sweetalert.css">
  <script type="text/javascript" src="sw/dist/sweetalert.min.js"></script>

  </head>

  <body class="hold-transition skin-blue sidebar-mini">

    <!-- Site wrapper -->
    <div class="wrapper">

      <?php

      $sql2 = $koneksi->query("select * from tb_profile ");

      $data1 = $sql2->fetch_assoc();

      ?>
      <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>A</b>LT</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><?php echo $data1['nama_sekolah'] ?></b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>


          <?php

          if ($_SESSION['admin']) {
            $user = $_SESSION['admin'];
          } elseif ($_SESSION['user']) {
            $user = $_SESSION['user'];
          } elseif ($_SESSION['kasir']) {
            $user = $_SESSION['kasir'];
          } elseif ($_SESSION['teknisi']) {
            $user = $_SESSION['teknisi'];
          }


          $sql_user = $koneksi->query("select * from tb_user where id='$user'");
          $data_user = $sql_user->fetch_assoc();

          $nama = $data_user['nama_user'];

          $level = $data_user['level'];

          $id_user = $data_user['id'];
          $id_user = $data_user['id'];

          $id_pelanggan = $data_user['id_pelanggan'];

          ?>

          <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

              <?php if ($_SESSION['admin']) { ?>

                <!-- <li class="">
                  <a href="?page=order">
                    <span class="label label-danger">?</span><i class="fa fa-shopping-basket" aria-hidden="true"> </i>
                  </a>
                </li> -->

                <li class="">
                  <a href="?page=dokumentasi">
                    <i class="fa fa-book" aria-hidden="true"></i> Dokumentasi
                  </a>
                </li>

              <?php } ?>

              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle red-button" id="refresh-button">
                  <i class="fa fa-refresh" aria-hidden="true"></i> Refresh
                </a>
              </li>

              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="images/<?php echo $data_user['foto'] ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs">Hai, <?php echo $nama ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="images/<?php echo $data_user['foto'] ?>" class="img-circle" alt="User Image">

                    <p>
                      Anda Login Sebagai
                      <small><?php echo $level ?></small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="?page=ubah_p&id=<?php echo $data_user['id']; ?>" class="btn btn-default btn-flat">Ubah Password</a>
                    </div>
                    <div class="pull-right">
                      <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
            </ul>

          </div>
        </nav>
      </header>

      <!-- =============================================== -->

      <!-- Left side column. contains the sidebar -->


      <?php include "include/menu.php"; ?>


      <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <?php include "include/isi.php"; ?>

          <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="box box-primary box-solid">
                  <div class="box-header with-border">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    Rekap Kas Masuk dan Keluar
                  </div>

                  <div class="modal-body">
                    <form role="form" method="POST" target="blank" action="page/laporan/rekap_kas.php">
                      <div class="form-group">
                        <label>Tanggal Awal</label>
                        <input type="date" name="tgl_awal" required="" class="form-control">
                      </div>

                      <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" required="" class="form-control">
                      </div>


                      <div class="modal-footer">
                        <button type="submit" name="tambah" class="btn btn-primary"><i class="fa fa-print"></i> Cetak</button>
                      </div>

                    </form>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
            </div>
          </div>

        </section>
      </div>
      <!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
        </div>
        <b>Version</b> 7.0
        <strong><a href="">Tagihan Internet </a>.</strong> By Ibnu.
      </footer>

      <!-- Control Sidebar -->

      <!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 2.2.3 -->
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/select2/select2.full.min.js"></script>
    <!-- SlimScroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script src="jquery.mask.min.js"></script>

    <script src="plugins/chartjs/Chart.js"></script>

    <script type="text/javascript">
      $(document).ready(function() {

        // Format mata uang.
        $('.uang').mask('000.000.000.000.000.000', {
          reverse: true
        });

      })


      $("#parent").click(function() {
        $(".child").prop("checked", this.checked);
      });


      $('.child').click(function() {
        if ($('.child:checked').length == $('.child').length) {
          $('#parent').prop('checked', true);
        } else {
          $('#parent').prop('checked', false);
        }
      });
    </script>

    <script>
      $(function() {
        $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });

      $('#example3').DataTable({
        dom: 'Bfrtip',
        buttons: [
          'print'
        ]
      });
    </script>

    <script>
      $(function() {
        //Initialize Select2 Elements
        $(".select2").select2();

        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {
          "placeholder": "dd/mm/yyyy"
        });
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {
          "placeholder": "mm/dd/yyyy"
        });
        //Money Euro
        $("[data-mask]").inputmask();

        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
          timePicker: true,
          timePickerIncrement: 30,
          format: 'MM/DD/YYYY h:mm A'
        });
        //Date range as a button
        $('#daterange-btn').daterangepicker({
            ranges: {
              'Today': [moment(), moment()],
              'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days': [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month': [moment().startOf('month'), moment().endOf('month')],
              'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
          },
          function(start, end) {
            $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          }
        );

        //Date picker
        $('#datepicker').datepicker({
          autoclose: true
        });

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

        //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();

        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false
        });
      });
    </script>

    <script>
      // Menangani klik tombol
      document.querySelectorAll(".bayarBtn").forEach(function(button) {
        button.addEventListener("click", function(event) {
          event.preventDefault();

          var idPelanggan = this.getAttribute("data-idpelanggan");
          var kode = this.getAttribute("data-kode");
          var ip_address = this.getAttribute("data-ip_pelanggan");
          var no_hp = this.getAttribute("data-nohp");
          var nama = this.getAttribute("data-nama");
          var tagihan = this.getAttribute("data-tagihan");
          var harinin = this.getAttribute("data-waktu");
          var invoice = this.getAttribute("data-invoice");

          var pesan = `<?php echo $pesan; ?>`;

          if (!pesan) {}

          pesan = pesan.replace('$nama', nama);
          pesan = pesan.replace('$tagihan', tagihan);
          pesan = pesan.replace('$no_telp', no_hp);
          pesan = pesan.replace('$harinin', harinin);
          pesan = pesan.replace('$kode', kode);
          pesan = pesan.replace('$invoice', invoice);

          // Data untuk dikirim ke API
          var postData = {
            target: no_hp,
            message: pesan,
            countryCode: '62'
          };

          // Check if authorizationToken exists
          var authorizationToken = '<?php echo $authorizationToken; ?>';
          var hasAuthorizationToken = authorizationToken && authorizationToken.trim() !== '';

          // Kirim pesan menggunakan API WhatsApp jika authorizationToken ada
          if (hasAuthorizationToken) {
            fetch('https://api.fonnte.com/send', {
                method: 'POST',
                headers: {
                  'Authorization': authorizationToken
                },
                body: new URLSearchParams(postData)
              })
              .then(response => response.json())
              .then(data => {
                console.log("Respons dari API:", data);

                if (data.status === true) {
                  fetch('?page=transaksi&aksi=bayar&id=' + idPelanggan + '&ipaddress=' + ip_address, {
                    method: 'POST'
                  })
                  setTimeout(function() {
                    swal({
                      title: 'Tagihan',
                      text: 'Berhasil Dibayar!',
                      type: 'success'
                    }, function() {
                      window.location = '?page=transaksi';
                    });
                  }, 300);
                } else {
                  Swal.fire({
                    title: 'Informasi Pesan',
                    text: 'Pesan gagal dikirim',
                    icon: 'error'
                  });
                }
              })
              .catch(error => {
                console.error("Terjadi kesalahan:", error);
              });
          } else {
            // Jika authorizationToken tidak ada, hanya lakukan update database
            fetch('?page=transaksi&aksi=bayar&id=' + idPelanggan + '&ipaddress=' + ip_address, {
              method: 'POST'
            })
            setTimeout(function() {
              swal({
                title: 'Tagihan',
                text: 'Berhasil Dibayar Tanpa Mengirim Pesan Notifikasi',
                type: 'success'
              }, function() {
                window.location = '?page=transaksi';
              });
            }, 300);
          }
        });
      });
    </script>


    <!-- blokir -->
    <script>
      // Menangani klik tombol
      document.querySelectorAll(".blokirBtn").forEach(function(button) {
        button.addEventListener("click", function(event) {
          event.preventDefault();

          var cekMikrotik = <?= json_encode($row) ?>; // Assuming $row is an array or an object

          // Check if cekMikrotik has data
          if (cekMikrotik) {
            // Mengambil data dari atribut
            var id_tagihan = this.getAttribute("data-idtagihan");
            var kode = this.getAttribute("data-kode");
            var ip_pelanggan = this.getAttribute("data-ip_pelanggan");
            var no_hp = this.getAttribute("data-nohp");
            var nama = this.getAttribute("data-nama");
            var tagihan = this.getAttribute("data-tagihan");
            var harinin = this.getAttribute("data-waktu");

            var blokir = `<?= $qwe['pesan_blokir'] ?>`;

            // Ganti placeholder dalam teks yang diambil dari textarea dengan nilai dari variabel JavaScript yang sesuai
            blokir = blokir.replace('$nama', nama);
            blokir = blokir.replace('$tagihan', tagihan);
            blokir = blokir.replace('$no_telp', no_hp);
            blokir = blokir.replace('$sekarang_format', harinin);
            blokir = blokir.replace('$kode', kode);

            // Data untuk dikirim ke API
            var postData = {
              target: no_hp,
              message: blokir,
              countryCode: '62'
            };

            // Check apakah authorizationToken ada
            var authorizationToken = '<?php echo $authorizationToken; ?>';
            var hasAuthorizationToken = authorizationToken && authorizationToken.trim() !== '';

            // Kirim pesan menggunakan API WhatsApp jika authorizationToken ada
            if (hasAuthorizationToken) {
              fetch('https://api.fonnte.com/send', {
                  method: 'POST',
                  headers: {
                    'Authorization': authorizationToken
                  },
                  body: new URLSearchParams(postData)
                })
                .then(response => response.json())
                .then(data => {

                  // Jika pesan berhasil dikirim, lakukan update database
                  if (data.status === true) {
                    // Kirim permintaan Ajax ke file PHP untuk melakukan update database
                    fetch('page/transaksi/blokir.php?ip_pelanggan=' + ip_pelanggan + '&id_tagihan=' + id_tagihan, {
                      method: 'GET'
                    })
                    setTimeout(function() {
                      swal({
                        title: 'Blokir',
                        text: 'Berhasil Memblokir!',
                        type: 'success'
                      }, function() {
                        window.location = '?page=transaksi';
                      });
                    }, 300);
                  } else {
                    // Tampilkan notifikasi pesan gagal dikirim
                    Swal.fire({
                      title: 'Informasi Pesan',
                      text: 'Pesan gagal dikirim',
                      icon: 'error'
                    });
                  }
                })
                .catch(error => {
                  console.error("Terjadi kesalahan:", error);
                });
            } else {
              // Jika authorizationToken tidak ada, hanya lakukan update database
              fetch('page/transaksi/blokir.php?ip_pelanggan=' + ip_pelanggan + '&id_tagihan=' + id_tagihan, {
                method: 'GET'
              })
              setTimeout(function() {
                swal({
                  title: 'Blokir',
                  text: 'Berhasil Memblokir Tanpa Mengirim Pesan',
                  type: 'success'
                }, function() {
                  window.location = '?page=transaksi';
                });
              }, 300);
            }
          } else {
            swal({
              title: 'Hubungkan Mikrotik API Terlebih Dahulu',
              text: 'Pengaturan -> Mikrotik API Kosong',
              type: 'error'
            }, function() {
              window.location = '?page=transaksi';
            });
          }
        });
      });
    </script>


    <!-- open blokir  -->
    <script>
      // Menangani klik tombol
      document.querySelectorAll(".openBlokirBtn").forEach(function(button) {
        button.addEventListener("click", function(event) {
          event.preventDefault();

          // Mengambil data dari atribut
          var id_tagihan = this.getAttribute("data-idtagihan");
          var kode = this.getAttribute("data-kode");
          var ip_pelanggan = this.getAttribute("data-ip_pelanggan");
          var no_hp = this.getAttribute("data-nohp");
          var nama = this.getAttribute("data-nama");
          var tagihan = this.getAttribute("data-tagihan");
          var harinin = this.getAttribute("data-waktu");

          var openblokir = `<?= $asd['pesan_bukablokir'] ?>`;

          // Ganti placeholder dalam teks yang diambil dari textarea dengan nilai dari variabel JavaScript yang sesuai
          openblokir = openblokir.replace('$nama', nama);
          openblokir = openblokir.replace('$tagihan', tagihan);
          openblokir = openblokir.replace('$no_telp', no_hp);
          openblokir = openblokir.replace('$hariinin', harinin);
          openblokir = openblokir.replace('$kode', kode);

          // Data untuk dikirim ke API
          var postData = {
            target: no_hp,
            message: openblokir,
            countryCode: '62'
          };

          // Check apakah authorizationToken ada
          var authorizationToken = '<?php echo $authorizationToken; ?>';
          var hasAuthorizationToken = authorizationToken && authorizationToken.trim() !== '';

          // Kirim pesan menggunakan API WhatsApp jika authorizationToken ada
          if (hasAuthorizationToken) {
            fetch('https://api.fonnte.com/send', {
                method: 'POST',
                headers: {
                  'Authorization': authorizationToken
                },
                body: new URLSearchParams(postData)
              })
              .then(response => response.json())
              .then(data => {
                // Tangani respons dari API
                console.log("Respons dari API:", data);

                // Jika pesan berhasil dikirim, lakukan update database
                if (data.status === true) {
                  // Kirim permintaan Ajax ke file PHP untuk melakukan update database
                  fetch('page/transaksi/hapus_blokir.php?ip_pelanggan=' + ip_pelanggan + '&id_tagihan=' + id_tagihan, {
                    method: 'GET'
                  })
                  setTimeout(function() {
                    swal({
                      title: 'Membuka Blokir',
                      text: 'Berhasil Membuka!',
                      type: 'success'
                    }, function() {
                      window.location = '?page=transaksi';
                    });
                  }, 300);
                } else {
                  // Tampilkan notifikasi pesan gagal dikirim
                  Swal.fire({
                    title: 'Informasi Pesan',
                    text: 'Pesan gagal dikirim',
                    icon: 'error'
                  });
                }
              })
              .catch(error => {
                console.error("Terjadi kesalahan:", error);
              });
          } else {
            // Jika authorizationToken tidak ada, hanya lakukan update database
            fetch('page/transaksi/hapus_blokir.php?ip_pelanggan=' + ip_pelanggan + '&id_tagihan=' + id_tagihan, {
              method: 'GET'
            })
            setTimeout(function() {
              swal({
                title: 'Membuka Blokir',
                text: 'Berhasil Membuka!',
                type: 'success'
              }, function() {
                window.location = '?page=transaksi';
              });
            }, 300);
          }
        });
      });
    </script>

    <script>
      // Menangani klik tombol
      document.querySelectorAll(".tagihBtn").forEach(function(button) {
        button.addEventListener("click", function(event) {
          event.preventDefault();

          // Mengambil data dari atribut
          var id_tagihan = this.getAttribute("data-idpelanggan");
          var kode = this.getAttribute("data-kode");
          var ip_pelanggan = this.getAttribute("data-ip_pelanggan");
          var no_hp = this.getAttribute("data-nohp");
          var nama = this.getAttribute("data-nama");
          var tagihan = this.getAttribute("data-tagihan");
          var harinin = this.getAttribute("data-waktu");
          var jatuh_tempo = this.getAttribute("data-jatuhtempo");

          // Dapatkan teks dari textarea
          var notifTextarea = document.getElementById("notifTextarea");
          var notifText = `<?= $notif ?>`;

          // Ganti placeholder dalam teks yang diambil dari textarea dengan nilai dari variabel JavaScript yang sesuai
          notifText = notifText.replace('$nama', nama);
          notifText = notifText.replace('$tagihan', tagihan);
          notifText = notifText.replace('$no_telp', no_hp);
          notifText = notifText.replace('$harinin', harinin);
          notifText = notifText.replace('$jatuh_tempo', jatuh_tempo);
          notifText = notifText.replace('$kode', kode);

          // Data untuk dikirim ke API
          var postData = {
            target: no_hp,
            message: notifText,
            countryCode: '62'
          };

          // Check apakah authorizationToken ada
          var authorizationToken = '<?php echo $authorizationToken; ?>';
          var hasAuthorizationToken = authorizationToken && authorizationToken.trim() !== '';

          // Kirim pesan menggunakan API WhatsApp jika authorizationToken ada
          if (hasAuthorizationToken) {
            fetch('https://api.fonnte.com/send', {
                method: 'POST',
                headers: {
                  'Authorization': authorizationToken
                },
                body: new URLSearchParams(postData)
              })
              .then(response => response.json())
              .then(data => {
                console.log("Respons dari API:", data);
                if (data.status === true) {
                  fetch('page/transaksi/notif.php?id_tagihan=' + id_tagihan, {
                    method: 'GET'
                  })
                  setTimeout(function() {
                    swal({
                      title: 'Mengirim Notif',
                      text: 'Berhasil Mengirim Pesan',
                      type: 'success'
                    }, function() {
                      window.location = '?page=transaksi';
                    });
                  }, 300);
                } else {
                  Swal.fire({
                    title: 'Informasi Pesan',
                    text: 'Pesan gagal dikirim',
                    icon: 'error'
                  });
                }
              })
              .catch(error => {
                console.error("Terjadi kesalahan:", error);
              });
          } else {
            swal({
              title: 'Gagal Mengirim Pesan',
              text: 'Masukan Kode Token Dan Costum Pesan Terlebih Dahulu',
              type: 'error'
            }, function() {
              window.location = '?page=transaksi';
            });
          }
        });
      });
    </script>

    <script>
      var selectElement = document.getElementById("exampleFormControlSelect1");
      var keteranganElement = document.getElementById("keterangan");
      selectElement.addEventListener("change", function() {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var namaBank = selectedOption.getAttribute("data-nama-bank");
        var nomorRekening = selectedOption.getAttribute("data-nomor-rekening");
        var namaRekening = selectedOption.getAttribute("data-nama-rekening");
        if (selectedOption.value === "") {
          keteranganElement.innerHTML = "<strong>Pilih Bank terlebih dahulu.</strong>";
        } else {
          keteranganElement.innerHTML = "Nama Bank : <strong>" + namaBank + "</strong> <br>Nomor Rekening : <strong>" + nomorRekening + "</strong> <br> Atas Nama : <strong>" + namaRekening + "</strong><br> <i style='color:red;'>* Mohon Untuk Di Cek Kembali Nomor Rekeningnya, Jika Terjadi Kesalahan Transfer Admin Tidak Bertanggung Jawab *</i>";
        }
      });
    </script>

    <script>
      document.getElementById('refresh-button').addEventListener('click', function() {
        location.reload();
      });
    </script>

    <script>
      document.getElementById('paymentGatewayBtn').addEventListener('click', function() {
        var tagihan = this.getAttribute("data-tagihan");
        var namapelanggan = this.getAttribute("data-nama");
        window.location.href = 'payment-gateway.php?tagihan=' + tagihan + '&nama=' + namapelanggan;
      });
    </script>

    <script>
      function goBack() {
        window.history.back();
      }
    </script>

    <script>
      function printDocument() {
        window.print();
      }
    </script>

    <script>
      var levelSelect = document.getElementById('levelSelect');

      levelSelect.addEventListener('change', function() {

        var selectedValue = levelSelect.value;

        var additionalInputDiv = document.getElementById('additionalInput');

        if (selectedValue !== '') {

          additionalInputDiv.style.display = 'block';
        } else {

          additionalInputDiv.style.display = 'none';
        }
      });
    </script>

    <script>
      document.getElementById("paketPPP").addEventListener("change", function() {
        var selectedNama = this.options[this.selectedIndex].getAttribute('data-nama');
        document.getElementById("nama_paket").value = selectedNama;
      });
    </script>

    <script>
      document.getElementById("paketPPPu").addEventListener("change", function() {
        var selectedNama = this.options[this.selectedIndex].getAttribute('data-nama');
        document.getElementById("nama_paketu").value = selectedNama;
      });
    </script>

    <script>
      document.getElementById("getPMikrotik").addEventListener("change", function() {
        // var selectedNama = this.options[this.selectedIndex].getAttribute('data-nama');
        var selectedUsername = this.options[this.selectedIndex].getAttribute('data-username');
        var selectedPassword = this.options[this.selectedIndex].getAttribute('data-password');
        var selectedProfile = this.options[this.selectedIndex].getAttribute('data-profile');
        // document.getElementById("nama_pelanggan").value = selectedNama;
        document.getElementById("username").value = selectedUsername;
        document.getElementById("password").value = selectedPassword;
        updateProfileDropdown(selectedProfile);
      });

      function updateProfileDropdown(selectedProfile) {
        var profileDropdown = document.getElementById("profile");
        for (var i = 0; i < profileDropdown.options.length; i++) {
          if (profileDropdown.options[i].value == selectedProfile) {
            profileDropdown.selectedIndex = i;
            break;
          }
        }

        if (selectedProfile !== "") {
          var namaPaket = selectedProfile.split('-')[0].trim();
          for (var j = 0; j < profileDropdown.options.length; j++) {
            if (profileDropdown.options[j].text.indexOf(namaPaket) !== -1) {
              profileDropdown.selectedIndex = j;
              break;
            }
          }
        }
      }
    </script>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <?php if ($page == "mapping" || $page == "pelanggan") { ?>
      <script>
        var map = L.map('map').setView([0, 0], 13);

        // Tambahkan layer OpenStreetMap sebagai latar belakang
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan layer satelit (contoh menggunakan Google Maps)
        var satelliteLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
          subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
          attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a>'
        });

        // Tambahkan layer kontrol untuk beralih antara OpenStreetMap dan satelit
        var baseMaps = {
          "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          }),
          "Satellite": satelliteLayer
        };

        L.control.layers(baseMaps).addTo(map);

        var clickMarker = null;

        function onMapClick(e) {
          var coordinates = e.latlng;
          var lat = coordinates.lat.toFixed(6);
          var lng = coordinates.lng.toFixed(6);

          document.getElementById('coordinates').value = lat + ', ' + lng;

          if (clickMarker !== null) {
            map.removeLayer(clickMarker);
          }

          clickMarker = L.marker(coordinates).addTo(map)
            .bindPopup('Tandai').openPopup();
        }

        map.on('click', onMapClick);

        var origin = window.location.origin;

        // Load coordinates from the server
        fetch(`${origin}/page/pelanggan/get_coordinates.php`)
          .then(response => response.json())
          .then(data => {
            data.forEach(coord => {
              const marker = L.marker([coord.lat, coord.lng]).addTo(map)
                .bindPopup('Pelanggan: ' + coord.nama_pelanggan + '<br> Lokasi: ' + coord.alamat +
                  '<br><a href="https://www.google.com/maps?q=' + coord.lat + ',' + coord.lng + '" target="_blank">Buka di Google Maps</a>');
            });
          })
          .catch(error => console.error('Error fetching coordinates:', error));

        // Get user's location
        if ('geolocation' in navigator) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;

            map.setView([userLat, userLng], 13);

            L.marker([userLat, userLng]).addTo(map)
              .bindPopup('Lokasi Saya').openPopup();
          }, function(error) {
            console.error('Error getting user location:', error);
          }, {
            enableHighAccuracy: true
          });
        } else {
          console.error('Geolocation is not supported by your browser');
        }
      </script>
    <?php } ?>

    <?php if ($page == "backup") { ?>
      <script>
        // Get all elements with the class 'download-link'
        var downloadLinks = document.querySelectorAll('.download-link');

        // Add click event listener to each download link
        downloadLinks.forEach(function(link) {
          link.onclick = function() {
            // Extract the file name from the 'data-file' attribute
            var fileName = link.getAttribute('data-file');

            // Form the file path based on the file name
            var filePath = "backup_db/" + fileName;

            // Check if the file exists
            fetch(filePath)
              .then(response => {
                if (!response.ok) {
                  throw new Error('File not found for the specified date.');
                }
                return response.text();
              })
              .then(fileContent => {
                var blob = new Blob([fileContent], {
                  type: 'text/plain'
                });
                var downloadLink = document.createElement('a');

                downloadLink.href = window.URL.createObjectURL(blob);
                downloadLink.download = fileName;

                document.body.appendChild(downloadLink);

                downloadLink.click();

                document.body.removeChild(downloadLink);
              })
              .catch(error => {
                console.error(error.message);
                alert(error.message);
              });
          };
        });
      </script>
    <?php } ?>

    <script>
      $(document).ready(function() {
        $('#userProfile').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": "http://localhost/tagihan_internet/page/hotspot/user_profile/data-tables.php" // Sesuaikan dengan nama file PHP yang akan memproses data
        });
      });
    </script>

    <script>
      var map = L.map('titikodc').setView([0, 0], 13);

      // Tambahkan layer OpenStreetMap sebagai latar belakang
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      // Tambahkan layer satelit (contoh menggunakan Google Maps)
      var satelliteLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a>'
      });

      // Tambahkan layer kontrol untuk beralih antara OpenStreetMap dan satelit
      var baseMaps = {
        "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }),
        "Satellite": satelliteLayer
      };

      L.control.layers(baseMaps).addTo(map);

      var clickMarker = null;

      function onMapClick(e) {
        var coordinates = e.latlng;
        var lat = coordinates.lat.toFixed(6);
        var lng = coordinates.lng.toFixed(6);

        document.getElementById('odc').value = lat + ', ' + lng;

        if (clickMarker !== null) {
          map.removeLayer(clickMarker);
        }

        clickMarker = L.marker(coordinates).addTo(map)
          .bindPopup('Tandai').openPopup();
      }

      map.on('click', onMapClick);

      var origin = window.location.origin;

      // Load coordinates from the server
      fetch(`${origin}/page/odc/get_coordinates.php`)
        .then(response => response.json())
        .then(data => {
          data.forEach(coord => {
            const marker = L.marker([coord.lat, coord.lng]).addTo(map)
              .bindPopup('Nama ODC: ' + coord.nama_odc + '<br> Jumlah Port: ' + coord.port_odc + '<br> Nama Perangkat: ' + coord.perangkat_odc +
                '<br><a href="https://www.google.com/maps?q=' + coord.lat + ',' + coord.lng + '" target="_blank">Buka di Google Maps</a>');
          });
        })
        .catch(error => console.error('Error fetching coordinates:', error));

      // Get user's location
      if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var userLat = position.coords.latitude;
          var userLng = position.coords.longitude;

          map.setView([userLat, userLng], 13);

          L.marker([userLat, userLng]).addTo(map)
            .bindPopup('Lokasi Saya').openPopup();
        }, function(error) {
          console.error('Error getting user location:', error);
        }, {
          enableHighAccuracy: true
        });
      } else {
        console.error('Geolocation is not supported by your browser');
      }
    </script>

    <script>
      var map = L.map('titikodp').setView([0, 0], 13);

      // Tambahkan layer OpenStreetMap sebagai latar belakang
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      // Tambahkan layer satelit (contoh menggunakan Google Maps)
      var satelliteLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a>'
      });

      // Tambahkan layer kontrol untuk beralih antara OpenStreetMap dan satelit
      var baseMaps = {
        "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }),
        "Satellite": satelliteLayer
      };

      L.control.layers(baseMaps).addTo(map);

      var clickMarker = null;

      function onMapClick(e) {
        var coordinates = e.latlng;
        var lat = coordinates.lat.toFixed(6);
        var lng = coordinates.lng.toFixed(6);

        document.getElementById('odp').value = lat + ', ' + lng;

        if (clickMarker !== null) {
          map.removeLayer(clickMarker);
        }

        clickMarker = L.marker(coordinates).addTo(map)
          .bindPopup('Tandai').openPopup();
      }

      map.on('click', onMapClick);

      var origin = window.location.origin;

      // Load coordinates from the server
      fetch(`${origin}/page/odp/get_coordinates.php`)
        .then(response => response.json())
        .then(data => {
          data.forEach(coord => {
            const marker = L.marker([coord.lat, coord.lng]).addTo(map)
              .bindPopup('Nama ODP: ' + coord.nama_odp + '<br> Jumlah Port: ' + coord.port_odp +
                '<br><a href="https://www.google.com/maps?q=' + coord.lat + ',' + coord.lng + '" target="_blank">Buka di Google Maps</a>');
          });
        })
        .catch(error => console.error('Error fetching coordinates:', error));

      // Get user's location
      if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var userLat = position.coords.latitude;
          var userLng = position.coords.longitude;

          map.setView([userLat, userLng], 13);

          L.marker([userLat, userLng]).addTo(map)
            .bindPopup('Lokasi Saya').openPopup();
        }, function(error) {
          console.error('Error getting user location:', error);
        }, {
          enableHighAccuracy: true
        });
      } else {
        console.error('Geolocation is not supported by your browser');
      }
    </script>

  </body>

  </html>

<?php

} else {
  header("location:login.php");
}

?>