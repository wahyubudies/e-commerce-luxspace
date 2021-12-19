<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'users_id',        
        'products_id'
    ];

    /**
     * Get product owns cart
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id', 'id');
        // return $this->hasOne(Product::class, 'id', 'products_id');
    }

    /**
     * Get user that owns carts
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

}
