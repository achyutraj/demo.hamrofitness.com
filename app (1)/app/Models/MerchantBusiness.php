<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantBusiness extends Model
{
    use HasFactory;

    protected $table = "merchant_businesses";

    public function business() {
        return $this->belongsTo(Common::class, 'detail_id');
    }

    public function merchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public static function findByMerchant($id) {
        return MerchantBusiness::where('merchant_id','=', $id)
            ->first();
    }

    public static function merchantBusinessDetails($businessID) {
        return MerchantBusiness::where('detail_id','=',$businessID)
            ->first();
    }

    protected $fillable = ['merchant_id', 'detail_id'];
}
