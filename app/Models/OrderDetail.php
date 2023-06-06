<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order', 'user', 'food_id', 'beverage_id', 'entree', 'serve', 'quantity', 'note'
    ];

    protected $hidden = [];
}
