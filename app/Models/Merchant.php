<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

class Merchant extends Authenticatable
{
    use HasFactory , HasApiTokens, HasProfilePhoto , Notifiable,
    TwoFactorAuthenticatable, HasRoles, LogsActivity, LogUserDetailIdTrait;

    //use Authenticatable, CanResetPassword;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
               'username','first_name','middle_name','last_name','email','password','mobile','position',
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a Merchant : {$this->username}";
            })
            ->useLogName('Merchant');
    }

    public static $addUserRules = [
        'first_name' => 'required|alpha_spaces',
        'middle_name' => 'nullable|alpha_spaces',
        'last_name' => 'required|alpha_spaces',
        'mobile' => 'required|digits:10|unique:merchants',
        'password' => 'nullable',
        'email' => 'required|email|unique:merchants',
        'username' => 'required|unique:merchants',
        'role' => 'required|array',
    ];

    public static function updateRules($id = null)
    {
        return [
            'first_name' => 'required|alpha_spaces',
            'middle_name' => 'nullable|alpha_spaces',
            'last_name' => 'required|alpha_spaces',
            'gender' => 'required',
            'mobile' => 'required|digits:10|unique:merchants,mobile,' . $id,
            'password' => 'nullable',
            'email' => 'required|email'
        ];
    }

    public function isAdmin()
    {
        return $this->is_admin ? true : false;
    }

    public function employees()
    {
        return $this->hasMany(Employ::class, 'merchant_id', 'id');
    }

    public static $notification = [
        'notificationMeg' => 'required',
        'merchant' => 'required'
    ];

    public function business()
    {
        return $this->hasMany(MerchantBusiness::class, 'merchant_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(ReviewComment::class, 'merchant_id', 'id');
    }

    public function common()
    {
        return $this->belongsTo(Common::class, 'detail_id');
    }

    public function gymsetting()
    {
        return $this->hasOne(GymSetting::class, 'detail_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'business_cat');
    }

    public function message_thread()
    {
        return $this->hasMany(MessageThread::class,'merchant_id','id');
    }

    public static function getByBusinessID($detailID)
    {
        return Merchant::where('detail_id', '=', $detailID)->first();
    }

    public static function merchantDetail($businessId, $id)
    {
        return Merchant::leftJoin('merchant_businesses', 'merchants.id', '=', 'merchant_businesses.merchant_id')
            ->where('merchant_businesses.detail_id', $businessId)
            ->where('merchants.id', $id)
            ->select('merchants.*')
            ->first();
    }

    public static function recentlyActive()
    {
        return Merchant::select('common_details.title', 'merchants.first_name','merchants.middle_name','merchants.last_name','merchants.last_activity', 'merchants.image')
            ->where('merchants.last_activity', '>=', Carbon::today('Asia/Kathmandu')->toDateTimeString())
            ->where('is_admin',0)->where('is_logged_in',1)
            ->leftJoin('merchant_businesses', 'merchant_businesses.merchant_id', '=', 'merchants.id')
            ->leftJoin('common_details', 'merchant_businesses.detail_id', '=', 'common_details.id')
            ->leftJoin('gym_settings', 'merchant_businesses.detail_id', '=', 'gym_settings.detail_id')
           ->orderBy('merchants.last_activity', 'desc')
            ->get();
    }

    public static function userActiveInDays($days)
    {
        return Merchant::select('common_details.title', 'merchants.first_name','merchants.middle_name','merchants.last_name','merchants.last_activity', 'merchants.image')
            ->where('merchants.last_activity', '<=', Carbon::today('Asia/Kathmandu')->subDays($days)->toDateString())
            ->where('is_admin',0)->where('is_logged_in',0)
            ->leftJoin('merchant_businesses', 'merchant_businesses.merchant_id', '=', 'merchants.id')
            ->leftJoin('common_details', 'merchant_businesses.detail_id', '=', 'common_details.id')
            ->leftJoin('gym_settings', 'merchant_businesses.detail_id', '=', 'gym_settings.detail_id')
            ->orderBy('merchants.last_activity', 'desc')
            ->get();
    }

    //User who are not login till now
    public static function notActiveUsers()
    {
       return Merchant::select('common_details.title', 'merchants.first_name','merchants.middle_name','merchants.last_name','merchants.last_activity','merchants.image')
            ->whereNull('merchants.last_activity')
            ->where('is_admin',0)->where('is_logged_in',0)
            ->leftJoin('merchant_businesses', 'merchant_businesses.merchant_id', '=', 'merchants.id')
            ->leftJoin('common_details', 'merchant_businesses.detail_id', '=', 'common_details.id')
            ->leftJoin('gym_settings', 'merchant_businesses.detail_id', '=', 'gym_settings.detail_id')
            ->get();
    }

    public static function trialExpiringInDays($days)
    {
        return Merchant::select('common_details.title', 'merchants.trial_end_date', 'gym_settings.image')
            ->where('trial_end_date', '>', Carbon::now('Asia/Kathmandu'))
            ->where('trial_end_date', '<=', Carbon::today('Asia/Kathmandu')->addDays($days)->toDateString())
            ->leftJoin('merchant_businesses', 'merchant_businesses.merchant_id', '=', 'merchants.id')
            ->leftJoin('common_details', 'merchant_businesses.detail_id', '=', 'common_details.id')
            ->leftJoin('gym_settings', 'merchant_businesses.detail_id', '=', 'gym_settings.detail_id')
            ->orderBy('merchants.trial_end_date', 'asc')
            ->get();
    }

    public static function trialExpiredInDays($days)
    {
        return Merchant::select('common_details.title', 'merchants.trial_end_date', 'gym_settings.image')
            ->where('trial_end_date', '>=', Carbon::today('Asia/Kathmandu')->subDays($days)->toDateString())
            ->where('trial_end_date', '<=', Carbon::today('Asia/Kathmandu')->toDateString())
            ->leftJoin('merchant_businesses', 'merchant_businesses.merchant_id', '=', 'merchants.id')
            ->leftJoin('common_details', 'merchant_businesses.detail_id', '=', 'common_details.id')
            ->leftJoin('gym_settings', 'merchant_businesses.detail_id', '=', 'gym_settings.detail_id')
            ->orderBy('merchants.trial_end_date', 'desc')
            ->get();
    }

    public static function getByEmail($email)
    {
        return Merchant::where('email', $email)->first();
    }

    public function extend_subscription() {
        return $this->hasMany(GymMembershipExtend::class);
    }
    protected $guarded = ['id'];

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name ). ' '. ucfirst($this->middle_name) .' '. ucfirst($this->last_name);
    }
}
