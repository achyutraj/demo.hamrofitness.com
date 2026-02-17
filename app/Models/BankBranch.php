<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    use HasFactory;

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function bank_accounts()
    {
        return $this->hasMany(BankAccount::class, 'bank_branch_id');
    }
}
