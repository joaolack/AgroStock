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
   
}
