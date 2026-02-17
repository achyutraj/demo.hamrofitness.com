<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['asset_id','added_by','detail_id','service_by','service_date','next_service_date','remarks'];

    protected $casts = [
        'service_date' => 'date',
        'next_service_date' => 'date',
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class,'added_by');
    }

    public function assets(){
        return $this->belongsTo(AssetManagement::class,'asset_id');
    }
}
