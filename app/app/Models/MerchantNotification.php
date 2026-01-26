<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantNotification extends Model
{
    use HasFactory;

    protected $table = "merchant_notifications";

    protected $guarded = ['id'];
}
