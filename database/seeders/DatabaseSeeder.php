<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Owner/Admin and Staff)
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Owner / Admin Utama',
                'email' => 'admin@heavyequip.local',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        $staff1 = User::firstOrCreate(
            ['username' => 'staff1'],
            [
                'name' => 'Budi Santoso (Staff Gudang & Kasir)',
                'email' => 'staff1@heavyequip.local',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'phone' => '081298765432',
                'is_active' => true,
            ]
        );

        $staff2 = User::firstOrCreate(
            ['username' => 'staff2'],
            [
                'name' => 'Siti Rahma (Staff Operasional)',
                'email' => 'staff2@heavyequip.local',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'phone' => '081377889900',
                'is_active' => true,
            ]
        );

        $customer1 = User::firstOrCreate(
            ['username' => 'customer1'],
            [
                'name' => 'CV Mandiri Tambang Abadi',
                'email' => 'mandiri.tambang@client.local',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'phone' => '081122334455',
                'is_active' => true,
            ]
        );

        $customer2 = User::firstOrCreate(
            ['username' => 'customer2'],
            [
                'name' => 'PT Nusantara Karya Perkasa',
                'email' => 'procurement@nusantarakarya.co.id',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'phone' => '081288990011',
                'is_active' => true,
            ]
        );

        // 2. Seed Categories for Heavy Equipment Business
        $catUndercarriage = Category::firstOrCreate(
            ['code' => 'CAT-UND'],
            [
                'name' => 'Undercarriage & Tracks',
                'description' => 'Komponen kaki-kaki: track link, track roller, idler, sprocket untuk excavator & bulldozer',
            ]
        );

        $catHydraulic = Category::firstOrCreate(
            ['code' => 'CAT-HYD'],
            [
                'name' => 'Hydraulic System & Seals',
                'description' => 'Main pump, hydraulic cylinder, control valve, seal kit & hoses',
            ]
        );

        $catEngine = Category::firstOrCreate(
            ['code' => 'CAT-ENG'],
            [
                'name' => 'Engine & Filtration',
                'description' => 'Sparepart mesin diesel alat berat, turbocharger, filter oli, fuel filter & water separator',
            ]
        );

        $catGET = Category::firstOrCreate(
            ['code' => 'CAT-GET'],
            [
                'name' => 'Ground Engaging Tools (G.E.T)',
                'description' => 'Bucket teeth, cutting edge, side cutter, ripper shank',
            ]
        );

        $catElectrical = Category::firstOrCreate(
            ['code' => 'CAT-ELC'],
            [
                'name' => 'Electrical & Sensors',
                'description' => 'Starting motor, alternator, solenoid valve, wiring harness & pressure sensor',
            ]
        );

        // 3. Seed Suppliers (Distributor Resmi Alat Berat)
        $sup1 = Supplier::firstOrCreate(
            ['code' => 'SUP-TRAK'],
            [
                'name' => 'PT Trakindo Utama Partindo',
                'company_name' => 'PT Trakindo Utama Heavy Spares',
                'email' => 'sales@trakindopartindo.com',
                'phone' => '021-7890123',
                'address' => 'Kawasan Industri Cikarang Blok B-12, Bekasi',
            ]
        );

        $sup2 = Supplier::firstOrCreate(
            ['code' => 'SUP-UT'],
            [
                'name' => 'PT United Tractors Parts Supply',
                'company_name' => 'United Tractors Genuine Parts Division',
                'email' => 'contact@utpartsupply.co.id',
                'phone' => '021-4605980',
                'address' => 'Jl. Raya Bekasi Km 22, Cakung, Jakarta Timur',
            ]
        );

        $sup3 = Supplier::firstOrCreate(
            ['code' => 'SUP-HEX'],
            [
                'name' => 'PT Hexindo Heavy Spares',
                'company_name' => 'Hexindo Adiperkasa Parts Depot',
                'email' => 'supply@hexindospares.co.id',
                'phone' => '021-8765432',
                'address' => 'Pulo Gadung Industrial Estate Kav. 9, Jakarta Timur',
            ]
        );

        $sup4 = Supplier::firstOrCreate(
            ['code' => 'SUP-KOM'],
            [
                'name' => 'PT Komatsu Undercarriage Asia',
                'company_name' => 'Komatsu Asia Supply Center',
                'email' => 'asia.parts@komatsugroup.com',
                'phone' => '021-5544332',
                'address' => 'Kawasan MM2100 Cibitung, Cikarang Barat',
            ]
        );

        // 4. Seed Products with Heavy Equipment Specifications
        $productsData = [
            [
                'category_id' => $catGET->id,
                'sku' => 'GET-BT-PC200',
                'name' => 'Bucket Tooth PC200 Tiger Type (205-70-19570)',
                'brand' => 'Komatsu OEM',
                'model_series' => 'PC200-7 / PC200-8',
                'unit' => 'pcs',
                'purchase_price' => 240000,
                'selling_price' => 350000,
                'stock' => 45,
                'min_stock' => 15,
                'location' => 'Rak A-01',
                'description' => 'Gigi bucket tipe tiger khusus tanah keras & bebatuan tambang',
            ],
            [
                'category_id' => $catGET->id,
                'sku' => 'GET-PIN-PC200',
                'name' => 'Pin & Lock Bucket Tooth PC200 (09244-02496)',
                'brand' => 'Komatsu OEM',
                'model_series' => 'PC200-8',
                'unit' => 'set',
                'purchase_price' => 35000,
                'selling_price' => 55000,
                'stock' => 80,
                'min_stock' => 20,
                'location' => 'Rak A-02',
                'description' => 'Pin pengunci bucket tooth berbahan baja presisi tahan getaran',
            ],
            [
                'category_id' => $catEngine->id,
                'sku' => 'ENG-FLT-CAT320',
                'name' => 'Fuel Water Separator Filter Element (1R-0770)',
                'brand' => 'Caterpillar Genuine',
                'model_series' => 'CAT 320D / 320D2',
                'unit' => 'pcs',
                'purchase_price' => 380000,
                'selling_price' => 520000,
                'stock' => 4, // low stock!
                'min_stock' => 10,
                'location' => 'Rak B-05',
                'description' => 'Filter pemisah air dan solar biosolar B35 untuk excavator Cat 320D',
            ],
            [
                'category_id' => $catEngine->id,
                'sku' => 'ENG-OIL-6D102',
                'name' => 'Oil Filter Lube Element (6736-51-5142)',
                'brand' => 'Fleetguard / Cummins',
                'model_series' => 'SAA6D102E-2 / PC200-7',
                'unit' => 'pcs',
                'purchase_price' => 195000,
                'selling_price' => 285000,
                'stock' => 18,
                'min_stock' => 8,
                'location' => 'Rak B-06',
                'description' => 'Filter oli mesin kapasitas tinggi untuk daya tahan operasi berat',
            ],
            [
                'category_id' => $catHydraulic->id,
                'sku' => 'HYD-SEAL-BOOM200',
                'name' => 'Seal Kit Boom Cylinder PC200-8 (707-99-46320)',
                'brand' => 'NOK Japan Genuine',
                'model_series' => 'PC200-8',
                'unit' => 'set',
                'purchase_price' => 1250000,
                'selling_price' => 1750000,
                'stock' => 2, // low stock!
                'min_stock' => 5,
                'location' => 'Rak C-02',
                'description' => 'Seal kit hidrolik tahan tekanan tinggi 350 bar bahan polyurethane/FKM',
            ],
            [
                'category_id' => $catHydraulic->id,
                'sku' => 'HYD-SEAL-ARM320',
                'name' => 'Seal Kit Arm Cylinder CAT 320D (247-8890)',
                'brand' => 'Caterpillar OEM',
                'model_series' => 'CAT 320D',
                'unit' => 'set',
                'purchase_price' => 1400000,
                'selling_price' => 1980000,
                'stock' => 6,
                'min_stock' => 4,
                'location' => 'Rak C-03',
                'description' => 'Seal kit hidrolik arm stick silinder lengkap o-ring dan wear ring',
            ],
            [
                'category_id' => $catUndercarriage->id,
                'sku' => 'UND-TR-PC200',
                'name' => 'Track Roller Single Flange PC200-8 (20Y-30-00016)',
                'brand' => 'Berco Italy',
                'model_series' => 'PC200-8',
                'unit' => 'pcs',
                'purchase_price' => 1850000,
                'selling_price' => 2450000,
                'stock' => 14,
                'min_stock' => 6,
                'location' => 'Gudang Bawah G-01',
                'description' => 'Roller roda bawah rel undercarriage excavator tahan beban kejut tinggi',
            ],
            [
                'category_id' => $catUndercarriage->id,
                'sku' => 'UND-IDL-CAT320',
                'name' => 'Front Idler Assembly CAT 320D (102-8151)',
                'brand' => 'ITM Italtractor',
                'model_series' => 'CAT 320C / 320D',
                'unit' => 'pcs',
                'purchase_price' => 6200000,
                'selling_price' => 7900000,
                'stock' => 1, // critical low stock!
                'min_stock' => 3,
                'location' => 'Gudang Bawah G-04',
                'description' => 'Idler roda pemandu depan track undercarriage lengkap braket dan shaft',
            ],
            [
                'category_id' => $catElectrical->id,
                'sku' => 'ELC-STR-24V',
                'name' => 'Starter Motor 24V 7.5kW PC200-8 (600-863-5111)',
                'brand' => 'Denso Heavy Duty',
                'model_series' => 'PC200-8 / Komatsu 6D107',
                'unit' => 'unit',
                'purchase_price' => 4800000,
                'selling_price' => 6200000,
                'stock' => 3,
                'min_stock' => 2,
                'location' => 'Rak D-01',
                'description' => 'Motor starter 24 Volt torsi tinggi tahan getaran & debu tambang',
            ],
            [
                'category_id' => $catElectrical->id,
                'sku' => 'ELC-ALT-24V',
                'name' => 'Alternator Dynamo 24V 60A CAT 320D (344-5081)',
                'brand' => 'Delco Remy',
                'model_series' => 'CAT 320D C6.4 Engine',
                'unit' => 'unit',
                'purchase_price' => 3200000,
                'selling_price' => 4150000,
                'stock' => 5,
                'min_stock' => 3,
                'location' => 'Rak D-02',
                'description' => 'Dinamo ampere pengisian aki 24 Volt tugas berat',
            ],
        ];

        foreach ($productsData as $p) {
            Product::firstOrCreate(['sku' => $p['sku']], $p);
        }

        // 5. Seed Historical Purchases & Stock-in Transaction
        $prod1 = Product::where('sku', 'GET-BT-PC200')->first();
        $prod2 = Product::where('sku', 'GET-PIN-PC200')->first();

        $purchase = Purchase::firstOrCreate(
            ['reference_no' => 'PO-' . date('Ymd') . '-0001'],
            [
                'supplier_id' => $sup2->id,
                'user_id' => $staff1->id,
                'purchase_date' => Carbon::now()->subDays(2)->toDateString(),
                'status' => 'completed',
                'total_amount' => (20 * 240000) + (30 * 35000), // 4,800,000 + 1,050,000 = 5,850,000
                'notes' => 'Restock rutin kuartal komponen bucket teeth & pin Komatsu PC200',
            ]
        );

        PurchaseItem::firstOrCreate(
            [
                'purchase_id' => $purchase->id,
                'product_id' => $prod1->id,
            ],
            [
                'quantity' => 20,
                'unit_cost' => 240000,
                'subtotal' => 20 * 240000,
            ]
        );

        PurchaseItem::firstOrCreate(
            [
                'purchase_id' => $purchase->id,
                'product_id' => $prod2->id,
            ],
            [
                'quantity' => 30,
                'unit_cost' => 35000,
                'subtotal' => 30 * 35000,
            ]
        );

        // 6. Seed Historical Sales Transaction
        $sale = Sale::firstOrCreate(
            ['invoice_no' => 'INV-' . date('Ymd') . '-0001'],
            [
                'customer_name' => 'CV Mandiri Tambang Abadi',
                'customer_phone' => '081122334455',
                'user_id' => $staff1->id,
                'sale_date' => Carbon::now()->toDateString(),
                'status' => 'completed',
                'total_amount' => (5 * 350000) + (10 * 55000), // 1,750,000 + 550,000 = 2,300,000
                'paid_amount' => 2500000,
                'change_amount' => 200000,
                'payment_method' => 'transfer',
                'notes' => 'Pengiriman site quarry pasir Bogor, via pickup logistik ekspress',
            ]
        );

        SaleItem::firstOrCreate(
            [
                'sale_id' => $sale->id,
                'product_id' => $prod1->id,
            ],
            [
                'quantity' => 5,
                'unit_price' => 350000,
                'subtotal' => 5 * 350000,
            ]
        );

        SaleItem::firstOrCreate(
            [
                'sale_id' => $sale->id,
                'product_id' => $prod2->id,
            ],
            [
                'quantity' => 10,
                'unit_price' => 55000,
                'subtotal' => 10 * 55000,
            ]
        );
    }
}
