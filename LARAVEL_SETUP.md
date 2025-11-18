# SIPANDU DATA - Laravel Framework

Sistem Pemantauan dan Update Data Perencanaan yang telah dikonversi ke Laravel Framework.

## Konversi dari Vanilla JavaScript ke Laravel

Proyek ini telah berhasil dikonversi dari aplikasi vanilla JavaScript menjadi aplikasi Laravel Framework dengan fitur-fitur berikut:

### ✅ Apa yang Telah Dikonversi

1. **Backend Architecture**
   - Controllers untuk Auth, Dashboard, Data Management
   - API endpoints untuk Dinas dan Data Management
   - Eloquent Models dengan relationships

2. **Frontend Views**
   - Blade templates menggantikan HTML statis
   - Base layout dengan asset management
   - Dynamic routing dan named routes

3. **Database**
   - Migrations untuk Dinas, DataSubmission, Forum
   - Model relationships
   - Database seeders

4. **Assets**
   - CSS files dipindahkan ke `public/css/`
   - JavaScript files dipindahkan ke `public/js/`
   - Font Awesome dan Google Fonts CDN

## Setup & Installation

### Prerequisites
- PHP 8.1 atau lebih tinggi
- Composer
- Laravel 12
- MySQL atau MariaDB (via Laragon)

### Langkah-Langkah Instalasi

1. **Navigate ke directory project**
   ```bash
   cd c:\laragon\www\sipandu-data-laravel
   ```

2. **Konfigurasi environment (.env)**
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` file:
   ```
   APP_NAME="SIPANDU DATA"
   APP_DEBUG=true
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sipandu_data
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

4. **Jalankan Migrations**
   ```bash
   php artisan migrate
   ```

5. **Run Seeders (optional)**
   ```bash
   php artisan db:seed
   ```

6. **Mulai Development Server**
   ```bash
   php artisan serve
   ```

   Akses aplikasi di: `http://localhost:8000`

## Project Structure

```
sipandu-data-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── DataManagementController.php
│   │   │   └── API/
│   │   │       └── DinasController.php
│   │   └── Middleware/
│   └── Models/
│       ├── User.php
│       ├── Dinas.php
│       ├── DataSubmission.php
│       └── Forum.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   │   ├── DinasSeeder.php
│   │   └── UserSeeder.php
│   └── database.sqlite
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── index.blade.php
│   │   ├── login.blade.php
│   │   ├── dashboard.blade.php
│   │   ├── datamanagement.blade.php
│   │   ├── reports.blade.php
│   │   ├── calendar.blade.php
│   │   ├── forum.blade.php
│   │   ├── settings.blade.php
│   │   └── dinas-status.blade.php
│   └── css/ (symlinked to public/css/)
├── public/
│   ├── css/
│   │   ├── styles.css
│   │   ├── dashboard.css
│   │   └── mobile.css
│   ├── js/
│   │   ├── app.js
│   │   ├── utils.js
│   │   ├── charts.js
│   │   └── dashboard.js
│   └── index.php
└── routes/
    ├── web.php
    └── api.php
```

## Routes

### Web Routes (Authentication Required)
- `GET /` - Home page
- `GET /login` - Login page
- `POST /login` - Process login
- `POST /logout` - Process logout
- `GET /dashboard` - Main dashboard
- `GET /data-management` - Data management page
- `GET /reports` - Reports page
- `GET /calendar` - Calendar page
- `GET /forum` - Forum page
- `GET /settings` - Settings page
- `GET /dinas-status` - Dinas status page

### API Routes (Token Authentication)
- `GET /api/user` - Get current user
- `GET /api/dinas-status` - Get all dinas
- `GET /api/dinas/{id}` - Get specific dinas
- `POST /api/dinas` - Create new dinas

## Database Tables

### Users
Tabel untuk menyimpan data pengguna aplikasi

### Dinas
Tabel untuk menyimpan data dinas/lembaga

### DataSubmissions
Tabel untuk menyimpan data submission dari masing-masing dinas

### Forums
Tabel untuk menyimpan diskusi forum

## Authentication

Sistem menggunakan Laravel's built-in authentication dengan guard `web`.

### Demo Login Credentials
- **Super Admin**: admin@sipandu.id / password
- **Dinas Admin**: dinas@sipandu.id / password
- **User**: user@sipandu.id / password

(Update credentials di UserSeeder.php)

## Fitur-Fitur Utama

### 1. Dashboard
- Real-time monitoring data collection
- Statistics cards
- Progress charts
- Recent submissions

### 2. Data Management
- Upload dan validasi data
- Status tracking
- Pagination
- Search & filter

### 3. Reports & Analytics
- Chart.js integration
- Export functionality
- Various report types

### 4. Calendar
- Event scheduling
- Deadline tracking
- Notifications

### 5. Forum Diskusi
- Thread creation
- Comment system
- Category organization

### 6. Settings
- User profile management
- Password change
- Preferences

## API Documentation

### Get Dinas Status
```
GET /api/dinas-status
Authorization: Bearer {token}
```

Response:
```json
[
    {
        "id": 1,
        "nama": "Bappeda",
        "deskripsi": "Koordinator Utama",
        "status": "active"
    }
]
```

## Pengembangan Lebih Lanjut

### TODO Items
- [ ] Implementasi authorization policies
- [ ] Email notifications
- [ ] File upload dengan storage
- [ ] Advanced search/filtering
- [ ] Real-time notifications (WebSockets)
- [ ] Multi-language support
- [ ] Data validation rules
- [ ] Audit logging
- [ ] Rate limiting untuk API
- [ ] Mobile responsive optimization

## Troubleshooting

### Migration Error
```bash
php artisan migrate:fresh
php artisan migrate --seed
```

### Permission Issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database Connection
Pastikan MySQL service berjalan dan credentials di `.env` benar

## Support & Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Blade Templates](https://laravel.com/docs/blade)
- [Eloquent ORM](https://laravel.com/docs/eloquent)

## Lisensi

MIT License

## Pengembang

- **Original Developer**: H. Agus Salim, S.Pi
- **Organization**: Bappeda Kabupaten Kolaka Utara
- **Laravel Conversion**: 2025

---

Untuk bantuan atau pertanyaan, hubungi tim development Bappeda.
