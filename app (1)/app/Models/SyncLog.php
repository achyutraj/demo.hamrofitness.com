<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    use HasFactory;

    protected $fillable = ['client_id','device_id','synced','sync_on'];

    public function client(){
        return $this->belongsTo(GymClient::class);
    }

    public function device(){
        return $this->belongsTo(Device::class);
    }
    
}
