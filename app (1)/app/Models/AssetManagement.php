<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetManagement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "assets";

    protected $fillable = [
        'id','branch_id','tag','name','brand_name','supplier_id','quantity','asset_model','status','purchase_date'
    ];

    public function employes(){
        return $this->belongsToMany(Employ::class,'employes','employ_id','id');
    }

    public function employeeAssets(){
        return $this->hasMany(EmployAsset::class,'asset_id');
    }

    public function suppliers(){
        return $this->belongsTo(GymSupplier::class,'supplier_id');
    }
}
