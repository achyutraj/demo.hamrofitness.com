<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedeemPoint extends Model
{
    use HasFactory;

    protected $fillable = ['title','redeem_points','start_date','end_date','status','is_redeem_reduce','detail_id','membership_id'];

    protected $dates = ['start_date', 'end_date'];

    public static function rules($action)
    {
        $rules = [
            'add' => [
                'title' => 'required',
                'redeem_points' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'membership' => 'required',
            ]
        ];
        return $rules[$action];
    }

    public function scopeActive($query){
        return $query->where('status',1);
    }

}
