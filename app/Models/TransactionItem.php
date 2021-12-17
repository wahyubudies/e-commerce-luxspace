<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->belongsTo(Product::class, 'products_id', 'id');
        //return $this->hasOne(Product::class, 'id', 'products_id');        
    }
}
