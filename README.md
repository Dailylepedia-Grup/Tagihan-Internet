* CARA INSTALL 
SALIN PROGRAM BERBENTUK ZIP KE DALAM HOSTING LALU EXTRAK SETELAH ITU UBAH DATABASE DI include/koneksi
SESUAIKAN DENGAN DATABASE YANG SUDAH DIBUAT

~ WHATSAPP API 
  Gunakan Layanan dari fonte (Sangat Disarankan) Silakan 
  Lihat Dokumentasi di bagian admin https://fonnte.com/

~ Fitur Otomatis 
  Aktifkan Cronjob pada Linux (Local).
  Gunakan Cronjob Pada Layanan Hosting. 

  Contoh Penggunaan Di Hosting Direct Admin Bukan Cpanel 
  php /home/paynowbi/domains/demo.paynow.com/public_html/cron_notifikasi.php 
  php /home/paynowbi/domains/demo.paynow.com/public_html/cron_blokir.php

  Fitur Otomatis akan Berjalan Ketika Pelanggan Sudah Berada di Waktu Jatuh Tempo nya.

~ DATABASE 
  Silakan Import DATABASE yang sudah disediakan di dalam folder database ke PhpMyAdmin.
  Sesuaikan Koneksi Database dengan Program berada di folder (include->koneksi).

~ PAYMENT GATEWAY 
  Pastikan untuk URL Midtrans di isi https://namadomain.com/payment/notification-handler.php
  
  Login Midtrans -> Pengaturan -> Konfigurasi Isikan URL Midtrans Untuk Callback
  ~ Payment Notification URL*
  ~ Recurring Notification URL*
  ~ Pay Account Notification URL*
  ~ Finish Redirect URL* (isikan Domain saja)

  Masuk Lagi ke Pengaturan -> Snap Preferences -> System Settings -> Scroll Paling Bawah 
  ~ Redirection Settings Finish URL (isikan Domain) 
  ~ Unfinish URL (Isikan Domain)
  ~ Error Payment URL (Isikan Domain)

~ Jika Statik Tambahkan Konfigurasi Berikut di Mikrotik
  /ip firewall filter
  add action=drop chain=forward comment="Blocked due to unpaid" \
  dst-address-list=blocked_clients
  
