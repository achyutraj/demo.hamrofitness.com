<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEmail extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'message', 'status', 'phone'];

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
