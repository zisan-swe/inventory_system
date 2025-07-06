<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'account_id',
        'debit',
        'credit',
        'reference_type',
        'reference_id',
        'description'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
