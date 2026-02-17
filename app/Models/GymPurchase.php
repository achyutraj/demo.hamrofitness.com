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
 *     schema="GymPurchase",
 *     title="Membership Subscription",
 *     description="Membership Subscription Model",
 *     @OA\Property(property="id", type="integer", example=123),
 *     @OA\Property(property="membership_id", type="integer", example=123),
 *     @OA\Property(
 *         property="membership",
 *         ref="#/components/schemas/GymMembership"
 *     ),
 *     @OA\Property(property="purchase_amount", type="number", format="float", example=100.50),
 *     @OA\Property(property="paid_amount", type="number", format="float", example=90.00),
 *     @OA\Property(property="discount", type="number", format="float", example=10.50),
 *     @OA\Property(property="purchase_date", type="string", format="date-time", example="2024-07-15T12:00:00Z"),
 *     @OA\Property(property="start_date", type="string", format="date-time", example="2024-07-15T12:00:00Z"),
 *     @OA\Property(property="next_payment_date", type="string", format="date", example="2024-08-15"),
 *     @OA\Property(property="expires_on", type="string", format="date-time", example="2025-07-15T12:00:00Z"),
 *     @OA\Property(property="remarks", type="string", example="Subscription details"),
 *     @OA\Property(property="amount_to_be_paid", type="number", format="float", example=10.50),
 *     @OA\Property(property="payment_required", type="boolean", example=true),
 *     @OA\Property(property="status", type="integer", example=1),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", example=null),
 *     @OA\Property(property="is_renew", type="boolean", example=false),
 * )
 */

class GymPurchase extends Model
{
    use HasFactory ,SoftDeletes, LogsActivity, LogUserDetailIdTrait;

    protected $table = "gym_client_purchases";

    protected $guarded = ['id'];

    protected $fillable = ['client_id','membership_id','detail_id','purchase_amount','paid_amount','discount','purchase_date','start_date','next_payment_date','is_renew','expires_on','remarks','amount_to_be_paid','payment_required','status','deleted_at','is_redeem'];

    protected $dates = ['purchase_date', 'next_payment_date', 'start_date', 'expires_on','deleted_at'];

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
            ->logOnly([
                'client_id','membership_id','detail_id','purchase_amount','paid_amount','discount','purchase_date','start_date','next_payment_date','is_renew','expires_on','remarks','amount_to_be_paid',
                'payment_required','status','deleted_at','is_redeem'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a GymPurchase: {$this->membership->title} for Client : {$this->client->username}";
            })
            ->useLogName('GymPurchase');
    }

    public function membership() {
        return $this->belongsTo(GymMembership::class, 'membership_id')->withTrashed();
    }

    public function client() {
        return $this->belongsTo(GymClient::class, 'client_id');
    }

    public static function clientPurchases($id) {
        return GymPurchase::select('*')->selectRaw('amount_to_be_paid - paid_amount as diff')
            ->where('payment_required','yes')->where('status','!=','pending')
            ->where('client_id',$id)->get();
    }

    public static function clientMembershipPurchases($businessId,$clientId,$membershipId) {
        return GymPurchase::where('detail_id',$businessId)->where('client_id',$clientId)
            ->where('membership_id',$membershipId)->where('payment_required','yes')->latest()->get();
    }

    public static function clientLatestMembership($id) {
        return GymPurchase::where('client_id', '=', $id)
            ->whereNotNull('membership_id')
            ->orderBy('id', 'desc')
            ->first();
    }

    public static function purchaseByBusiness($businessID) {
        return GymPurchase::where('detail_id', '=', $businessID)->get();
    }

    public function extend_subscription() {
        return $this->hasMany(GymMembershipExtend::class);
    }

    public function freeze_subscription() {
        return $this->hasMany(GymMembershipFreeze::class);
    }

    public function scopeExpireSubscriptionInDays($query,$businessId,$days,$limit = null){
        $start =  Carbon::today()->format('Y-m-d');
        $end =  Carbon::today()->addDays($days)->format('Y-m-d');
        return  $query->where('detail_id',$businessId)
            ->whereBetween('expires_on',[$start,$end])
            ->orderBy('expires_on','asc')
            ->limit($limit)
            ->get();
    }

    public function scopeExpiredSubscription($query,$businessId,$limit = null){
        return  $query->where('detail_id',$businessId)->where('status','active')->where('payment_required','yes')
            ->limit($limit)->get();
    }

    public static function getSubscriptionExpire($membershipId,$start_date) {
        $membership = GymMembership::find($membershipId);
        $duration = $membership->duration;
        $type = $membership->duration_type;
        $startDate = Carbon::createFromFormat('m/d/Y',$start_date);
        switch ($type){
            case ('minute'):
                $expire = $startDate->addMinutes($duration);
                break;
            case ('month'):
                $expire = $startDate->addMonths($duration);
                break;
            case ('year'):
                $expire =  $startDate->addYears($duration);
                break;
            default:
                $expire =  $startDate->addDays($duration);
                break;
        }
        return $expire;
    }

}