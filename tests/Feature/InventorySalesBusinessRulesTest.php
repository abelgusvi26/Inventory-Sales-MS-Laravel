<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySalesBusinessRulesTest extends TestCase
{
    protected $admin;
    protected $staff;
    protected $category;
    protected $supplier;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['username' => 'test_admin'],
            [
                'name' => 'Admin Test',
                'email' => 'admintest@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->staff = User::firstOrCreate(
            ['username' => 'test_staff'],
            [
                'name' => 'Staff Test',
                'email' => 'stafftest@example.com',
                'password' => bcrypt('password'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );

        $this->category = Category::firstOrCreate(
            ['code' => 'CAT-TEST'],
            ['name' => 'Kategori Uji', 'description' => 'Untuk test suite']
        );

        $this->supplier = Supplier::firstOrCreate(
            ['code' => 'SUP-TEST'],
            ['name' => 'Supplier Uji', 'company_name' => 'PT Test Supplier']
        );

        $this->product = Product::firstOrCreate(
            ['sku' => 'TEST-SKU-001'],
            [
                'category_id' => $this->category->id,
                'name' => 'Produk Uji Part',
                'brand' => 'Komatsu',
                'model_series' => 'PC200',
                'unit' => 'pcs',
                'purchase_price' => 100000,
                'selling_price' => 150000,
                'stock' => 10,
                'min_stock' => 5,
            ]
        );
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_successful_and_dashboard_accessible(): void
    {
        $response = $this->post('/login', [
            'login' => 'test_admin',
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->admin);

        $dashboard = $this->actingAs($this->admin)->get('/');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('Ringkasan Eksekutif & Operasional');
    }

    public function test_staff_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->staff)->get('/users');
        $response->assertStatus(403);
    }

    public function test_purchase_stock_in_increases_stock_automatically(): void
    {
        $initialStock = $this->product->stock;
        $qtyToBuy = 5;

        $response = $this->actingAs($this->staff)->post('/purchases', [
            'supplier_id' => $this->supplier->id,
            'purchase_date' => date('Y-m-d'),
            'notes' => 'Pembelian test stok masuk',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => $qtyToBuy,
                    'unit_cost' => 100000,
                ]
            ]
        ]);

        $response->assertSessionHasNoErrors();
        $this->product->refresh();
        $this->assertEquals($initialStock + $qtyToBuy, $this->product->stock);
    }

    public function test_sales_decreases_stock_when_available(): void
    {
        $initialStock = $this->product->stock;
        $qtyToSell = 3;

        $response = $this->actingAs($this->staff)->post('/sales', [
            'customer_name' => 'PT Customer Uji',
            'customer_phone' => '0812345678',
            'sale_date' => date('Y-m-d'),
            'payment_method' => 'cash',
            'paid_amount' => 500000,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => $qtyToSell,
                    'unit_price' => 150000,
                ]
            ]
        ]);

        $response->assertSessionHasNoErrors();
        $this->product->refresh();
        $this->assertEquals($initialStock - $qtyToSell, $this->product->stock);
    }

    public function test_sales_rejected_when_stock_insufficient(): void
    {
        $initialStock = $this->product->stock;
        $excessiveQty = $initialStock + 100;

        $response = $this->actingAs($this->staff)->post('/sales', [
            'customer_name' => 'PT Greedy Buyer',
            'sale_date' => date('Y-m-d'),
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => $excessiveQty,
                    'unit_price' => 150000,
                ]
            ]
        ]);

        // Must redirect back with error message and stock must NOT change
        $response->assertSessionHas('error');
        $this->product->refresh();
        $this->assertEquals($initialStock, $this->product->stock);
    }

    public function test_admin_cancel_sale_restores_stock(): void
    {
        // First make a sale
        $qty = 2;
        $this->actingAs($this->staff)->post('/sales', [
            'customer_name' => 'PT Cancel Customer',
            'sale_date' => date('Y-m-d'),
            'payment_method' => 'transfer',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => $qty,
                    'unit_price' => 150000,
                ]
            ]
        ]);

        $sale = Sale::latest('id')->first();
        $stockAfterSale = $this->product->fresh()->stock;

        // Staff tries to cancel -> must be 403 Forbidden
        $staffCancel = $this->actingAs($this->staff)->post("/sales/{$sale->id}/cancel", [
            'cancellation_reason' => 'Salah input'
        ]);
        $staffCancel->assertStatus(403);

        // Admin cancels -> must succeed and restore stock
        $adminCancel = $this->actingAs($this->admin)->post("/sales/{$sale->id}/cancel", [
            'cancellation_reason' => 'Retur customer dan order dibatalkan resmi'
        ]);
        $adminCancel->assertSessionHas('success');

        $this->product->refresh();
        $this->assertEquals($stockAfterSale + $qty, $this->product->stock);

        $sale->refresh();
        $this->assertEquals('cancelled', $sale->status);
    }
}
