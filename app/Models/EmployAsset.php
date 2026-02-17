<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

class EmployAsset extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, LogUserDetailIdTrait;

    protected $table = "employ_asset";

    protected $fillable = [
        'id','employ_id','asset_id','quantity','repair_quantity','damaged_quantity','working_remarks'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
               'employ_id','asset_id','quantity','repair_quantity','damaged_quantity','working_remarks'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a EmployAsset : {$this->assets->name}";
            })
            ->useLogName('EmployAsset');
    }

    public function employee(){
        return $this->belongsTo(Employ::class,'employ_id');
    }

    public function assets(){
        return $this->belongsTo(AssetManagement::class,'asset_id');
    }

}
