<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

class Classes extends Model
{
    use HasFactory, LogsActivity, LogUserDetailIdTrait;

    protected $table="classes";

    protected $fillable = [
        'id','class_name'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
               'class_name','branch_id'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a Classes : {$this->class_name}";
            })
            ->useLogName('Classes');
    }

    public function class_schedules(){
        return $this->hasMany(ClassSchedule::class,'class_id');
    }
}
