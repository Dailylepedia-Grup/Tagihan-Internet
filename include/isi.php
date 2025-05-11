<?php

$page = $_GET['page'];
$aksi = $_GET['aksi'];

if ($page == "paket") {

	if ($aksi == "") {
		include "page/paket/paket.php";
	}

	if ($aksi == "hapus") {
		include "page/paket/hapus.php";
	}

	if ($aksi == "tambah_paket") {
		include "page/paket/tambah_paket.php";
	}

	if ($aksi == "ubah_paket") {
		include "page/paket/ubah_paket.php";
	}
}

if ($page == "mapping") {
	if ($aksi == "") {
		include "page/mapping/mapping.php";
	}
}

if ($page == "odc") {
	if ($aksi == "") {
		include "page/odc/odc.php";
	}
	if ($aksi == "listodc") {
		include "page/odc/listodc.php";
	}
	if ($aksi == "tambahodc") {
		include "page/odc/tambahodc.php";
	}
	if ($aksi == "hapus") {
		include "page/odc/hapus.php";
	}
	if ($aksi == "ubahodc") {
		include "page/odc/ubahodc.php";
	}
}

if ($page == "odp") {
	if ($aksi == "") {
		include "page/odp/odp.php";
	}
	if ($aksi == "listodp") {
		include "page/odp/listodp.php";
	}
	if ($aksi == "tambahodp") {
		include "page/odp/tambahodp.php";
	}
	if ($aksi == "hapus") {
		include "page/odp/hapus.php";
	}
	if ($aksi == "ubahodp") {
		include "page/odp/ubahodp.php";
	}
}

if ($page == "monitoring") {
	if ($aksi == "") {
		include "page/monitoring/monitoring.php";
	}
}

if ($page == "tambahsimplequeue") {
	if ($aksi == "") {
		include "page/monitoring/tambahsimplequeue.php";
	}
}

if ($page == "ubahsimplequeue") {
	if ($aksi == "") {
		include "page/monitoring/ubahsimplequeue.php";
	}
}

if ($page == "tambahpppoesecret") {
	if ($aksi == "") {
		include "page/monitoring/tambahpppoesecret.php";
	}
}

if ($page == "ubahpppoesecrect") {
	if ($aksi == "") {
		include "page/monitoring/ubahpppoesecrect.php";
	}
}

if ($page == "pppoeprofile") {
	if ($aksi == "") {
		include "page/monitoring/pppoeprofile/profileppp.php";
	}
	if ($aksi == "tambah_ppprofile") {
		include "page/monitoring/pppoeprofile/tambah_ppprofile.php";
	}
	if ($aksi == "hapuspppprofile") {
		include "page/monitoring/pppoeprofile/hapuspppprofile.php";
	}
	if ($aksi == "ubah_pprofile") {
		include "page/monitoring/pppoeprofile/ubah_pprofile.php";
	}
}



if ($page == "pppoe") {
	if ($aksi == "") {
		include "page/monitoring/pppoe.php";
	}

	if ($aksi == "hapus") {
		include "page/monitoring/hapus_ppp.php";
	}
}

if ($page == "simplequeue") {
	if ($aksi == "") {
		include "page/monitoring/simplequeue.php";
	}

	if ($aksi == "hapus") {
		include "page/monitoring/hapus_simplequeue.php";
	}
}

if ($page == "costumpesan") {
	include "page/costumpesan/costumpesan.php";
}

if ($page == "kelola_bank") {
	if ($aksi == "") {
		include "page/kelola_bank/kelola_bank.php";
	}

	if ($aksi == "hapus") {
		include "page/kelola_bank/hapus.php";
	}
}

if ($page == "kelola_keluhan") {
	if ($aksi == "") {
		include "page/kelola_keluhan/kelola_keluhan.php";
	}

	if ($aksi == "proses") {
		include "page/kelola_keluhan/proses.php";
	}

	if ($aksi == "selesai") {
		include "page/kelola_keluhan/selesai.php";
	}
}

if ($page == "pengumuman") {
	include "page/pengumuman/pengumuman.php";
}


if ($page == "laporan") {

	if ($aksi == "") {
		include "page/laporan/laporan.php";
	}

	if ($aksi == "cetak_riwayat") {
		include "page/laporan/cetak_riwayat.php";
	}
}

if ($page == "backup") {
	if ($aksi == "") {
		include "page/backup/backup.php";
	}

	if ($aksi == "now") {
		include "page/backup/now.php";
	}

	if ($aksi == "download") {
		include "page/backup/download.php";
	}

	if ($aksi == "hapus") {
		include "page/backup/hapus.php";
	}
}

if ($page == "profile") {
	if ($aksi == "") {
		include "page/profile/profile.php";
	}
	if ($aksi == "hapus_mikrotik") {
		include "page/profile/hapus_mikrotik.php";
	}
	if ($aksi == "hapus_waapi") {
		include "page/profile/hapus_wawapi.php";
	}
}

if ($page == "users-hotspot") {
	if ($aksi == "") {
		include "page/hotspot/users_hotspot/users_hotspot.php";
	}

	if ($aksi == "add_users") {
		include "page/hotspot/users_hotspot/add_users.php";
	}
}

