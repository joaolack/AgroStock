<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'name', 
        'description',
        'selling_price', 
        'cost_price', 
        'category_id', 
        'supplier_id',
        'stock_quantity', 
        'minimum_stock',
        'expiration_date',
    ];

    protected $casts = [
        'expiration_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getStockStatusAttribute() 
    {
        if ($this->stock_quantity <= 0) {
            return 'Em Falta';
        }
        if ($this->stock_quantity <= $this->minimum_stock) {
            return 'Estoque Baixo';
        }
        return 'Estoque Normal';
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function availableBatches()
    {
        return $this->batches()
            ->where('quantity', '>', 0)
            ->orderByRaw('expiration_date IS NULL')
            ->orderBy('expiration_date')
            ->orderBy('created_at')
            ->orderBy('id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
   
}
