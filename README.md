# Aplikasi PPDB SMK Rohmatul Ummah

Aplikasi Penerimaan Peserta Didik Baru (PPDB) online untuk SMK Rohmatul Ummah yang dibuat dengan PHP Native, HTML, CSS, dan JavaScript.

## 📋 Fitur Aplikasi

### Untuk Calon Siswa:
- 📝 Pendaftaran online dengan form lengkap
- 🔍 Cek status pendaftaran dengan nomor pendaftaran
- 📊 Melihat informasi jurusan dan kuota
- 📢 Melihat pengumuman dari sekolah
- 📄 Cetak bukti pendaftaran

### Untuk Admin:
- 📈 Dashboard dengan statistik pendaftar
- 👥 Kelola data pendaftar (verifikasi, terima, tolak)
- 📚 Kelola data jurusan
- 📣 Kelola pengumuman
- ⚙️ Pengaturan aplikasi
- 📊 Filter dan cari data pendaftar
- 📤 Export data ke Excel
- 💬 Tambah catatan untuk pendaftar

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP Native (tanpa framework)
- **Frontend**: HTML5, CSS3, JavaScript
- **Database**: MySQL/MariaDB
- **Icons**: Font Awesome 6
- **Server**: Apache (XAMPP/WAMP/LAMP)

## 📦 Instalasi

### 1. Persiapan Environment

Pastikan Anda sudah menginstall:
- XAMPP/WAMP/LAMP (PHP 7.4+ dan MySQL/MariaDB)
- Web Browser (Chrome, Firefox, Edge, dll)

### 2. Clone atau Download Project

```bash
# Letakkan folder 'spmb' di dalam folder htdocs (XAMPP)
C:\xampp\htdocs\spmb
```

### 3. Setup Database

1. Buka **phpMyAdmin** di browser: `http://localhost/phpmyadmin`
2. Buat database baru dengan nama: `ppdb_smk_rohmatul_ummah`
3. Import file `database.sql` yang ada di folder project
   - Klik database yang baru dibuat
   - Pilih tab "Import"
   - Pilih file `database.sql`
   - Klik "Go"

### 4. Konfigurasi Aplikasi

Edit file `config.php` jika diperlukan (default sudah sesuai):

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Sesuaikan jika ada password
define('DB_NAME', 'ppdb_smk_rohmatul_ummah');
define('BASE_URL', 'http://localhost/spmb/');
```

### 5. Jalankan Aplikasi

1. Pastikan Apache dan MySQL di XAMPP sudah berjalan
2. Buka browser dan akses: `http://localhost/spmb/`

## 👤 Login Admin

Akun admin default:
- **Username**: `admin`
- **Password**: `admin123`

⚠️ **PENTING**: Segera ubah password default setelah login pertama kali!

## 📁 Struktur Folder

```
spmb/
├── admin/                  # Panel admin
│   ├── dashboard.php       # Dashboard admin
│   ├── pendaftar.php       # Kelola pendaftar
│   ├── detail_pendaftar.php # Detail pendaftar
│   ├── jurusan.php         # Kelola jurusan
│   ├── pengumuman.php      # Kelola pengumuman
│   ├── pengaturan.php      # Pengaturan sistem
│   ├── login.php           # Halaman login
│   ├── logout.php          # Proses logout
│   ├── header.php          # Header admin
│   └── footer.php          # Footer admin
├── css/
│   └── style.css           # File CSS utama
├── js/
│   └── script.js           # File JavaScript
├── uploads/                # Folder untuk upload file (buat manual)
├── config.php              # Konfigurasi database
├── database.sql            # File SQL database
├── index.php               # Halaman utama
├── pendaftaran.php         # Form pendaftaran
├── cek_status.php          # Cek status pendaftaran
└── README.md               # File ini
```

## 🚀 Cara Menggunakan

### Untuk Calon Siswa:

1. **Pendaftaran**:
   - Buka website PPDB
   - Klik menu "Daftar"
   - Isi formulir pendaftaran dengan lengkap
   - Submit formulir
   - Simpan nomor pendaftaran yang diberikan

2. **Cek Status**:
   - Klik menu "Cek Status"
   - Masukkan nomor pendaftaran
   - Lihat status pendaftaran Anda

### Untuk Admin:

1. **Login**:
   - Akses `http://localhost/spmb/admin/login.php`
   - Login dengan username dan password

2. **Kelola Pendaftar**:
   - Buka menu "Data Pendaftar"
   - Klik tombol mata untuk melihat detail
   - Ubah status (Pending → Verifikasi → Diterima/Ditolak)
   - Tambahkan catatan jika diperlukan

3. **Verifikasi Pendaftar**:
   - Filter berdasarkan status "Pending"
   - Review data pendaftar
   - Ubah status menjadi "Diterima" atau "Ditolak"

## 📊 Database Schema

### Tabel Utama:
- **admin**: Data administrator
- **jurusan**: Data program keahlian
- **pendaftar**: Data calon siswa
- **pengumuman**: Pengumuman PPDB
- **pengaturan**: Konfigurasi sistem

## 🎨 Fitur Tambahan

- ✅ Responsive design (mobile-friendly)
- ✅ Auto-calculate rata-rata nilai
- ✅ Filter dan pencarian data
- ✅ Export data ke Excel
- ✅ Print bukti pendaftaran
- ✅ Real-time status tracking
- ✅ Validasi form client-side dan server-side
- ✅ Clean dan modern UI/UX

## 🔒 Keamanan

Aplikasi ini menggunakan beberapa fitur keamanan dasar:
- Password di-hash dengan MD5 (disarankan upgrade ke bcrypt)
- Sanitasi input untuk mencegah SQL injection
- Session management untuk admin
- Validasi form

⚠️ **Catatan**: Untuk production, disarankan menambahkan:
- HTTPS/SSL
- CSRF protection
- Password hashing dengan bcrypt/argon2
- Rate limiting
- Input validation yang lebih ketat

## 🐛 Troubleshooting

### Database Connection Error
```
Solution: Pastikan MySQL berjalan dan konfigurasi di config.php sudah benar
```

### Page Not Found (404)
```
Solution: Pastikan BASE_URL di config.php sudah sesuai dengan lokasi folder
```

### Upload File Error
```
Solution: Buat folder 'uploads/' secara manual dan berikan permission 755
```

## 📝 Pengembangan Lebih Lanjut

Fitur yang bisa ditambahkan:
- [ ] Upload dokumen (foto, ijazah, KK)
- [ ] Notifikasi email/WhatsApp
- [ ] Payment gateway untuk biaya daftar ulang
- [ ] Cetak kartu ujian/pendaftaran
- [ ] Multi-role admin (super admin, operator)
- [ ] Backup database otomatis
- [ ] Captcha untuk keamanan
- [ ] History log aktivitas admin

## 👨‍💻 Developer

Aplikasi ini dibuat untuk SMK Rohmatul Ummah sebagai sistem PPDB online.

## 📄 Lisensi

Aplikasi ini dibuat untuk keperluan pendidikan dan dapat digunakan secara bebas dengan tetap mencantumkan sumber.

## 🙏 Kontribusi

Jika menemukan bug atau ingin menambahkan fitur, silakan:
1. Fork repository ini
2. Buat branch baru
3. Commit perubahan Anda
4. Push ke branch
5. Buat Pull Request

## 📞 Support

Jika ada pertanyaan atau masalah, silakan hubungi:
- Email: info@smkrohmatulummah.sch.id
- Telp: (021) 12345678

---

**Selamat menggunakan Aplikasi PPDB SMK Rohmatul Ummah! 🎓**
