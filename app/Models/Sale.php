<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_no',
        'date',
        'total_amount',
        'discount',
        'vat',
        'grand_total',
        'paid_amount',
        'due_amount',
        'notes'
    ];
    protected $casts = [
    'date' => 'date',
];

    public function details()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function journalEntries()
    {
        return $this->morphMany(JournalEntry::class, 'reference');
    }
      public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
