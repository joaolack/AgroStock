<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip_code',
        'notes',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    //Relacionamentos
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function productBatches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    //Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
