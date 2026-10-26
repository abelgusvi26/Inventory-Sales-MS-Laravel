# Entity Relationship Diagram (ERD)
## MotoParts Pro - Motorcycle Spare Parts Sales & Inventory Management System

Dokumen ini menjelaskan rancangan relasi basis data (*database schema & constraints*) pada sistem manajemen inventaris dan transaksi penjualan suku cadang sepeda motor.

```mermaid
erDiagram
    USERS ||--o{ PURCHASES : "records (petugas)"
    USERS ||--o{ SALES : "records (kasir)"
    USERS ||--o{ PURCHASES : "cancels (cancelled_by)"
    USERS ||--o{ SALES : "cancels (cancelled_by)"
    
    CATEGORIES ||--o{ PRODUCTS : "classifies"
    SUPPLIERS ||--o{ PURCHASES : "supplies"
    
    PURCHASES ||--|{ PURCHASE_ITEMS : "contains"
    PRODUCTS ||--o{ PURCHASE_ITEMS : "bought in"
    
    SALES ||--|{ SALE_ITEMS : "contains"
    PRODUCTS ||--o{ SALE_ITEMS : "sold in"

    USERS {
        bigint id PK
        string name
        string username UK
        string email UK
        string password
        string role "admin | staff | customer"
        string phone
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    CATEGORIES {
        bigint id PK
        string code UK
        string name
        text description
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "Soft Delete"
    }

    SUPPLIERS {
        bigint id PK
        string code UK
        string name "PIC Contact"
        string company_name "PT / CV Name"
        string email
        string phone
        text address
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "Soft Delete"
    }

    PRODUCTS {
        bigint id PK
        bigint category_id FK
        string sku UK "Unique Stock Keeping Unit"
        string name "Nama Suku Cadang Motor"
        string brand "AHM, YGP, Daytona, RCB, Motul, GS Astra"
        string model_series "Beat, Vario 160, NMAX, Aerox, Scoopy, PCX"
        string unit "pcs, set, unit, botol, pasang"
        decimal purchase_price "15,2"
        decimal selling_price "15,2"
        int stock "Current inventory quantity"
        int min_stock "Safety stock threshold"
        string location "Bin / Rack ID"
        text description
        string image "Path foto / ilustrasi suku cadang"
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "Soft Delete"
    }

    PURCHASES {
        bigint id PK
        string reference_no UK "PO-YYYYMMDD-XXXX"
        bigint supplier_id FK
        bigint user_id FK "Creator staff"
        date purchase_date
        string status "completed | cancelled"
        decimal total_amount "15,2 (calculated by server)"
        text notes
        timestamp cancelled_at
        bigint cancelled_by FK
        string cancellation_reason
        timestamp created_at
        timestamp updated_at
    }

    PURCHASE_ITEMS {
        bigint id PK
        bigint purchase_id FK
        bigint product_id FK
        int quantity "Must be > 0"
        decimal unit_cost "15,2"
        decimal subtotal "15,2"
        timestamp created_at
        timestamp updated_at
    }

    SALES {
        bigint id PK
        string invoice_no UK "INV-YYYYMMDD-XXXX"
        string customer_name
        string customer_phone
        bigint user_id FK "Cashier / staff"
        date sale_date
        string status "completed | cancelled"
        decimal total_amount "15,2 (calculated by server)"
        decimal paid_amount "15,2"
        decimal change_amount "15,2"
        string payment_method "cash | transfer | tempo"
        text notes
        timestamp cancelled_at
        bigint cancelled_by FK
        string cancellation_reason
        timestamp created_at
        timestamp updated_at
    }

    SALE_ITEMS {
        bigint id PK
        bigint sale_id FK
        bigint product_id FK
        int quantity "Must be > 0 and <= stock"
        decimal unit_price "15,2"
        decimal subtotal "15,2"
        timestamp created_at
        timestamp updated_at
    }
```

---

### Penjelasan Integritas & Kardinalitas Data:

1. **`users`**:
   - Berperan sebagai otentikasi dan otorisasi (`role`: `admin`, `staff`, atau `customer`).
   - Berelasi ke `purchases` dan `sales` sebagai pencatat transaksi kasir (audit trail) maupun pembatal transaksi (`cancelled_by`).

2. **`categories` -> `products` (1 to Many)**:
   - Pengelompokan komponen suku cadang motor (Transmisi CVT, Pengereman, Mesin & Piston, Oli & Pelumas, Kelistrikan & Aki, Suspensi & Ban).
   - Menggunakan `nullOnDelete()` dan `SoftDeletes` untuk menjamin histori data produk tidak rusak bila kategori diarsipkan.

3. **`suppliers` -> `purchases` (1 to Many)**:
   - Mencatat mitra pemasok resmi suku cadang motor (distributor AHM, YGP, Daytona, dsb). Dilindungi `restrictOnDelete()` sehingga supplier yang memiliki riwayat PO tidak dapat dihapus sembarangan.

4. **`products` (Dukungan Gambar & Visual)**:
   - Kolom `image` menyimpan path file foto suku cadang motor (JPG/PNG/WEBP/SVG) yang diunggah dari form atau aset ilustrasi di `public/images/products/`.

5. **`purchases` & `purchase_items` (Header - Detail)**:
   - Relasi 1 to Many cascade delete dari header ke item.
   - Menggunakan `lockForUpdate()` dan `DB::transaction()`: saat pembelian sukses disimpan, kuantitas bertambah otomatis ke kolom `products.stock`.

6. **`sales` & `sale_items` (Header - Detail)**:
   - Relasi 1 to Many cascade delete dari header ke item.
   - Menggunakan `lockForUpdate()` dan `DB::transaction()`: sistem memverifikasi bahwa total kuantitas tidak melebihi sisa fisik di `products.stock`. Jika stok tidak mencukupi, operasi dibatalkan total (`rollback`).

7. **Aturan Pembatalan Transaksi (Cancellation & Restocking)**:
   - Pembatalan transaksi hanya diizinkan untuk akun role `admin`.
   - Pembatalan penjualan kasir mengembalikan kuantitas barang ke `products.stock`.
   - Pembatalan pembelian memeriksa apakah stok fisik masih mencukupi untuk dikurangi kembali ke posisi sebelum barang masuk.
