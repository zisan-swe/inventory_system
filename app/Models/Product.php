<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SaleItem;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'purchase_price',
        'sell_price',
        'opening_stock',
        'current_stock'
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleItem::class);
    }

}
