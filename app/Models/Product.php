<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'brand',
        'model_series',
        'unit',
        'purchase_price',
        'selling_price',
        'stock',
        'min_stock',
        'location',
        'description',
        'image',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Check if product has been used in any transactions.
     * Business rule: Products used in transactions cannot be hard deleted.
     */
    public function hasTransactions(): bool
    {
        return $this->purchaseItems()->exists() || $this->saleItems()->exists();
    }

    /**
     * Helper to get product image URL or fallback placeholder
     */
    public function imageUrl(): string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            if (str_starts_with($this->image, 'images/')) {
                return asset($this->image);
            }
            return asset('storage/' . $this->image);
        }

        return asset('images/products/placeholder.svg');
    }
}
