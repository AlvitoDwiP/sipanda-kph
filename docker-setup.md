# Docker Setup — SIPANDA KPH

## Prerequisites
- Docker Engine
- Docker Compose

---

## Quick Start (Deploy Pertama Kali)

```bash
# 1. Pastikan file .env.docker sudah ada dan terkonfigurasi
cp .env.example .env.docker
# Edit .env.docker: isi DB_HOST=mysql, DB_DATABASE=kepegawaian_db, dll

# 2. Build dan jalankan semua container
docker compose up -d --build

# 3. Selesai!
# Startup script otomatis menjalankan: migrate + seed (jika DB kosong)
```

**Akses:**
| Service | URL |
|---|---|
| Aplikasi | http://localhost:8000 |
| phpMyAdmin | http://localhost:8085 |

**Akun default setelah seeding:**
| Role | Email | Password |
|---|---|---|
| Admin | `admin@sipanda.id` | `password123` |
| KPH | `kph@sipanda.id` | `password123` |
| Pegawai | `pegawai@sipanda.id` | `password123` |

> ⚠️ Ganti password setelah login pertama!

---

## Reset Database (Hapus semua data & seed ulang)

```bash
docker compose exec app php artisan migrate:fresh --seed --force
```

---

## Perintah Umum

```bash
# Masuk ke dalam container
docker compose exec app bash

# Jalankan artisan command apapun
docker compose exec app php artisan <command>

# Build frontend assets
docker compose exec app npm run build

# Lihat log aplikasi
docker compose logs -f app

# Stop semua container
docker compose down

# Stop dan hapus semua data (termasuk database)
docker compose down -v
```

---

## Troubleshooting

**Permission error pada storage:**
```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

**Asset frontend tidak muncul:**
```bash
docker compose exec app npm run build
```

**Build ulang dari awal:**
```bash
docker compose down -v
docker compose up -d --build
```
