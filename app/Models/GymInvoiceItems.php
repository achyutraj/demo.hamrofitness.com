<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GymInvoiceItems extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gym_invoice_items';

    protected $guarded = ['id'];
}
