 <?php

  $sql2 = $koneksi->query("select * from tb_profile ");

  $data1 = $sql2->fetch_assoc();

  ?>

 <aside class="main-sidebar">
   <!-- sidebar: style can be found in sidebar.less -->
   <!-- <section class="sidebar"> -->
   <section class="sidebar" style="max-height: 85vh; overflow-y: auto;">
     <div class="user-panel">
       <div>
         <img style="margin-left:30px" src="images/<?php echo $data1['foto'] ?>" width="140" height="120">
       </div>
       <div class="pull-left info">
       </div>
     </div>
     <!-- search form -->
     <!-- /.search form -->
     <!-- sidebar menu: : style can be found in sidebar.less -->

     <?php
      switch ($_GET['page']) {

        case 'pengguna':

          $aktifAdmin = 'active';
          break;

        case 'profile':

          $aktifprofile = 'active';
          break;

        case 'monitoring':

          $aktifMikrotik = 'active';
          $aktifmonitoring = 'active';
          break;

        case 'pppoe':

          $aktifMikrotik = 'active';
          $aktifpppoe = 'active';
          break;

        case 'simplequeue':

          $aktifMikrotik = 'active';
          $aktifsimplequeue = 'active';
          break;

        case 'activeclient':
          $aktifMikrotik = 'active';
          $aktifclient = 'active';
          break;

        case 'pppoeprofile':
          $aktifMikrotik = 'active';
          $aktifpppoeprofile = 'active';
          break;

        case 'pengumuman':
          $aktifPengumuman = 'active';
          break;

        case 'costumpesan':
          $costumpesan = 'active';
          break;

        case 'mapping':
          $mapping = 'active';
          break;

        case 'odc':
          $odc = 'active';
          break;

        case 'odp':
          $odp = 'active';
          break;

        case 'kelola_keluhan':
          $keluhan = 'active';
          break;

        case 'kelola_bank':
          $rekening = 'active';
          break;

        case 'paket':

          $aktifA = 'active';
          $aktifA2 = 'active';
          break;

        case 'perangkat':

          $aktifA = 'active';
          $aktifA3 = 'active';
          break;

        case 'pelanggan':

          $aktifA = 'active';
          $aktifA4 = 'active';
          break;

        case 'kenaikan':

          $aktifA = 'active';
          $aktifA5 = 'active';
          break;

        case 'kelulusan':

          $aktifA = 'active';
          $aktifA6 = 'active';
          break;

        case 'jenisbayar':

          $aktifB = 'active';
          $aktifB1 = 'active';
          break;

        case 'kas':

          $aktifB = 'active';
          $aktifB2 = 'active';
          break;

        case 'kas2':
          $aktifB6 = 'active';
          break;

        case 'laporan_tagihan_siswa':

          $aktifC = 'active';
          $aktifC1 = 'active';
          break;

        case 'laporan_data_siswa':

          $aktifC = 'active';
          $aktifC2 = 'active';
          break;

        case 'users-hotspot':
          $aktifHotspot = 'active';
          $aktifusersHotspot = 'active';
          break;

        case 'users-profile':
          $aktifHotspot = 'active';
          $aktifUserProfile = 'active';
          break;

        case 'host-hotspot':
          $aktifHotspot = 'active';
          $aktifHost = 'active';
          break;

        case 'monitoring-hotspot':
          $aktifHotspot = 'active';
          $aktifMonitoringHotspot = 'active';
          break;

          //menu home
        default:

          $aktifHome = 'active';
      }
      ?>

     <ul class="sidebar-menu" data-widget="tree">
       <li class="header">MAIN NAVIGATION</li>

       <?php if ($_SESSION['admin'] || $_SESSION['teknisi']) { ?>
         <li class="<?php echo $aktifHome; ?>"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
       <?php } ?>

       <?php if ($_SESSION['admin']) { ?>
         <li class="<?php echo $aktifprofile; ?>"><a href="?page=profile"><i class="fa fa-gear"></i> Pengaturan </a></li>
       <?php  } ?>

       <?php if ($_SESSION['admin'] || $_SESSION['teknisi']) { ?>
         <li class="treeview <?php echo $aktifMikrotik; ?>">
           <a href="#">
             <i class="fa fa-list-alt"></i> <span>Mikrotik</span>
             <span class="pull-right-container">
               <i class="fa fa-angle-left pull-right"></i>
             </span>
           </a>
           <ul class="treeview-menu">
             <li class="<?php echo $aktifmonitoring; ?>"><a href="?page=monitoring"><i class="fa fa-circle-o"></i> Monitoring </a></li>
             <li class="<?php echo $aktifpppoeprofile; ?>"><a href="?page=pppoeprofile"> <i class="fa fa-circle-o"></i> PPPOE Profile </a></li>
             <li class="<?php echo $aktifpppoe; ?>"><a href="?page=pppoe"><i class="fa fa-circle-o"></i> PPPOE Secret </a></li>
             <li class="<?php echo $aktifsimplequeue; ?>"><a href="?page=simplequeue"><i class="fa fa-circle-o"></i> Simple Queue </a></li>
             <li class="<?php echo $aktifclient; ?>"><a href="?page=activeclient"> <i class="fa fa-circle-o"></i> Client Active </a></li>
           </ul>
         </li>
         <!-- 
         <li class="treeview <?php echo $aktifHotspot; ?>">
           <a href="#">
             <i class="fa fa-list-alt"></i> <span>Hotspot</span>
             <span class="pull-right-container">
               <i class="fa fa-angle-left pull-right"></i>
             </span>
           </a>
           <ul class="treeview-menu">
             <li class="<?php echo $aktifMonitoringHotspot; ?>"><a href="?page=monitoring-hotspot"> <i class="fa fa-circle-o"></i> Monitoring Hotspot </a></li>
             <li class="<?php echo $aktifusersHotspot; ?>"><a href="?page=users-hotspot"><i class="fa fa-circle-o"></i> Users Hotspot </a></li>
             <li class="<?php echo $aktifUserProfile; ?>"><a href="?page=users-profile"><i class="fa fa-circle-o"></i> Users Profile </a></li>
             <li class="<?php echo $aktifHost; ?>"><a href="?page=host-hotspot"><i class="fa fa-circle-o"></i> Host </a></li>
             <li class="<?php echo $aktifsimplequeue; ?>"><a href="?page=simplequeue"><i class="fa fa-circle-o"></i> Simple Queue </a></li>
             <li class="<?php echo $aktifclient; ?>"><a href="?page=activeclient"> <i class="fa fa-circle-o"></i> Client Active </a></li>
           </ul>
         </li> -->

       <?php } ?>

       <?php if ($_SESSION['admin']) { ?>
         <!-- <li class="<?php echo $waweb; ?>"><a href="?page=waweb"><i class="fa fa-file-text"></i> Whatsapp Web</a></li> -->
         <li class="<?php echo $costumpesan; ?>"><a href="?page=costumpesan"><i class="fa fa-file-text"></i> Costum Pesan</a></li>
         <li class="<?php echo $aktifAdmin; ?>"><a href="?page=pengguna"><i class="fa fa-users"></i> pengguna</a></li>
         <li class="<?php echo $aktifPengumuman; ?>"><a href="?page=pengumuman"><i class="fa fa-volume-up"></i> Pengumuman</a></li>
       <?php } ?>

       <?php if ($_SESSION['admin'] || $_SESSION['teknisi']) { ?>
         <li class="<?php echo $keluhan; ?>"><a href="?page=kelola_keluhan"><i class="fa fa-file-text"></i>Lihat Keluhan</a></li>
       <?php } ?>

       <?php if ($_SESSION['admin']) { ?>
         <li class="<?php echo $rekening; ?>"><a href="?page=kelola_bank"><i class="fa fa-university" aria-hidden="true"></i>Payment Gateway</a></li>

         <?php
          $conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
          $checkUser = $conPelanggan->fetch_assoc();

          if ($checkUser['mapping'] == 'aktif') {
          ?>
           <li class="<?php echo $mapping; ?>"><a href="?page=mapping"> <i class="fa fa-map-marker" aria-hidden="true"></i>Lokasi Pelanggan</a></li>
           <li class="<?php echo $odp; ?>"><a href="?page=odp"> <i class="fa fa-map-marker" aria-hidden="true"></i>Lokasi ODP</a></li>
           <li class="<?php echo $odc; ?>"><a href="?page=odc"> <i class="fa fa-map-marker" aria-hidden="true"></i>Lokasi ODC</a></li>
         <?php
          }
          ?>
       <?php } ?>

       <?php if ($_SESSION['admin'] || $_SESSION['teknisi']) { ?>
         <li class="treeview <?php echo $aktifA; ?>">
           <a href="#">
             <i class="fa fa-list-alt"></i> <span>Data Master</span>
             <span class="pull-right-container">
               <i class="fa fa-angle-left pull-right"></i>

             </span>
           </a>
           <ul class="treeview-menu">
             <!-- <li class="<?php echo $aktifA3; ?>"><a href="?page=perangkat"> <i class="fa fa-circle-o"></i> Costum ID Pelanggan</a></li> -->
             <li class="<?php echo $aktifA3; ?>"><a href="?page=perangkat"> <i class="fa fa-circle-o"></i> Perangkat</a></li>
             <?php if ($_SESSION['admin']) { ?>
               <li class="<?php echo $aktifA2; ?>"><a href="?page=paket"> <i class="fa fa-circle-o"></i> Data Paket</a></li>
             <?php } ?>
             <li class="<?php echo $aktifA4; ?>"><a href="?page=pelanggan"> <i class="fa fa-circle-o"></i> Data Pelanggan</a></li>
           </ul>
         </li>
       <?php } ?>

       <?php if ($_SESSION['admin'] || $_SESSION['kasir']) { ?>
         <li><a href="?page=transaksi"><i class="fa fa-money"></i> Transaksi Pembayaran</a></li>
       <?php } ?>

       <?php if ($_SESSION['admin']) { ?>
         <li class="<?php echo $aktifB2; ?>"><a href="?page=kas"><i class="fa fa-exchange"></i> Kas Masuk dan Keluar</a></li>
       <?php } ?>

       <?php if ($_SESSION['admin']) { ?>
         <li><a href="?page=laporan"><i class="fa fa-print"></i> Laporan Kas Masuk dan Keluar </a></li>
         <li><a href="?page=backup"><i class="fa fa-hdd-o"></i> Cadangkan Database </a></li>
       <?php } ?>

       <?php if ($_SESSION['user']) { ?>
         <li><a href="index.php"><i class="fa fa-money"></i> Data Tagihan</a></li>
         <li><a href="?page=keluhan"><i class="fa fa-file-text-o" aria-hidden="true"></i> Keluhan</a></li>
         <!-- <li class="<?php echo $aktifB6; ?>"><a href="?page=kas2"><i class="fa fa-exchange"></i> Kas Masuk dan Keluar</a></li> -->
       <?php } ?>

     </ul>
   </section>
   <!-- /.sidebar -->
 </aside>


 <!-- =============================================== -->

 <!-- Content Wrapper. Contains page content -->