<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessRenewHistory extends Model
{
    use HasFactory;
    protected $table = 'business_renew_history';

    public static $rules = [
        'package_offered' => 'required',
        'package_amount' => 'required',
        'renew_start_date' => 'required|date',
        'renew_end_date' => 'nullable|date',
        'remark' => 'nullable|string',
    ];

    protected $dates = ['renew_start_date', 'renew_end_date'];

    protected $guarded = ['id'];
}
