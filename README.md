# Kantin 40

Aplikasi kantin digital berbasis Laravel 12 dengan tiga jenis akun: Admin, Warung, dan User.

---

## Instalasi

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

---

## Kredensial Default

### Admin
| Field    | Value              |
|----------|--------------------|
| Email    | admin@ecanteen.com |
| Password | password           |

### User (Siswa)
| Nama         | Email              | Password | Saldo Awal |
|--------------|--------------------|----------|------------|
| Budi Santoso | budi@ecanteen.com  | password | Rp 50.000  |
| Siti Rahayu  | siti@ecanteen.com  | password | Rp 75.000  |
| Ahmad Fauzi  | ahmad@ecanteen.com | password | Rp 100.000 |
| Dewi Lestari | dewi@ecanteen.com  | password | Rp 30.000  |

### Warung
Akun warung dibuat oleh Admin melalui panel **Admin → Kelola Warung → Tambah Warung**.
Setelah dibuat, pemilik warung login dengan email & password yang disetel admin.

---

## Role & Panel

| Role   | URL Panel           | Akses                                                      |
|--------|---------------------|------------------------------------------------------------|
| admin  | `/admin/dashboard`  | Manajemen menu, pesanan, user, laporan, kelola warung      |
| warung | `/warung/dashboard` | Manajemen menu warung, antrean pesanan, laporan pendapatan |
| user   | `/home`             | Pesan makanan, keranjang, riwayat pesanan, top-up saldo    |

---

## Stack

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Blade, Tailwind CSS v4, Vite, React (menu grid)
- **Database**: MySQL (XAMPP)
- **Icons**: Iconify (Solar icon set)
- **Charts**: Chart.js
