<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;


/**
 * @OA\Schema(
 *     schema="ProductPayment",
 *     title="Product Payment",
 *     description="Product Payment Model",
 *     @OA\Property(property="id", type="integer", example=123),
 *     @OA\Property(
 *         property="product_sale",
 *         ref="#/components/schemas/ProductSales"
 *     ),
 *     @OA\Property(property="payment_id", type="integer", example=987),
 *     @OA\Property(property="payment_amount", type="number", format="float", example=100.50),
 *     @OA\Property(property="payment_date", type="string", format="date-time", example="2024-07-15T12:00:00Z"),
 *     @OA\Property(property="payment_source", type="string", example="cash"),
 *     @OA\Property(property="remarks", type="string", example="Payment details"),
 * )
 */

class ProductPayment extends Model
{
    use HasFactory, SoftDeletes , LogsActivity, LogUserDetailIdTrait;

    protected $table = 'product_payments';

    protected $dates = ['payment_date','deleted_at'];

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
            ->logOnly(['product_sale_id','user_id','payment_id','payment_amount','payment_date',
                'payment_source','remarks'])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return " {$userId} has {$eventName} a ProductPayment for Client: {$this->client->username}";
            })
            ->useLogName('ProductPayment');
    }

    public static function rules($action) {
        $rules = [
            'custom' => [
                'client' => 'required',
                'payment_amount' => 'required|numeric|min:1',
                'payment_source' => 'required',
                'payment_date' => 'required',
            ],
            'membership' => [
                'client' => 'required',
                'payment_amount' => 'required|numeric|min:1',
                'payment_source' => 'required',
                'payment_date' => 'required',
                'product_sale_id' => 'required',
                'payment_required' => 'required',
            ],
            'ajax_add' => [
                'payment_amount' => 'required|numeric|min:1',
                'payment_source' => 'required',
                'payment_date' => 'required'
            ]
        ];
        return $rules[$action];
    }

    public function client() {
        return $this->belongsTo(GymClient::class, 'user_id');
    }

    public function product_sale() {
        return $this->belongsTo(ProductSales::class, 'product_sale_id');
    }

    public static function getCurrentBalance($id) {
        return ProductPayment::has('product_sale')->where('branch_id', $id)->sum('payment_amount');
    }

    public static function getLastSixMonthBalance($id) {
        $amount = ProductPayment::leftJoin('product_sales', 'product_sales.id', '=', 'product_sale_id')
            ->where('product_payments.branch_id', '=', $id)
            ->where('payment_date', '>', Carbon::now()->subMonths(6)->format('Y-m-d'))
            ->sum('payment_amount');

        if(is_null($amount)) {
            return '0';
        }

        return $amount;
    }

    public static function getDailySales($id) {
        return ProductPayment::has('product_sale')->where('branch_id', $id)
            ->where('payment_date', today())->sum('payment_amount');
    }

    public static function getWeeklySales($start,$end,$id) {
        return ProductPayment::where('branch_id', $id)
            ->where('payment_date', '>=', $start)->where('payment_date', '<=', $end)
            ->sum('payment_amount');
    }


    public static function getMaxSale($id) {
        return ProductPayment::has('product_sale')->where('branch_id', $id)->max('payment_amount');
    }

    public static function getAverageMonthlySales($month,$year,$id) {
        return ProductPayment::has('product_sale')->where('branch_id', $id)
            ->whereMonth('payment_date', $month) ->whereYear('payment_date', $year)
            ->sum('payment_amount');
    }

    public function getPaymentType($value){

        if ($value == 'cash') {
            return "<div class='font-dark'> Cash <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'cheque') {
            return "<div class='font-dark'> Cheque <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'esewa') {
            return "<div class='font-dark'> Esewa <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'khalti') {
            return "<div class='font-dark'> Khalti <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'phone_pay') {
            return "<div class='font-dark'> Fonepay <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'ime_pay') {
            return "<div class='font-dark'> Imepay <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'other') {
            return "<div class='font-dark'> Other <i class='fa fa-money'></i> </div>";
        }
        if ($value == 'credit_card') {
            return "<div class='font-dark'> Credit Card <i class='fa fa-credit-card'></i> </div>";
        }
        if ($value == 'debit_card') {
            return "<div class='font-dark'> Debit Card <i class='fa fa-cc-visa'></i> </div>  ";
        } else {
            return "<div class='font-dark'> Net Banking <i class='fa fa-internet-explorer '></i> </div>";
        }
    }

}
