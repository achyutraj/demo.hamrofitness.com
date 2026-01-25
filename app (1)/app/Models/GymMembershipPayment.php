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
 *     schema="GymMembershipPayment",
 *     title="GymMembership Payment",
 *     description="GymMembership Payment Model",
 *     @OA\Property(property="id", type="integer", example=123),
 *     @OA\Property(
 *         property="purchase",
 *         ref="#/components/schemas/GymPurchase"
 *     ),
 *     @OA\Property(property="payment_id", type="integer", example=987),
 *     @OA\Property(property="payment_amount", type="number", format="float", example=100.50),
 *     @OA\Property(property="payment_date", type="string", format="date-time", example="2024-07-15T12:00:00Z"),
 *     @OA\Property(property="payment_source", type="string", example="Credit Card"),
 *     @OA\Property(property="remarks", type="string", example="Payment details"),
 * )
 */
class GymMembershipPayment extends Model
{
    use HasFactory,SoftDeletes , LogsActivity, LogUserDetailIdTrait;

    protected $table = 'gym_membership_payments';

    protected $dates = ['payment_date','deleted_at'];

    protected $dateFormat = 'Y-m-d';

    protected $guarded = ['id'];

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
        $detailId = $user ? $user->detail_id : null;

        return LogOptions::defaults()
            ->logOnly([
               'user_id','purchase_id','detail_id','payment_amount','payment_source','payment_date','remarks'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a GymMembershipPayment of {$this->purchase->membership->title} for Client ID: {$this->client->username}";
            })
            ->useLogName('GymMembershipPayment');
    }

    public function client() {
        return $this->belongsTo(GymClient::class, 'user_id');
    }

    public function purchase() {
        return $this->belongsTo(GymPurchase::class, 'purchase_id');
    }

    public function businessBranches() {
        return $this->belongsTo(Common::class, 'detail_id', 'id');
    }

    public static function rules($action) {
        $rules = [
            'custom' => [
                'client' => 'required',
                'payment_amount' => 'required|numeric',
                'payment_source' => 'required',
                'payment_date' => 'required|date',
            ],
            'membership' => [
                'client' => 'required',
                'payment_amount' => 'required|numeric|min:1',
                'payment_source' => 'required',
                'payment_date' => 'required|date',
                'purchase_id' => 'required',
                'payment_required' => 'required',
            ],
            'edit' => [
                'payment_amount' => 'required|numeric|min:1',
                'payment_source' => 'required',
                'payment_date' => 'required|date',
                'payment_required' => 'required',
            ],
            'ajax_add' => [
                'payment_amount' => 'required|numeric|min:1',
                'payment_source' => 'required',
                'payment_date' => 'required|date',
            ]
        ];
        return $rules[$action];
    }

    public static function getCurrentBalance($id) {
        return GymMembershipPayment::has('purchase')->where('detail_id',$id)->sum('payment_amount');
    }

    public static function getLastSixMonthBalance($id) {
        $amount = GymMembershipPayment::leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'purchase_id')
            ->where('gym_membership_payments.detail_id', '=', $id)
            ->where('payment_date', '>', Carbon::now()->subMonths(6)->format('Y-m-d'))
            ->sum('payment_amount');

        if(is_null($amount)) {
            return '0';
        }

        return $amount;
    }

    public static function getWeeklySales($start,$end,$id) {
        return GymMembershipPayment::has('purchase')->where('detail_id',$id)
            ->where('payment_date', '>=', $start)->where('payment_date', '<=', $end)
            ->sum('payment_amount');
    }

    public static function getMaxSale($id) {
       return GymMembershipPayment::has('purchase')->where('detail_id', $id)->max('payment_amount');
    }

    public static function getDailySales($id) {
        return GymMembershipPayment::has('purchase')->where('detail_id', $id)
            ->where('payment_date', today())->sum('payment_amount');
    }

    public static function getAverageMonthlySales($month,$year,$id) {
        return GymMembershipPayment::has('purchase')->where('detail_id', $id)
            ->whereMonth('payment_date', $month)->whereYear('payment_date', $year)->sum('payment_amount');
    }

    public static function paymentByBusiness($businessID) {
        return GymMembershipPayment::leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')
            ->where('gym_client_purchases.detail_id', '=', $businessID)
            ->get();
    }

}
