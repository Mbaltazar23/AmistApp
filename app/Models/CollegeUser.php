<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeUser extends Model
{
    use HasFactory;

    protected $table = "college_users";

    protected $fillable = [
        'id',
        'college_id',
        'user_id',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
