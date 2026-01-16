<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

class GymSetting extends Model
{
    use HasFactory,LogsActivity, LogUserDetailIdTrait;

    protected $table = 'gym_settings';

    // protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
               'detail_id','options','front_image','image','customer_logo','sms_status','sms_username',
               'sms_api_url','sms_password','sms_sender_id','is_old','campaign_id','route_id'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a GymSetting";
            })
            ->useLogName('GymSetting');
    }

    public static function GetMerchantInfo($detail_id) {
        return GymSetting::where('detail_id', $detail_id)->first();
    }

    public function currency() {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function getMerchantID($businessID) //return merchant id only
    {
        $merchant =  Merchant::where('detail_id','=',$businessID)->first();
        return $merchant->id;
    }

    public static function rules($action) {
        $rules = [
            'add' => [
                'mobile' => 'required',
                'address' => 'required',
                'gstin' => 'nullable|size:15',
            ],
            'image' => [
                'file' => 'image',
            ],
            'update' => [
                'gstin' => 'required|size:15',
            ],
            'id' => [
                'id' => 'required',
            ],
            'activity' => [
                'level' => 'required',
                'activity' => 'required'
            ],
            'training' => [
                'days' => 'required',
                'activity' => 'required',
                'sets' => 'required',
                'level' => 'required',
                'repetition' => 'required',
                'weights' => 'required',
                'restTime' => 'required'
            ],
            'editTraining' => [
                'days' => 'required',
                'activity' => 'required',
                'sets' => 'required',
                'repetition' => 'required',
                'weights' => 'required',
                'restTime' => 'required'
            ]
        ];

        return $rules[$action];

    }

    public static function getOptions(): array {
        return [
            'customer_register_status' => true,
            'customer_payment_status' => true,
            'membership_due_status' => true,
            'membership_renew_status' => true,
            'membership_extend_status' => true,
            'membership_expire_status' => true,
            'membership_due_payment_status' => true,
            'customer_anniversary_status' => false,
            'customer_birth_status' => true,
            'customer_register_notify' => 'sms',
            'customer_payment_notify' => 'sms',
            'membership_due_notify' => 'sms',
            'membership_due_notify_days' => '1',
            'membership_due_pay_notify' => 'sms',
            'membership_due_pay_notify_days' => '1',
            'membership_expire_notify' => 'sms',
            'membership_expire_notify_days' => '1',
            'membership_renew_notify' => 'sms',
            'membership_extend_notify' => 'sms',
            'customer_anniversary_notify' => 'sms',
            'customer_birthday_notify' => 'sms',
            'subscription_expire_days' => '45',
            'product_expire_days' => '45',
            'membership_feature' => true,
            'locker_feature' => false,
            'asset_feature' => false,
            'product_feature' => false,
            'body_measurement_feature' => false,

            //locker
            'locker_expire_status' => false,
            'locker_due_status' => false,
            'locker_expire_notify' => 'sms',
            'locker_expire_notify_days' => '1',
            'locker_due_notify' => 'sms',
            'locker_due_notify_days' => '1',
        ];
    }

    public function getOption($name) :string
    {
        return $this->getOptions()[$name];
    }

    public function updateOptions($data) {
        $options = (object)array_merge((array)$this->getOptions(),$data);
        $this->options = json_encode($options);
        $this->save();
    }
}
