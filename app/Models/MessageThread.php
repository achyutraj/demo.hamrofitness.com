<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThread extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'message_threads';
    protected $fillable = ['employee_id', 'customer_id', 'merchant_id', 'detail_id'];

    public function client()
    {
        return $this->belongsTo(GymClient::class, 'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employ::class, 'employee_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
