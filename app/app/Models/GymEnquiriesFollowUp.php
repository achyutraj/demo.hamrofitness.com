<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymEnquiriesFollowUp extends Model
{
    use HasFactory;

    protected $table = 'gym_enquiries_follow_up';

    public static $rules = [
        'next_follow_up_on' => 'required|date',
        'remark' => 'nullable',
    ];

    protected $dates = ['follow_up_date', 'next_follow_up_on'];

    protected $guarded = ['id'];

    public static function gymEnquiryFollowUps($enquiryId) {
        return GymEnquiriesFollowUp::where('gym_enquiry_id', $enquiryId)
            ->orderBy('id', 'desc')
            ->get();
    }
}
