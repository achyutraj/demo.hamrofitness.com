<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmsLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'adms_response','filter_response',
        'detail_id',
        'device_code',
        'serial_num',
        'status',
        'error_message',
        'fetch_attempt'
    ];

    protected $casts = [
        'date' => 'date',
        'adms_response' => 'array',
        'filter_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the business that owns the ADMS log.
     */
    public function business()
    {
        return $this->belongsTo(MerchantBusiness::class, 'detail_id');
    }

    /**
     * Get the device associated with this log.
     */
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_code', 'code');
    }

    /**
     * Scope to filter by business ID
     */
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('detail_id', $businessId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('date', [$from, $to]);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if user has exceeded daily sync limit
     */
    public static function hasExceededDailyLimit($businessId, $limit = 3)
    {
        $today = Carbon::today();
        $count = self::where('detail_id', $businessId)
                    ->whereDate('created_at', $today->format('Y-m-d'))
                    ->count();

        return $count >= $limit;
    }

    /**
     * Get today's sync count for a business
     */
    public static function getTodaySyncCount($businessId)
    {
        $today = Carbon::today();
        return self::where('detail_id', $businessId)
                  ->whereDate('created_at', $today->format('Y-m-d'))
                  ->count();
    }
}
