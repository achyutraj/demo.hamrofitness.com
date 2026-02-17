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
 *     schema="GymMembership",
 *     title="GymMembership",
 *     description="GymMembership Model",
 *     @OA\Property(property="id", type="integer", example=123),
 *     @OA\Property(property="title", type="string", example="Membership Plan"),
 *     @OA\Property(property="price", type="string", example="100"),
 *     @OA\Property(property="duration", type="string", example="100"),
 *     @OA\Property(property="duration_type", type="string", enum={"day", "week", "month", "year"}, example="month"),
 *     @OA\Property(property="details", type="string", example="Membership details"),
 * )
 */

class GymMembership extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, LogUserDetailIdTrait;

    protected $table = 'gym_memberships';

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
               'title','price','duration','duration_type','details'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a GymMembership : {$this->title}";
            })
            ->useLogName('GymMembership');
    }

    public function subCategory() {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public static function rules($action, $subId, $id = null) {
        $rules = [
            'add' => [
                'title' => 'required|unique:gym_memberships,title,NULL,id,business_category_id,' . $subId,
                'price' => 'required|numeric',
                'duration' => 'required',
                'duration_type' => 'required',
                ],
            'edit' => [
                'title' => 'required|unique:gym_memberships,title,' . $id . ',id,business_category_id,' . $subId,
                'price' => 'required|numeric',
                'duration' => 'required',
                'duration_type' => 'required',
            ]
        ];
        return $rules[$action];
    }

    public static function merchantMembershipDetail($memID, $businessID) {
        return GymMembership::where('id', '=', $memID)->where('detail_id', '=', $businessID)->first();
    }

    public static function membershipsForSelect($id) {
        return GymMembership::select('id', 'title')->where('detail_id', '=', $id)->get();
    }

    public static function membershipByBusiness($businessID) {
        return GymMembership::where('detail_id', '=', $businessID)->get();
    }

    public static function membershipByName($name, $businessID) {
        return GymMembership::where('title', $name)->where('detail_id', '=', $businessID)->first();
    }

    public function clients() {
        return $this->belongsToMany(GymClient::class,'gym_client_purchases','membership_id','client_id');
    }

    public function subscriptions() {
        return $this->hasMany(GymPurchase::class,'membership_id','id');
    }

    public function membershipHistories()
    {
        return $this->hasMany(MembershipHistory::class,'membership_id');
    }

    public function trackChange($actionType, $fieldName = null, $oldValue = null, $newValue = null, $changeReason = null)
    {
        $user = auth()->guard('merchant')->user();

        $this->membershipHistories()->create([
            'action_type' => $actionType,
            'field_name' => $fieldName,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'changed_by' => $user ? $user->id : null,
            'change_reason' => $changeReason,
            'branch_id' => $this->detail_id,
        ]);
    }
}
