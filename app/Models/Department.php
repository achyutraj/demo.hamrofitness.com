<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

class Department extends Model
{
    use HasFactory, LogsActivity, LogUserDetailIdTrait;

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
               'name','detail_id'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a Department : {$this->name}";
            })
            ->useLogName('Department');
    }

    protected $fillable = ['name','detail_id'];

    public function devices()
    {
        return $this->belongsToMany(Device::class,'department_device','department_id','device_id');
    }

}
