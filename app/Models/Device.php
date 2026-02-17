<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

class Device extends Model
{
    use HasFactory, LogsActivity, LogUserDetailIdTrait;
    protected $fillable = ['detail_id','name','code','serial_num','port_num','ip_address','device_model','device_type','device_status','vendor_name'];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
               'detail_id','name','code','serial_num','port_num','ip_address','device_model','device_type','device_status','vendor_name'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a Device : {$this->name}";
            })
            ->useLogName('Device');
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class,'department_device','device_id','department_id');
    }

    public function clients()
    {
        return $this->belongsToMany(GymClient::class,'device_gym_clients','device_id','client_id')
                    ->withPivot(['is_denied', 'is_device_deleted','is_expired']);
    }

}
