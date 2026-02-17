<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'banks';
    protected $fillable = ['branch_id', 'name'];

    public function branches(){
        return $this->hasMany(BankBranch::class,'bank_id');
    }
}
