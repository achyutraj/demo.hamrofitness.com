<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSms extends Model
{
    use HasFactory;

    protected $table = 'customer_sms';
    protected $fillable = ['message', 'status', 'phone','recipient_id','sender_id','response_message','sent_from'];

    const UPDATED_AT = null;

    public function recipient()
    {
        return $this->belongsTo(GymClient::class, 'recipient_id');
    }

    public function sender()
    {
        return $this->belongsTo(Merchant::class, 'sender_id');
    }
}
