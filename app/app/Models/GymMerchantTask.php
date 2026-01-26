<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymMerchantTask extends Model
{
    use HasFactory;

    protected $table = 'task_management';
    protected $dates = ['deadline','reminder_date'];

    public function merchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    protected $fillable = ['merchant_id', 'heading', 'description', 'deadline', 'status', 'priority'];
}
