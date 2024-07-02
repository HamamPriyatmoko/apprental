<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'address',
        'phone_number',
        'profile_picture',
        'gender',
    ];

    public function user() //relasi agar dapat diakses dengan mudah
    {
        return $this->belongsTo(User::class);
    }
}
