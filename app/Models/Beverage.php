<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beverage extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'photo',
        'allergic',
        'allergic_id',
        'ingredients',
        'hide',
        'price', 
        'category'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
