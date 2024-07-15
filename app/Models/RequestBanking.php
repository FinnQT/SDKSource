<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestBanking extends Model
{
    use HasFactory;
    public $username;
    public $password ;
    public $request_id ;
    public $bank_code ;
    public $money ;
    public $url_callback ;
}
