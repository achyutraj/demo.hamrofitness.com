<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankLedger extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id','bank_account_id','transaction_type','amount','transaction_method','date','remarks'];

    public function bankAccount(){
        return $this->hasMany(BankAccount::class,'bank_account_id');
    }
}
