<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GymSupplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gym_suppliers';

    protected $fillable = [
        'name', 'address', 'email', 'phone','branch_id'
    ];

    protected $guarded = ['id'];

    public static function rules($action)
    {
        $rules = [
            'add' => [
                'name' => 'required',
                'address' => 'nullable',
                'phone' => 'required|digits:10',
                'email' => 'nullable|email|unique:gym_suppliers',
            ]
        ];
        return $rules[$action];
    }

    public function product(){
        return $this->hasMany(Product::class,'supplier_id');
    }
}
