<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'category_id',
        'name',
        'image',
        'points',
        'stock',
        'status',
        'created_at'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchases(){
        return $this->hasMany(Purchase::class);
    }
}
