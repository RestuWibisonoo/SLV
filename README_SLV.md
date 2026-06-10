# Dokumentasi Deployment Docker (Sistem dan Layanan Virtual)

Proyek **Sodakoh Pohon** kini dikonfigurasi untuk berjalan di atas ekosistem container (Docker) sesuai dengan standar Sistem dan Layanan Virtual (SLV).

## Topologi Deployment

Arsitektur aplikasi ini dipecah ke dalam 3 layanan (services) mandiri yang terisolasi dalam satu **Virtual Network (`sodakoh_network`)**:

1. **`webserver` (Nginx)**: 
   Bertindak sebagai web server utama dan Reverse Proxy. Menerima request HTTP dari pengguna di port `80` dan meneruskannya (FastCGI) ke service `app`.
2. **`app` (PHP-FPM)**: 
   Merupakan environment aplikasi PHP Native (Controller-Model). Menerima instruksi komputasi dari webserver melalui port internal `9000`. Ekstensi `mysqli` dan `pdo_mysql` sudah di-bundle langsung dalam image-nya.
3. **`db` (MariaDB/MySQL)**: 
   Merupakan server database yang aman dan terisolasi. Service ini mengekspos dirinya dalam network internal sehingga aplikasi bisa langsung menggunakan `DB_HOST=db` sebagai target koneksi.

## Persyaratan
- Docker
- Docker Compose

## Struktur File Infrastruktur
- `docker-compose.yml`: Mengatur orkestrasi 3 layanan (webserver, app, db).
- `Dockerfile`: Instruksi khusus untuk build image `app` (PHP 8.2 FPM).
- `nginx.conf`: Konfigurasi reverse proxy agar Nginx bisa meneruskan request ke PHP-FPM.
- `.env`: Variabel environment agar kredensial (database & URL) tidak ditulis secara hardcode.

## Panduan Menjalankan (Deployment)

1. **Pastikan port 80 tidak digunakan** oleh layanan lain (misalnya Apache/XAMPP).
2. Jalankan perintah berikut di root directory proyek ini:
   ```bash
   docker-compose up -d --build
   ```
3. Tunggu hingga proses pull image dan build selesai.
4. (Opsional) Jika database belum terbentuk, Anda bisa login ke dalam MariaDB container dan meng-import file SQL secara manual:
   ```bash
   docker exec -i sodakoh_db mysql -u root -psecret sodakoh_pohon < config/sodakoh_pohon.sql
   ```
   *Catatan: Pastikan mengubah password pada `.env` atau perintah di atas sesuai kebutuhan keamanan.*
5. Aplikasi sudah dapat diakses melalui browser di alamat:
   ```
   http://localhost
   ```

## Mengubah Konfigurasi
Jika Anda mengubah kode program PHP, perubahan tersebut akan langsung tercermin karena konfigurasi Docker Compose menggunakan **Volumes** yang memetakan folder kerja saat ini ke dalam folder `/var/www/html` di container. Anda tidak perlu mem-build ulang container kecuali terdapat perubahan pada `Dockerfile` atau package system level.
