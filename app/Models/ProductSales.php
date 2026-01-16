<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

/**
 * @OA\Schema(
 *     schema="ProductSales",
 *     title="Product Purchase",
 *     description="Product Purchase Model",
 *     @OA\Property(property="id", type="integer", example=123),
 *     @OA\Property(property="products", type="string", example="products array"),
 *     @OA\Property(property="total_amount", type="number", format="float", example=100.50),
 *     @OA\Property(property="paid_amount", type="number", format="float", example=90.00),
 *     @OA\Property(property="next_payment_date", type="string", format="date", example="2024-08-15"),
 *     @OA\Property(property="payment_required", type="string", example="yes"),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", example=null),
 * )
 */
class ProductSales extends Model
{
    use HasFactory, LogsActivity, LogUserDetailIdTrait, SoftDeletes;

    protected $table="product_sales";

    protected $fillable = [
        'id','customer_type','client_id','employ_id','customer_name','product_name','product_price','product_quantity','product_discount','product_amount','branch_id'
    ];

    protected $dates = [ 'created_at','next_payment_date'];

    public function getActivitylogOptions(): LogOptions
    {
        $user = null;

        if (auth()->guard('merchant')->check()) {
            $user = auth()->guard('merchant')->user();
        } elseif (auth()->guard('customer')->check()) {
            $user = auth()->guard('customer')->user();
        }elseif (auth()->guard('customer-api')->check()) {
            $user = auth()->guard('customer-api')->user();
        }

        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly(['client_id','product_name','product_price', 'product_quantity',
                'product_discount','product_amount','branch_id'])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return " {$userId} has {$eventName} a ProductSale for Client: {$this->customer->username}";
            })
            ->useLogName('ProductSale');
    }

    public static function clientPurchases($id) {
        return ProductSales::select('*')->selectRaw('total_amount - paid_amount as diff')->where('payment_required','yes')
                ->where('status','active')->where('client_id',$id)->get();
    }

    public static function productByBusiness($businessID) {
        return ProductSales::where('branch_id', '=', $businessID)->get();
    }

    public function customer() {
        return $this->belongsTo(GymClient::class,'client_id');
    }

    public function scopeDueProductPayment($query,$businessId,$limit = null){
        return  $query->where('branch_id',$businessId)->where('status','active')
        ->where('payment_required','yes')
            ->limit($limit)->get();
    }

    public function productPayment(){
        return $this->hasMany(ProductPayment::class,'product_sale_id','id');
    }

}
