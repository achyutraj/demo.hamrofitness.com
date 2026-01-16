<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\LogUserDetailIdTrait;

class GymInvoice extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, LogUserDetailIdTrait;

    protected $table = 'gym_invoice';

    protected $guarded = ['id'];

    protected $dates = ['invoice_date'];

    public function getActivitylogOptions(): LogOptions
    {
        $user = auth()->guard('merchant')->user();
        $userId = $user ? $user->username : 'Unknown User';

        return LogOptions::defaults()
            ->logOnly([
                'detail_id','item_name','category_id','purchase_date','supplier_id','price','bill','remarks'
            ])
            ->logOnlyDirty()->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) use ($userId) {
                return "{$userId} has {$eventName} a GymInvoice : {$this->item_name}";
            })
            ->useLogName('GymInvoice');
    }

    public static function rules($action){
        $rules = [
            'add' => [
                'client_address' => 'required',
                'client_name' => 'required',
                'invoice_date' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'generated_by' => 'required',
            ],
            'membership' => [
                'client' => 'required',
                'payment_amount' => 'required',
                'payment_source' => 'required',
                'payment_date' => 'required',
                'purchase_id' => 'required',
                'payment_required' => 'required',
                'payment_type'  => 'required',
                'generated_by' => 'required'
            ]
        ];
        return $rules[$action];
    }

    public function business()
    {
        return $this->belongsTo(Common::class,'detail_id');
    }

    public function items()
    {
        return $this->hasMany(GymInvoiceItems::class,'invoice_id');
    }

    public static function byInvoiceId($invoiceID,$businessID)
    {
        return GymInvoice::with('items')
            ->where('id','=',$invoiceID)
            ->where('detail_id','=',$businessID)
            ->first();
    }

    public static function byBusinessID($businessID)
    {
        return GymInvoice::with('items')
            ->where('detail_id','=',$businessID)
            ->get();
    }
}
