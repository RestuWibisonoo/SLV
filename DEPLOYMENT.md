# Panduan Deployment Microservices ke Server Ubuntu (`slv.restuw.my.id`)

Karena arsitektur sudah berubah menjadi berbasis Docker, cara deployment-nya akan sedikit berbeda namun jauh lebih rapi dan konsisten. Berikut adalah langkah-langkah lengkapnya:

## 1. Persiapan Kode Lokal (Mac)
Pertama, pastikan semua perubahan lokal sudah tersimpan ke dalam Git dan di-*push* ke repository (GitHub/GitLab).

```bash
git add .
git commit -m "feat: refactor to docker microservices"
git push origin main
```
*(Catatan: Jika Anda tidak menggunakan Git, Anda bisa menggunakan perintah `rsync` atau `scp` untuk mentransfer folder `SLV` dari Mac ke server).*

---

## 2. Masuk ke Ubuntu Server
Buka terminal dan lakukan SSH ke server Anda:
```bash
ssh user_ubuntu@slv.restuw.my.id
```

---

## 3. Install Docker & Docker Compose (Jika Belum Ada)
Jika server belum terinstall Docker, jalankan perintah ini di server:
```bash
sudo apt update
sudo apt install docker.io docker-compose -y
sudo systemctl enable docker
sudo systemctl start docker
```

---

## 4. Penyesuaian Port (Sangat Penting!)
Secara default, konfigurasi `docker-compose.yml` kita mengikat port `80` (`"80:80"`). 
**TAPI**, jika server Ubuntu Anda **sudah memiliki Nginx/Apache** yang berjalan di port 80 untuk mengelola domain, akan terjadi **bentrok/conflict port**.

**Solusinya**: Ubah `docker-compose.yml` pada bagian `api-gateway` untuk menggunakan port internal, misalnya `8080`.

Buka file `docker-compose.yml` di server, ubah bagian port menjadi:
```yaml
  api-gateway:
    image: nginx:alpine
    ports:
      - "127.0.0.1:8080:80"  # Expose ke port 8080 di localhost server
```

---

## 5. Menjalankan Microservices di Server
Masuk ke direktori project yang sudah Anda pull/copy di server, lalu jalankan:

```bash
# 1. Matikan versi lama jika masih berjalan
docker-compose down -v

# 2. Build dan jalankan microservices versi baru di background
docker-compose up -d --build
```
Cek apakah sudah menyala dengan `docker ps`.

---

## 6. Konfigurasi Nginx di Server Host (Reverse Proxy & Domain)
Jika Anda mengubah port docker ke `8080` pada langkah 4, Anda perlu membuat Server Block (Virtual Host) di Nginx Ubuntu agar domain `slv.restuw.my.id` mengarah ke container Docker kita.

Buat file konfigurasi Nginx:
```bash
sudo nano /etc/nginx/sites-available/slv.restuw.my.id
```

Isi dengan konfigurasi berikut:
```nginx
server {
    listen 80;
    server_name slv.restuw.my.id;

    location / {
        proxy_pass http://127.0.0.1:8080; # Mengarah ke Nginx API Gateway Docker
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

Aktifkan konfigurasi dan restart Nginx Host:
```bash
sudo ln -s /etc/nginx/sites-available/slv.restuw.my.id /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 7. Setup SSL (HTTPS) dengan Let's Encrypt Certbot
Agar API aman (HTTPS), pasang SSL di server Ubuntu:

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d slv.restuw.my.id
```
Pilih opsi untuk *Redirect* semua trafik HTTP ke HTTPS saat diminta oleh Certbot.

---

## 8. Selesai! 🎉
Deployment Microservices Anda sudah selesai. Anda kini bisa mengaksesnya secara aman via:
`https://slv.restuw.my.id/api/campaign/list`
