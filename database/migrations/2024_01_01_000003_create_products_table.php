<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('brand')->nullable(); // e.g., Honda Genuine Parts (AHM), Yamaha Genuine Parts (YGP), Suzuki, Kawasaki, Daytona, RCB
            $table->string('model_series')->nullable(); // e.g., Beat, Vario 160, NMAX, Aerox, Scoopy, PCX, CBR150R
            $table->string('unit')->default('pcs'); // unit: pcs, set, unit, botol, pasang
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5); // threshold for low stock alert
            $table->string('location')->nullable(); // warehouse bin/rack
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // path ke file gambar produk
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
