<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'products_id',
        'transactions_id'
    ];

    /**
     * Get product that owns transaction item
     */
    public function product()
    {
        // return $this->hasOne(Product, 'id', 'products_id');
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }
}
