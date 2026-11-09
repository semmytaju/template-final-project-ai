# Template Proyek Akhir Mahasiswa (PHP MVC)

Template starter kit berbasis **PHP Native MVC** yang sederhana, bersih, dan mudah dipahami. Dibuat khusus sebagai fondasi awal bagi mahasiswa dalam mengerjakan **Tugas Akhir**, **Skripsi**, atau **Proyek Mata Kuliah**.

---

## 🚀 3 Langkah Cepat Menjalankan

1. **Letakkan Proyek**:
   Salin folder proyek ini ke dalam direktori XAMPP:
   ```text
   C:\xampp\htdocs\template-final-project-ai
   ```

2. **Nyalakan XAMPP**:
   Buka **XAMPP Control Panel**, lalu klik **Start** pada:
   - ✅ **Apache**
   - ✅ **MySQL**

3. **Buka di Browser**:
   Buka peramban dan akses:
   👉 **`http://localhost/template-final-project-ai/`**

---

## 🗄️ Inisialisasi Database (`db_project`)

Nama database default adalah **`db_project`**. File skema SQL tersedia di: `database/db_project.sql`.

Anda dapat menginisialisasi database dengan salah satu cara:
- **Cara Otomatis (Direkomendasikan)**: Buka halaman utama di browser. Jika database belum terhubung, klik tombol **"Buat Database & Tabel Otomatis"**.
- **Cara Manual**: Buka `http://localhost/phpmyadmin`, buat database `db_project`, lalu import file `database/db_project.sql`.

### Konfigurasi Database (`app/config/config.php`):
```php
define("HOST_DB", "localhost");
define("NAME_DB", "db_project");
define("USER_DB", "root");
define("PASS_DB", ""); // default XAMPP kosong ""
```

---

## 🔑 Akun Login Demo

| Role | Username | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `admin123` | Akses penuh dashboard, kelola mahasiswa, log aktivitas |
| **Mahasiswa** | `mahasiswa` | `mahasiswa123` | Akun pengguna mahasiswa |
| **Dosen** | `dosen` | `dosen123` | Akun pengguna dosen |

Halaman Login: **`http://localhost/template-final-project-ai/login/`**

---

## 📁 Struktur Folder Sederhana

```text
template-final-project-ai/
├── app/
│   ├── config/config.php     # Pengaturan database & URL
│   ├── controller/           # Logika aplikasi (contoh: mahasiswa.php)
│   ├── core/                 # Router (App.php), Controller, Database
│   ├── model/                # Query database (contoh: Mahasiswa_model.php)
│   └── view/                 # Tampilan antarmuka HTML/PHP
├── database/
│   └── db_project.sql        # Skema tabel default
├── public/                   # CSS, JS, Gambar, & Uploads
├── .htaccess                 # Routing URL bersih
├── index.php                 # Pintu masuk utama aplikasi
└── README.md                 # Petunjuk penggunaan
```

---

## 💡 Contoh Cara Membuat Fitur Baru

Misalkan Anda ingin membuat modul **Buku**:

1. **Buat Model** di `app/model/Buku_model.php`:
   ```php
   <?php
   class Buku_model {
       private $db;
       public function __construct() {
           $this->db = new Database();
       }
       public function getAll() {
           $this->db->query("SELECT * FROM buku");
           return $this->db->resultSet();
       }
   }
   ```

2. **Buat Controller** di `app/controller/buku.php`:
   ```php
   <?php
   class buku extends Controller {
       public function index() {
           $model = $this->logic('Buku_model');
           $data = ['buku' => $model->getAll()];
           $this->display('buku/index', $data);
       }
   }
   ```

3. **Buat View** di `app/view/buku/index.php`:
   ```php
   <?php require_once dirname(__DIR__) . '/template/header.php'; ?>
   <h3>Daftar Buku</h3>
   <ul>
     <?php foreach ($data['buku'] as $b): ?>
       <li><?= htmlspecialchars($b['judul']); ?></li>
     <?php endforeach; ?>
   </ul>
   <?php require_once dirname(__DIR__) . '/template/footer.php'; ?>
   ```

Akses fitur baru di browser: **`http://localhost/template-final-project-ai/buku/`**

---

## ❓ Troubleshooting Singkat

- **Muncul peringatan merah "Database Belum Terhubung"?**
  Pastikan modul MySQL di XAMPP Control Panel sudah dalam kondisi **Running** (berwarna hijau).
- **Muncul error "Access denied for user 'root'"?**
  Buka `app/config/config.php`, lalu sesuaikan `PASS_DB` dengan password MySQL komputer Anda.
- **Muncul Error 404 saat membuka menu?**
  Pastikan modul `mod_rewrite` di Apache XAMPP aktif dan file `.htaccess` ada di folder proyek.
