<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseData extends Model
{
    use HasFactory;
    public $ErrorCode;
    public $Description;
    public $TransactionID;
    public $PartnerTransactionID;
    public $CardAmount;
    public $VendorTransactionID;
    public function __construct()
    {
        $this->ErrorCode = null;
        $this->Description = null;
        $this->TransactionID = null;
        $this->PartnerTransactionID = null;
        $this->CardAmount = null;
        $this->VendorTransactionID = null;
    }
}
