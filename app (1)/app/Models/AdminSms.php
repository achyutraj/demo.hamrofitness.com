<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSms extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'status', 'phone'];

    const UPDATED_AT = null;

    public function recipient()
    {
        return $this->belongsTo(Merchant::class, 'recipient_id');
    }

    public function sender()
    {
        return $this->belongsTo(Merchant::class, 'sender_id');
    }
}
