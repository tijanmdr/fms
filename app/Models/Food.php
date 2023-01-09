<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{

    protected $table = 'foods';
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
        'price',
        'hot',
        'sauce',
        'hide',
        'category'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
