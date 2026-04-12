# SIPANDA-KPH

SIPANDA-KPH merupakan aplikasi web berbasis Laravel yang dirancang untuk mendukung pengelolaan data kepegawaian, penugasan, dan pelaporan aktivitas kerja dalam lingkungan KPH. Sistem ini mengintegrasikan kebutuhan administrasi pengguna, pengelolaan data pegawai, monitoring penugasan, pencatatan kegiatan, serta pengawasan akses berbasis peran dalam satu platform terpusat.

## Overview

Proyek ini dibangun untuk menyediakan sistem informasi internal yang terstruktur bagi tiga kelompok pengguna utama, yaitu `admin`, `pegawai`, dan `kph`. Setiap peran memiliki akses terhadap dashboard dan modul yang berbeda sesuai tanggung jawabnya. Dengan pendekatan ini, proses administrasi kepegawaian dapat dilakukan secara lebih tertib, terdokumentasi, dan mudah dipantau.

Secara umum, aplikasi ini mencakup pengelolaan akun pengguna, data pegawai, riwayat kepegawaian, penugasan, catatan kegiatan, notifikasi, log aktivitas, serta fitur ekspor dokumen PDF untuk kebutuhan pelaporan.

## Problem

Pengelolaan data kepegawaian dan aktivitas kerja sering kali menghadapi beberapa kendala utama, antara lain:

- Data pegawai dan akun pengguna tersimpan secara terpisah atau belum terdokumentasi dengan baik.
- Proses verifikasi akun dan pengaturan hak akses belum terkontrol secara konsisten.
- Penugasan kerja dan pelaporan kegiatan pegawai sulit dipantau dalam satu alur yang terintegrasi.
- Pimpinan atau pihak pengawas memerlukan sarana monitoring yang lebih cepat dan akurat terhadap aktivitas pegawai.

SIPANDA-KPH dikembangkan untuk menjawab kebutuhan tersebut melalui satu sistem terpusat yang mampu mengelola data, proses, dan kontrol akses secara lebih sistematis.

## Approach

Pendekatan pengembangan aplikasi ini menggunakan arsitektur monolitik berbasis Laravel dengan pemisahan akses berdasarkan peran pengguna. Implementasi sistem difokuskan pada:

- pemisahan modul kerja untuk `admin`, `pegawai`, dan `kph`
- kontrol akses berbasis middleware agar setiap pengguna hanya dapat mengakses fitur sesuai perannya
- validasi status akun untuk memastikan hanya akun aktif yang dapat masuk ke dalam sistem
- penggunaan relasi data untuk menghubungkan pengguna, pegawai, data diri, riwayat kepegawaian, penugasan, dan catatan kegiatan
- dukungan lingkungan pengembangan berbasis Docker untuk mempermudah proses instalasi dan menjalankan layanan aplikasi

Pendekatan ini dipilih agar aplikasi tetap terstruktur, mudah dikembangkan, dan sesuai untuk kebutuhan sistem informasi internal organisasi.

## Tools

Teknologi dan tools utama yang digunakan dalam proyek ini meliputi:

- PHP 8.2
- Laravel 11
- Blade Template Engine
- Tailwind CSS
- Alpine.js
- Vite
- MySQL
- Redis
- Nginx
- Docker dan Docker Compose
- phpMyAdmin
- `barryvdh/laravel-dompdf` untuk ekspor PDF

## Output

Output utama dari pengembangan aplikasi ini adalah tersedianya sistem informasi kepegawaian yang memiliki kemampuan sebagai berikut:

- dashboard terpisah untuk `admin`, `pegawai`, dan `kph`
- manajemen akun pengguna dan verifikasi status akun
- pengelolaan data pegawai dan data kepegawaian
- pengelolaan referensi master seperti golongan, jabatan, dan unit kerja
- pencatatan dan monitoring penugasan kerja
- input, revisi, dan evaluasi catatan kegiatan pegawai
- direktori dan informasi data pegawai aktif
- notifikasi administratif
- pencatatan log aktivitas sistem
- ekspor dokumen PDF untuk kebutuhan laporan kegiatan

## Insights

Berdasarkan struktur kode dan konfigurasi yang ada, terdapat beberapa hal penting yang menjadi catatan dari proyek ini:

- Sistem sudah memiliki pemisahan peran yang jelas dan cukup representatif untuk kebutuhan operasional internal.
- Mekanisme login tidak hanya memeriksa kredensial pengguna, tetapi juga memastikan `status_akun` berada dalam kondisi aktif.
- Lingkungan pengembangan telah disiapkan untuk menggunakan Docker dengan layanan `app`, `nginx`, `mysql`, `redis`, dan `phpmyadmin`.
- Seeder awal telah menyediakan data akun dasar untuk kebutuhan pengujian awal peran sistem.
- Dokumentasi sebelumnya masih berupa README bawaan Laravel, sehingga perlu diperbarui agar mencerminkan identitas dan tujuan proyek secara formal.

## Struktur Peran Pengguna

### Admin

Admin bertanggung jawab terhadap pengelolaan pengguna, data pegawai, master data, penugasan, verifikasi akun, notifikasi, dan log aktivitas sistem.

### Pegawai

Pegawai memiliki akses untuk mengelola data diri, melihat data kepegawaian, memantau tugas yang diberikan, serta membuat dan memperbarui catatan kegiatan.

### KPH

KPH berperan dalam memantau data pegawai dan penugasan sebagai bagian dari fungsi pengawasan dan koordinasi.

## Menjalankan Dengan Docker

Project ini sudah disiapkan untuk dijalankan dengan Docker menggunakan layanan `app`, `nginx`, `mysql`, `redis`, dan `phpmyadmin`.

1. Jalankan container:
   ```bash
   docker compose up -d --build
   ```
2. Jalankan migrasi database:
   ```bash
   docker compose exec app php artisan migrate
   ```
3. Jika ingin mengisi data awal:
   ```bash
   docker compose exec app php artisan db:seed
   ```

Endpoint default:

- aplikasi: [http://localhost:8000](http://localhost:8000)
- phpMyAdmin: [http://localhost:8080](http://localhost:8080)
- MySQL: `localhost:3306`
- Redis: `localhost:6379`

Container Docker akan memakai konfigurasi dari `.env.docker`, sehingga koneksi ke MySQL dan Redis otomatis mengarah ke service Docker tanpa mengubah `.env` lokal Anda.


## Penutup

SIPANDA-KPH diharapkan menjadi fondasi sistem informasi kepegawaian yang lebih tertata, transparan, dan mudah dikembangkan. Dengan dokumentasi yang lebih jelas, proyek ini juga menjadi lebih siap untuk proses pengembangan lanjutan, evaluasi akademik, maupun presentasi formal.
