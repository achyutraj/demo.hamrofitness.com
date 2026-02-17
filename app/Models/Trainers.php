<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

class Trainers extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, LogUserDetailIdTrait;

    protected $table = 'trainers';

    protected $fillable = [
        'id','name','address','email','phone'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
              'name','address','email','phone'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return " {$userId} has {$eventName} a Trainers : {$this->name}";
            })
            ->useLogName('Trainers');
    }

    public function class_schedules(){
        return $this->hasMany(ClassSchedule::class,'trainer_id');
    }}
