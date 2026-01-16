<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MembershipHistory extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'membership_id',
        'action_type', // 'created', 'updated', 'deleted'
        'field_name',
        'old_value',
        'new_value',
        'changed_by',
        'change_reason',
        'branch_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'membership_id',
                'action_type',
                'field_name',
                'old_value',
                'new_value',
                'changed_by',
                'change_reason',
                'branch_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return "Membership history has been {$eventName}";
            })
            ->useLogName('MembershipHistory');
    }

    public function membership()
    {
        return $this->belongsTo(GymMembership::class);
    }

    public function changedByUser()
    {
        return $this->belongsTo(Merchant::class, 'changed_by');
    }
}
