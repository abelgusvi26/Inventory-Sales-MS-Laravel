<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Inventory & Sales Management System (Heavy Equipment)
|--------------------------------------------------------------------------
*/

// Public Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Admin & Staff)
Route::middleware(['auth'])->group(function () {
    // Dashboard & Overview
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 1. Products & Inventory Routes
    // Specific static sub-paths MUST be registered BEFORE wildcard `{product}`
    Route::middleware(['role:admin,staff'])->group(function () {
        Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
    // Public/all roles can browse catalog & product details
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // 2. Sales & Orders Routes
    // Specific static sub-path `/sales/create` MUST be registered BEFORE wildcard `{sale}`
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

    // 3. Categories Routes
    Route::middleware(['role:admin,staff'])->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

    // 4. Warehouse Purchases & Suppliers (Admin & Staff only)
    Route::middleware(['role:admin,staff'])->group(function () {
        Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('suppliers', SupplierController::class);
    });

    // Admin Exclusive Routes
    Route::middleware(['role:admin'])->group(function () {
        // User & Role Management
        Route::resource('users', UserController::class);

        // Transaction Cancellations with Stock Restoration
        Route::post('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
        Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
    });

    // 5. System Documentation & ERD Page (UTS Reference)
    Route::get('/documentation', function () {
        return view('documentation.index');
    })->name('documentation');
});

