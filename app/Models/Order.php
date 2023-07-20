<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $hidden = [];

    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFinished($query)
    {
        return $query->where('status', 2);
    }

    public function scopeDeleted($query)
    {
        return $query->where('status', 3);
    }

    public function scopeCheckStatus($query, $status) {
        return $query->where('status', '!=', $status);
    }
}
