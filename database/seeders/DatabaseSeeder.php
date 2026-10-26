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
     * Seed the application's database with motorcycle spare parts data.
     */
    public function run(): void
    {
        // 1. Seed Users (Owner/Admin, Staff Kasir, dan Pelanggan Bengkel/Retail)
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Owner / Admin Toko',
                'email' => 'admin@motoparts.local',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        $staff1 = User::firstOrCreate(
            ['username' => 'staff1'],
            [
                'name' => 'Budi Pratama (Kasir & Staff Gudang)',
                'email' => 'staff1@motoparts.local',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'phone' => '081298765432',
                'is_active' => true,
            ]
        );

        $staff2 = User::firstOrCreate(
            ['username' => 'staff2'],
            [
                'name' => 'Siti Nurhaliza (Mekanik & Frontdesk)',
                'email' => 'staff2@motoparts.local',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'phone' => '081377889900',
                'is_active' => true,
            ]
        );

        $customer1 = User::firstOrCreate(
            ['username' => 'customer1'],
            [
                'name' => 'Bengkel Motor Berkah Jaya',
                'email' => 'bengkel.berkah@client.local',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'phone' => '081122334455',
                'is_active' => true,
            ]
        );

        $customer2 = User::firstOrCreate(
            ['username' => 'customer2'],
            [
                'name' => 'Komunitas Matic Speed Club',
                'email' => 'speedclub@client.local',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'phone' => '081288990011',
                'is_active' => true,
            ]
        );

        // 2. Seed Categories for Motorcycle Spare Parts
        $catCVT = Category::firstOrCreate(
            ['code' => 'CAT-CVT'],
            [
                'name' => 'Transmisi CVT & Drivetrain',
                'description' => 'V-Belt, roller transmisi, per cvt, mangkok ganda, kampas ganda untuk motor matic',
            ]
        );

        $catBrake = Category::firstOrCreate(
            ['code' => 'CAT-BRK'],
            [
                'name' => 'Sistem Pengereman (Brake)',
                'description' => 'Kampas rem tromol & cakram (brake pad), piringan disc brake, master rem, selang rem',
            ]
        );

        $catEngine = Category::firstOrCreate(
            ['code' => 'CAT-ENG'],
            [
                'name' => 'Mesin & Piston Kit',
                'description' => 'Piston set, ring seher, klep mesin, noken as/camshaft, rantai keteng & paking blok',
            ]
        );

        $catOil = Category::firstOrCreate(
            ['code' => 'CAT-OIL'],
            [
                'name' => 'Oli & Pelumas Mesin',
                'description' => 'Oli mesin 4T, oli gardan matic, cairan radiator coolant, minyak rem & pelumas rantai',
            ]
        );

        $catElec = Category::firstOrCreate(
            ['code' => 'CAT-ELC'],
            [
                'name' => 'Kelistrikan & Penerangan',
                'description' => 'Aki kering/basah (battery MF), busi iridium, lampu LED biled, relay flasher, cdi/ecu',
            ]
        );

        $catChassis = Category::firstOrCreate(
            ['code' => 'CAT-SUS'],
            [
                'name' => 'Suspensi, Rantai & Ban',
                'description' => 'Shockbreaker belakang/depan, gear set rantai roda, ban luar/dalam tubeless',
            ]
        );

        // 3. Seed Suppliers (Distributor Resmi Suku Cadang Motor)
        $sup1 = Supplier::firstOrCreate(
            ['code' => 'SUP-AHM'],
            [
                'name' => 'PT Astra Honda Motor Parts Depot',
                'company_name' => 'Distributor Resmi Honda Genuine Parts (AHM)',
                'email' => 'parts@ahm-distribution.co.id',
                'phone' => '021-6518080',
                'address' => 'Kawasan Industri Pulogadung Jl. Raya Bekasi Km 20, Jakarta Timur',
            ]
        );

        $sup2 = Supplier::firstOrCreate(
            ['code' => 'SUP-YGP'],
            [
                'name' => 'PT Yamaha Indonesia Motor Parts Supply',
                'company_name' => 'Yamaha Genuine Parts (YGP) Center',
                'email' => 'spareparts@yamaha-motor.co.id',
                'phone' => '021-4607880',
                'address' => 'Jl. Dr. KRT Radjiman Widyodiningrat, Pulo Jahe, Jakarta Timur',
            ]
        );

        $sup3 = Supplier::firstOrCreate(
            ['code' => 'SUP-DAY'],
            [
                'name' => 'PT Daytona Racing Distribution',
                'company_name' => 'Daytona Racing Performance Parts',
                'email' => 'sales@daytonaracing.id',
                'phone' => '021-8972341',
                'address' => 'Kawasan Industri Delta Silicon Lippo Cikarang, Bekasi',
            ]
        );

        $sup4 = Supplier::firstOrCreate(
            ['code' => 'SUP-RCBOT'],
            [
                'name' => 'PT Enwan Multi Partindo (RCB & Proliner)',
                'company_name' => 'Racing Boy (RCB) Indonesia Official',
                'email' => 'order@enwan-rcb.co.id',
                'phone' => '021-5967000',
                'address' => 'Kawasan Industri Jatake, Tangerang, Banten',
            ]
        );

        // 4. Seed Motorcycle Spare Parts Products With Images
        $productsData = [
            [
                'category_id' => $catCVT->id,
                'sku' => 'CVT-VBLT-BEAT',
                'name' => 'V-Belt & Roller Set Honda Beat FI / ESP (23100-K44-V01)',
                'brand' => 'Honda Genuine Parts (AHM)',
                'model_series' => 'Beat FI / ESP / Street / Scoopy ESP',
                'unit' => 'set',
                'purchase_price' => 95000,
                'selling_price' => 145000,
                'stock' => 35,
                'min_stock' => 10,
                'location' => 'Rak A-01 (CVT Matic)',
                'description' => 'Paket sabuk transmisi V-Belt dan roller standar original AHM untuk motor matic Honda 110cc',
                'image' => 'images/products/vbelt_cvt.jpg',
            ],
            [
                'category_id' => $catCVT->id,
                'sku' => 'CVT-VBLT-NMAX',
                'name' => 'V-Belt Kit Yamaha NMAX 155 / Aerox 155 (B65-E7641-00)',
                'brand' => 'Yamaha Genuine Parts (YGP)',
                'model_series' => 'NMAX 155 New/Old / Aerox 155 / Lexi',
                'unit' => 'pcs',
                'purchase_price' => 110000,
                'selling_price' => 165000,
                'stock' => 28,
                'min_stock' => 8,
                'location' => 'Rak A-02 (CVT Maxi)',
                'description' => 'V-Belt original kevlar Yamaha untuk lini Maxi Scooter 155cc tahan panas dan putaran tinggi',
                'image' => 'images/products/vbelt_cvt.jpg',
            ],
            [
                'category_id' => $catBrake->id,
                'sku' => 'BRK-PAD-FR01',
                'name' => 'Kampas Rem Cakram Depan Daytona Ultra Force (Lapisan Keramik)',
                'brand' => 'Daytona Racing',
                'model_series' => 'Vario 125/150/160, Beat FI, Scoopy, PCX',
                'unit' => 'set',
                'purchase_price' => 45000,
                'selling_price' => 75000,
                'stock' => 4, // low stock!
                'min_stock' => 12,
                'location' => 'Rak B-01 (Brake Pad)',
                'description' => 'Brake pad non-asbestos keramik Daytona, daya cengkeram pakem, tidak berisik dan awet terhadap disc brake',
                'image' => 'images/products/brake_pad.jpg',
            ],
            [
                'category_id' => $catBrake->id,
                'sku' => 'BRK-DISC-RCB',
                'name' => 'Piringan Cakram Floating Disc RCB S-Series 260mm Gold',
                'brand' => 'Racing Boy (RCB)',
                'model_series' => 'Beat / Vario 125 / Scoopy / Mio',
                'unit' => 'pcs',
                'purchase_price' => 280000,
                'selling_price' => 395000,
                'stock' => 12,
                'min_stock' => 5,
                'location' => 'Rak B-03 (Piringan Cakram)',
                'description' => 'Floating disc brake kompetisi diameter 260mm berbahan stainless steel jepang dengan carrier aluminium alloy',
                'image' => 'images/products/brake_pad.jpg',
            ],
            [
                'category_id' => $catEngine->id,
                'sku' => 'ENG-PST-VAR160',
                'name' => 'Piston Kit Set Standar Honda Vario 160 / PCX 160 (13101-K0S-V00)',
                'brand' => 'Honda Genuine Parts (AHM)',
                'model_series' => 'Vario 160 ESP+ / PCX 160 / ADV 160 (4 Katup)',
                'unit' => 'set',
                'purchase_price' => 160000,
                'selling_price' => 235000,
                'stock' => 3, // low stock!
                'min_stock' => 6,
                'location' => 'Rak C-02 (Piston Kit)',
                'description' => 'Paket piston lengkap ring seher, pin piston, dan klip pengunci untuk mesin eSP+ 4 valve',
                'image' => 'images/products/piston_kit.jpg',
            ],
            [
                'category_id' => $catOil->id,
                'sku' => 'OIL-MOTUL-SCOOT',
                'name' => 'Oli Mesin Motul Scooter Expert LE 4T 10W-40 Technosynthese 1 Liter',
                'brand' => 'Motul',
                'model_series' => 'Semua Motor Matic 110cc - 250cc',
                'unit' => 'botol',
                'purchase_price' => 78000,
                'selling_price' => 105000,
                'stock' => 45,
                'min_stock' => 15,
                'location' => 'Rak D-01 (Pelumas Oli)',
                'description' => 'Oli semi sintetik JASO MB API SM dengan aditif pembersih kerak mesin matic suhu tinggi',
                'image' => 'images/products/oli_mesin.jpg',
            ],
            [
                'category_id' => $catElec->id,
                'sku' => 'ELC-AKI-GTZ5S',
                'name' => 'Aki Motor Kering Bebas Perawatan GS Astra Maintenance Free GTZ-5S',
                'brand' => 'GS Astra',
                'model_series' => 'Beat, Vario 110, Mio M3, Scoopy, Supra X 125, Vixion',
                'unit' => 'unit',
                'purchase_price' => 185000,
                'selling_price' => 245000,
                'stock' => 18,
                'min_stock' => 8,
                'location' => 'Rak E-01 (Aki & Battery)',
                'description' => 'Aki kering 12V 3.5Ah siap pasang anti tumpah dengan CCA tinggi untuk starter elektrik',
                'image' => 'images/products/aki_motor.jpg',
            ],
            [
                'category_id' => $catElec->id,
                'sku' => 'ELC-LAMP-BILED',
                'name' => 'Lampu Depan Biled Projector AES Turbo SE All Weather 2.5 Inch 55W',
                'brand' => 'AES Projector',
                'model_series' => 'Universal Custom Motor Bebek, Matic & Sport',
                'unit' => 'set',
                'purchase_price' => 420000,
                'selling_price' => 580000,
                'stock' => 8,
                'min_stock' => 4,
                'location' => 'Rak E-04 (Penerangan LED)',
                'description' => 'Lensa biled cut-off tajam dengan kipas pendingin high-speed dan driver tahan fluktuasi voltase',
                'image' => 'images/products/lampu_led.svg',
            ],
            [
                'category_id' => $catChassis->id,
                'sku' => 'SUS-SHOCK-RCB',
                'name' => 'Shockbreaker Tabung RCB VD Series 330mm Click Rebound Adjustable',
                'brand' => 'Racing Boy (RCB)',
                'model_series' => 'Vario 125/150, Beat, Scoopy, Mio',
                'unit' => 'pasang',
                'purchase_price' => 1250000,
                'selling_price' => 1650000,
                'stock' => 6,
                'min_stock' => 3,
                'location' => 'Rak F-02 (Suspensi)',
                'description' => 'Suspensi tabung gas nitrogen dengan setelan preload dan rebound klik empuk untuk harian & touring',
                'image' => 'images/products/shockbreaker.jpg',
            ],
            [
                'category_id' => $catChassis->id,
                'sku' => 'DRV-CHAIN-SSS',
                'name' => 'Gear Set Paket Rantai SSS Racing Gold 415 / 428 Heavy Duty',
                'brand' => 'SSS Sprocket',
                'model_series' => 'CBR150R / CB150R / MX King 150 / Sonic 150R',
                'unit' => 'set',
                'purchase_price' => 245000,
                'selling_price' => 335000,
                'stock' => 14,
                'min_stock' => 5,
                'location' => 'Rak F-05 (Rantai & Gear)',
                'description' => 'Sprocket baja karbon presisi tinggi dengan rantai emas tahan mulur untuk motor kopling sport',
                'image' => 'images/products/gear_set.jpg',
            ],
            [
                'category_id' => $catChassis->id,
                'sku' => 'BAN-MAXXIS-VIC',
                'name' => 'Ban Motor Tubeless Maxxis Victra S98 ST 90/80-14 Soft Compound Dual',
                'brand' => 'Maxxis Tire',
                'model_series' => 'Pelek Ring 14 Beat / Vario / Scoopy / Mio / Aerox',
                'unit' => 'pcs',
                'purchase_price' => 250000,
                'selling_price' => 330000,
                'stock' => 22,
                'min_stock' => 8,
                'location' => 'Gudang Ban Belakang',
                'description' => 'Ban tubeless kompon lunak daya cengkeram maksimal di aspal kering maupun basah',
                'image' => 'images/products/ban_motor.jpg',
            ],
        ];

        foreach ($productsData as $p) {
            Product::updateOrCreate(['sku' => $p['sku']], $p);
        }

        // 5. Seed Historical Purchases & Stock-in Transaction
        $prod1 = Product::where('sku', 'CVT-VBLT-BEAT')->first();
        $prod2 = Product::where('sku', 'OIL-MOTUL-SCOOT')->first();

        $purchase = Purchase::firstOrCreate(
            ['reference_no' => 'PO-' . date('Ymd') . '-0001'],
            [
                'supplier_id' => $sup1->id,
                'user_id' => $staff1->id,
                'purchase_date' => Carbon::now()->subDays(2)->toDateString(),
                'status' => 'completed',
                'total_amount' => (20 * 95000) + (30 * 78000), // 1,900,000 + 2,340,000 = 4,240,000
                'notes' => 'Restock rutin V-Belt AHM Beat FI dan Oli Motul Scooter Expert 1L',
            ]
        );

        PurchaseItem::firstOrCreate(
            [
                'purchase_id' => $purchase->id,
                'product_id' => $prod1->id,
            ],
            [
                'quantity' => 20,
                'unit_cost' => 95000,
                'subtotal' => 20 * 95000,
            ]
        );

        PurchaseItem::firstOrCreate(
            [
                'purchase_id' => $purchase->id,
                'product_id' => $prod2->id,
            ],
            [
                'quantity' => 30,
                'unit_cost' => 78000,
                'subtotal' => 30 * 78000,
            ]
        );

        // 6. Seed Historical Sales Transaction
        $sale = Sale::firstOrCreate(
            ['invoice_no' => 'INV-' . date('Ymd') . '-0001'],
            [
                'customer_name' => 'Bengkel Motor Berkah Jaya',
                'customer_phone' => '081122334455',
                'user_id' => $staff1->id,
                'sale_date' => Carbon::now()->toDateString(),
                'status' => 'completed',
                'total_amount' => (2 * 145000) + (4 * 105000), // 290,000 + 420,000 = 710,000
                'paid_amount' => 750000,
                'change_amount' => 40000,
                'payment_method' => 'cash',
                'notes' => 'Servis berkala & ganti V-belt Beat FI pelanggan bengkel',
            ]
        );

        SaleItem::firstOrCreate(
            [
                'sale_id' => $sale->id,
                'product_id' => $prod1->id,
            ],
            [
                'quantity' => 2,
                'unit_price' => 145000,
                'subtotal' => 2 * 145000,
            ]
        );

        SaleItem::firstOrCreate(
            [
                'sale_id' => $sale->id,
                'product_id' => $prod2->id,
            ],
            [
                'quantity' => 4,
                'unit_price' => 105000,
                'subtotal' => 4 * 105000,
            ]
        );
    }
}
