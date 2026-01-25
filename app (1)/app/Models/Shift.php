<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

class Shift extends Model
{
    use HasFactory, LogsActivity, LogUserDetailIdTrait;

    protected $fillable = ['detail_id','name','slug','from_time','to_time'];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
               'detail_id','name','slug','from_time','to_time'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return " {$userId} has {$eventName} a Shift: {$this->name}";
            })
            ->useLogName('Shift');
    }

    public function clients()
    {
        return $this->belongsToMany(GymClient::class,'gym_client_shift','shift_id','client_id');
    }
}
