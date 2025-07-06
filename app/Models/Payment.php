<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
      protected $fillable = [
        'sale_id',
        'amount',
        'payment_date',
        'payment_method',
        'notes'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
