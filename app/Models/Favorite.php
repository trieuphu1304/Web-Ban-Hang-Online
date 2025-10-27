<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorites';
    protected $fillable = ['user_id', 'product_id'];

     public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    // Thêm relationship với User nếu cần
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}