if ($page == "users-profile") {
	if ($aksi == "") {
		include "page/hotspot/users_profile/users_profile.php";
	}

	if ($aksi == "add_profile") {
		include "page/hotspot/users_profile/add_profile.php";
	}

	if ($aksi == "edit_profile") {
		include "page/hotspot/users_profile/edit_profile.php";
	}

	if ($aksi == "delete_profile") {
		include "page/hotspot/users_profile/delete_profile.php";
	}
}

if ($page == "laporan_tagihan_siswa") {
	if ($aksi == "") {
		include "page/laporan/laporan_tagihan_siswa.php";
	}
}

if ($page == "laporan_data_siswa") {
	if ($aksi == "") {
		include "page/laporan/laporan_data_siswa.php";
	}
}

if ($page == "pelanggan") {

	if ($aksi == "") {
		include "page/pelanggan/pelanggan.php";
	}

	if ($aksi == "hapus") {
		include "page/pelanggan/hapus.php";
	}

	if ($aksi == "tambah_pelanggan") {
		include "page/pelanggan/tambah_pelanggan.php";
	}

	if ($aksi == "ubah_pelanggan") {
		include "page/pelanggan/ubah_pelanggan.php";
	}

	if ($aksi == "cetak_pelanggan") {
		include "page/pelanggan/cetak_pelanggan.php";
	}

	if ($aksi == "export_excel") {
		include "page/pelanggan/export_excel.php";
	}

	if ($aksi == "get_coordinates") {
		include "page/pelanggan/get_coordinates.php";
	}
}

if ($page == "perangkat") {

	if ($aksi == "") {
		include "page/perangkat/perangkat.php";
	}

	if ($aksi == "tambah_perangkat") {
		include "page/perangkat/tambah_perangkat.php";
	}

	if ($aksi == "ubah_perangkat") {
		include "page/perangkat/ubah_perangkat.php";
	}

	if ($aksi == "hapus") {
		include "page/perangkat/hapus.php";
	}
}

if ($page == "activeclient") {
	if ($aksi == "") {
		include "page/activeclient/activeclient.php";
	}
}

if ($page == "dokumentasi") {
	if ($aksi == "") {
		include "page/dokumentasi/dokumentasi.php";
	}
}

if ($page == "order") {
	if ($aksi == "") {
		include "page/order/order.php";
	}
}

if ($page == "jenisbayar") {
	if ($aksi == "") {
		include "page/jenisbayar/jenisbayar.php";
	}

	if ($aksi == "tambah") {
		include "page/jenisbayar/tambah.php";
	}

	if ($aksi == "hapus") {
		include "page/jenisbayar/hapus.php";
	}

	if ($aksi == "ubahbulanan") {
		include "page/jenisbayar/ubah_bulanan.php";
	}

	if ($aksi == "hapusbulanan") {
		include "page/jenisbayar/hapusbayar.php";
	}

	if ($aksi == "seting") {
		include "page/jenisbayar/bulanan.php";
	}

	if ($aksi == "ubahbebas") {
		include "page/jenisbayar/ubah_bebas.php";
	}

	if ($aksi == "hapusbebas") {
		include "page/jenisbayar/hapus_bebas.php";
	}
}


if ($page == "ubah_p") {
	if ($aksi == "") {
		include "ubah_password.php";
	}
}


if ($page == "transaksi") {
	if ($aksi == "") {
		include "page/transaksi/transaksi.php";
	}

	if ($aksi == "lihat") {
		include "page/transaksi/lihat.php";
	}

	if ($aksi == "tambah") {
		include "page/transaksi/tambah.php";
	}

	if ($aksi == "bayar") {
		include "page/transaksi/bayar.php";
	}

	if ($aksi == "hapus") {
		include "page/transaksi/batal.php";
	}

	if ($aksi == "hapuss") {
		include "page/transaksi/hapus.php";
	}

	if ($aksi == "blokir") {
		include "page/transaksi/blokir.php";
	}
	if ($aksi == "hapus_blokir") {
		include "page/transaksi/hapus_blokir.php";
	}
	if ($aksi == "notif") {
		include "page/transaksi/notif.php";
	}
}


if ($page == "pengguna") {
	if ($aksi == "") {
		include "page/pengguna/pengguna.php";
	}

	if ($aksi == "tambah") {
		include "page/pengguna/tambah.php";
	}

	if ($aksi == "ubah") {
		include "page/pengguna/ubah.php";
	}

	if ($aksi == "hapus") {
		include "page/pengguna/hapus.php";
	}
}

if ($page == "kas") {
	if ($aksi == "") {
		include "page/kas/kas.php";
	}
}

if ($page == "kas2") {
	if ($aksi == "") {
		include "page/kas2/kas2.php";
	}
}


if ($page == "keluhan") {
	if ($aksi == "") {
		include "page/keluhan/keluhan.php";
	}
}

if ($page == "monitoring-hotspot") {
	if ($aksi == "") {
		include "page/hotspot/monitoring/monitoring.php";
	}
	if ($aksi == "details-active") {
		include "page/hotspot/monitoring/details-active.php";
	}
}

if ($page == "host-hotspot") {
	if ($aksi == "") {
		include "page/hotspot/host/host.php";
	}
}

if ($page == "") {
	include "home.php";
}
