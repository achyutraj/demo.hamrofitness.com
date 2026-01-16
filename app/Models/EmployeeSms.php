<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSms extends Model
{
    use HasFactory;

    protected $table = 'employee_sms';
    protected $fillable = ['message', 'status', 'phone','recipient_id','sender_id'];

    const UPDATED_AT = null;

    public function recipient()
    {
        return $this->belongsTo(Employ::class, 'recipient_id');
    }

    public function sender()
    {
        return $this->belongsTo(Merchant::class, 'sender_id');
    }
}
