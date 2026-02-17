<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

class Employ extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, LogUserDetailIdTrait;

    protected $table = 'employes';

    protected $fillable = [
        'id','first_name','middle_name','last_name','position','email','password','mobile',
        'branch_id','detail_id','date_of_birth','username','role','image','gender','merchant_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
               'first_name','middle_name','last_name','position','email','password','mobile',
               'branch_id','detail_id','date_of_birth','username','role','gender','merchant_id'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a Employ : {$this->username}";
            })
            ->useLogName('Employ');
    }

    public function payroll(){
        return $this->hasMany(Payroll::class,'employ_id');
    }

    public function merchant(){
        return $this->belongsTo(Merchant::class,'merchant_id');
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name ). ' '. ucfirst($this->middle_name) .' '. ucfirst($this->last_name);
    }

    public function message_thread()
    {
        return $this->hasMany(MessageThread::class,'employee_id','id');
    }
}
