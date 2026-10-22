# Heavy Equipment Inventory & Sales Management System (Laravel 10)

Sistem Informasi Manajemen Inventaris dan Transaksi Penjualan terintegrasi untuk bisnis distribusi suku cadang dan armada alat berat (*heavy equipment parts*), dibangun menggunakan **Laravel 10**, **MySQL / MariaDB**, **Eloquent ORM**, serta mengimplementasikan prinsip data integrity dan atomic database transactions.

---

## 🚀 Fitur Utama & Kesesuaian Kebutuhan

1. **Authentication & Role-Based Access Control (RBAC)**:
   - **Admin / Owner**: Akses penuh ke seluruh modul, manajemen akun pengguna, pembatalan transaksi resmi dengan pemulihan stok otomatis.
   - **Staff (Gudang & Kasir)**: Input transaksi pembelian (*stock-in*), transaksi penjualan (*stock-out*), melihat katalog dan peringatan stok rendah. Dibatasi dari menu manajemen pengguna dan pembatalan transaksi berisiko.

2. **Master Data Suku Cadang Alat Berat**:
   - Master Produk (SKU unik, Merek, Seri Kompatibilitas seperti *PC200-8* / *CAT 320D*, Satuan, Harga Beli, Harga Jual, Stok Aktual, Batas Minimum Stok, Lokasi Rak).
   - Master Kategori Produk & Master Mitra Pemasok (Suppliers).
   - Dilengkapi *Soft Deletes* dan validasi penghapusan: produk atau pemasok yang sudah terlibat dalam riwayat transaksi dilindungi dari *hard delete*.

3. **Logika Transaksi & Pembaruan Stok Otomatis (*Atomic Transactions*)**:
   - **Pembelian (*Purchase / Stock-In*)**: Multi-item, perhitungan total dihitung aman oleh server, nomor referensi urut otomatis (`PO-YYYYMMDD-XXXX`), stok bertambah otomatis hanya setelah database transaction berhasil di-*commit*.
   - **Penjualan (*Sales / Stock-Out*)**: Multi-item, pengecekan ketersediaan stok fisik secara *real-time* di server (`lockForUpdate`). Transaksi ditolak secara tegas apabila stok tidak mencukupi. Stok berkurang otomatis setelah transaksi sukses.
   - **Pembatalan Transaksi**: Admin dapat membatalkan transaksi dengan pencatatan alasan dan petugas pembatal. Stok otomatis dipulihkan/dikembalikan secara konsisten.

4. **Dashboard & Laporan Agregat**:
   - Menghitung omset penjualan hari ini dan bulan ini langsung dari database agregat (`sum()`, `count()`).
   - Valuasi total nilai aset inventaris fisik gudang (`SUM(stock * purchase_price)`).
   - Notifikasi & tabel khusus **Peringatan Stok Rendah / Kritis** (*Safety Stock Alert*).
   - Filter tanggal, pencarian kata kunci, kategori, status transaksi, dan paginasi yang mempertahankan parameter query (`paginate()->withQueryString()`).

---

## 🛠️ Persyaratan Sistem & Arsitektur

- **PHP**: Versi 8.2 atau lebih baru (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl`, `zip`)
- **DBMS**: MySQL 5.7+ / MariaDB 10.1+
- **Composer**: Versi 2.x
- **Framework**: Laravel 10.x
- **Arsitektur**:
  - `routes/web.php`: Routing HTTP dengan pemisahan middleware `guest`, `auth`, dan `role:admin`.
  - `app/Http/Controllers/`: Pemisahan logika kendali modul (`DashboardController`, `ProductController`, `PurchaseController`, `SaleController`, `CategoryController`, `SupplierController`, `UserController`, `AuthController`).
  - `app/Models/`: Model Eloquent dengan relasi lengkap dan casting tipe data.
  - `database/migrations/`: Migrasi terstruktur dengan foreign keys, unique constraint, dan default string length 191 (kompatibel utf8mb4 MariaDB).

---

## 📥 Panduan Instalasi & Menjalankan Aplikasi

### 1. Konfigurasi Lingkungan (`.env`) & Pastikan MySQL Berjalan
- Buka **XAMPP Control Panel** lalu klik **Start** pada modul **MySQL** (atau jalankan `C:\xampp\mysql_start.bat`).
- Konfigurasi database default pada file [`.env`](file:///d:/Sistem%20Informasi/Semester%203/Pemrograman%20Web/Inventory_Sales_MS_Laravel/.env):
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_sales_ms
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Jalankan Migrasi & Seeder
Jalankan perintah berikut untuk membuat seluruh tabel dan mengisi data awal (kategori, supplier, suku cadang alat berat, dan akun pengguna):
```bash
php artisan migrate:fresh --seed
```

### 3. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi dapat diakses melalui browser pada alamat: **`http://127.0.0.1:8000`**

---

## 🔑 Kredensial Akun Pengguna Bawaan (Default Seeders)

| Role | Username | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin / Owner** | `admin` | `admin123` | Akses penuh, manajemen pengguna, pembatalan transaksi |
| **Staff Gudang & Kasir** | `staff1` | `staff123` | Transaksi pembelian, penjualan, katalog produk |
| **Staff Operasional** | `staff2` | `staff123` | Transaksi pembelian dan penjualan |
| **Customer / Pembeli** | `customer1` | `customer123` | Portal katalog suku cadang, pantau riwayat faktur pribadi |
| **Customer / Pembeli** | `customer2` | `customer123` | Akun pembeli perusahaan / kontraktor alat berat |

---

## 🧪 Pengujian Otomatis (*Automated Feature Testing*)

Telah disediakan suite pengujian otomatis (`InventorySalesBusinessRulesTest`) yang menguji integritas seluruh aturan bisnis utama:
- Pengalihan otentikasi (guest redirect).
- Hak akses staff vs admin (RBAC 403 authorization).
- Penambahan stok otomatis saat pembelian sukses.
- Pengurangan stok otomatis saat penjualan berhasil.
- Penolakan transaksi penjualan jika stok tidak mencukupi (rollback).
- Pembatalan transaksi dan pemulihan stok kembali ke gudang oleh Admin.

Jalankan test suite dengan perintah:
```bash
php artisan test --filter=InventorySalesBusinessRulesTest
```

---

## 📂 Struktur Dokumen Tambahan

- **[ERD.md](file:///d:/Sistem%20Informasi/Semester%203/Pemrograman%20Web/Inventory_Sales_MS_Laravel/ERD.md)**: Diagram Entity Relationship, struktur relasi tabel, tipe data, dan aturan kunci constraint.
