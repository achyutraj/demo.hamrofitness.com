<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProductQuantity extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'product_quantities';
    protected $fillable = [
        'product_id',
        'quantity',
        'type', // 'add', 'remove', 'update'
        'previous_quantity',
        'new_quantity',
        'notes',
        'branch_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'product_id',
                'quantity',
                'type',
                'previous_quantity',
                'new_quantity',
                'notes',
                'branch_id'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return "Product quantity history has been {$eventName}";
            })
            ->useLogName('ProductQuantityHistory');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
