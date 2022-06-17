<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class AuthModel extends Model
{
    use HasApiTokens,HasFactory;
    protected $table = 'auth';
    protected $fillable = ['name', 'email', 'password'];
}
