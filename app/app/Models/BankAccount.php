<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'bank_id', 'bank_branch_id', 'account_number', 'balance'];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function branch()
    {
        return $this->belongsTo(BankBranch::class, 'bank_branch_id');
    }

    public function ledgers(){
        return $this->hasMany(BankLedger::class, 'bank_account_id');
    }
}
