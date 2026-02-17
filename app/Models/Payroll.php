<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "payroll";

    protected $fillable = [
        'id','employ_id','salary','allowance','deduction','total'
    ];

    protected $dates = ['created_at'];
    public function employes(){
        return $this->belongsTo(Employ::class,'employ_id');
    }

    public static function getPayrolls($id) {
        $amount = Payroll::whereHas('employes', function ($query) use ($id) {
                    $query->where('branch_id', $id);
                })
                ->sum('total');

        if(is_null($amount)) {
            return '0';
        }
        return $amount;
    }
}
