<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymClientReminderHistory extends Model
{
    use HasFactory;

    protected $table = 'gym_client_reminder_history';
    protected $guarded = ['id'];

    public function client() {
        return $this->belongsTo(GymClient::class, 'client_id');
    }

    public function purchase() {
        return $this->belongsTo(GymPurchase::class, 'purchase_id');
    }

}
