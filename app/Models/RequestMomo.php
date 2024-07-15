<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestMomo extends Model
{
    use HasFactory;
    public $merchantId;
    public $transId ;
    public $storeId ;
    public $amount ;
    public $payMethod ;
    public $desc ;
    public $title ;
    public $ipnUrl ;
    public $redirectUrl ;
    public $failedUrl ;
    public $signature ;
}
