<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployLeave extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "employ_leaves";
    protected $fillable = [
        'id','leaveType','leaveDays','days','startDate','endDate','employ_id','branch_id'
    ];

    public function employee(){
        return $this->belongsTo(Employ::class,'employ_id');
    }
}
