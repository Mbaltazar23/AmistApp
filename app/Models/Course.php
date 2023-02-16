<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

     
    protected $fillable = [
        'dni',
        'name',
        'college_id',
        'status',
        'created_at'
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}
