<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'uuid',
        'username',
        'protect_code',
        'email',
        'password',
        'name',
        'location',
        'CCCD',
        'balance',
        'status',
        'link',
        'is_admin',
        'ip_address',
        'last_login_ip'
    ];

}
