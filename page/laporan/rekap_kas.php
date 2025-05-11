<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "../../include/koneksi.php";
$tgl_awal = $_POST['tgl_awal'];
$tgl_akhir = $_POST['tgl_akhir'];

$satu_hari        = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

function tglIndonesia2($str)
{
  $tr   = trim($str);
  $str    = str_replace(array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'), array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'), $tr);
  return $str;
}

?>

<style type="text/css">
  .tabel {
    border-collapse: collapse;
  }

  .tabel th {
    padding: 8px 5px;
    background-color: #cccccc;
  }

  .tabel td {
    padding: 8px 5px;
  }
</style>
<script>
  window.print();
  window.onfocus = function() {
    window.close();
  }
</script>
</head>

<body onload="window.print()">




  <table width="100%">
    <tr>
      <td width="0">&nbsp;</td>
      <td colspan="6">
        <div align="center"><strong>Laporan Kas Masuk dan Keluar <br> Tanggal <?php echo  tglIndonesia2(date('d F Y', strtotime($tgl_awal))) ?> - Tanggal <?php echo  tglIndonesia2(date('d F Y', strtotime($tgl_akhir))) ?> </strong></div>
      </td>
    </tr>


  </table><br>





  </table>
  <br>


  <table class="tabel" border="1" width="100%">

    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Kas Masuk</th>
        <th>Kas Keluar</th>


      </tr>
    </thead>
    <tbody>

      <?php

      $no = 1;

      $sql = $koneksi->query("select * from tb_kas where tgl_kas between '$tgl_awal' and '$tgl_akhir' order by id_kas desc");

      while ($data = $sql->fetch_assoc()) {

        $status = $data['status'];

        $t_masuk = $data['penerimaan'];
        $t_Keluar = $data['pengeluaran'];

        $total_masuk = $total_masuk + $t_masuk;
        $total_keluar = $total_keluar + $t_Keluar;
        $saldo = $total_masuk - $total_keluar;




      ?>


        <tr>
          <td><?php echo $no++; ?></td>
          <td><?php echo tglIndonesia2(date('d F Y', strtotime($data['tgl_kas']))) ?></td>
          <td><?php echo $data['keterangan'] ?></td>
          <td align="right"><?php echo number_format($data['penerimaan'], 0, ",", ".") ?></td>
          <td align="right"><?php echo number_format($data['pengeluaran'], 0, ",", ".") ?></td>


        </tr>



      <?php


      }

      ?>

      <tr>

        <td colspan="3" style="text-align: center; font-weight: bold; font-size: 16px">Total</td>
        <td align="right"><?php echo number_format($total_masuk, 0, ",", ".") ?></td>
        <td align="right"><?php echo number_format($total_keluar, 0, ",", ".") ?></td>

      </tr>

      <tr>

        <td colspan="3" style="text-align: center; font-weight: bold; font-size: 16px">Saldo</td>
        <td colspan="2" align="center"><?php echo number_format($saldo, 0, ",", ".") ?></td>


      </tr>



    </tbody>
  </table><br><br><br>

  <?php

  $sql2 = $koneksi->query("select * from tb_profile ");

  $data1 = $sql2->fetch_assoc();

  ?>