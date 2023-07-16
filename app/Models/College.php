<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class College extends Model {

    use HasFactory;

    protected $table = 'colleges';
    
    protected $fillable = [
        'dni',
        'name',
        'address',
        'phone',
        'stock_alumns',
        'status',
        'created_at'
    ];

    public function usersCollege() {
        return $this->hasMany(CollegeUser::class);
    }

    public function courses() {
        return $this->hasMany(Course::class);
    }

}
