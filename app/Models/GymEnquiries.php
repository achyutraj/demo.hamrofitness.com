<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GymEnquiries extends Model
{
    use HasFactory;

    public static $rules = [
        'enquiry_date'  => 'required|date',
        'customer_name' => 'required|string',
        'customer_lname' => 'required|string',
        'address'        => 'required|string',
        'email'          => 'required|email|unique:gym_enquiries,email',
        'mobile'         => 'required|digits:10|unique:gym_enquiries,mobile'
    ];

    protected $dates = ['previous_follow_up', 'next_follow_up', 'dob', 'enquiry_date'];

    protected $guarded = ['id'];

    public function followUp()
    {
        return $this->hasMany(GymEnquiriesFollowUp::class, 'gym_enquiry_id');
    }

    public static function gymEnquiry($businessId, $id)
    {
        return GymEnquiries::with('followUp')
            ->where('detail_id', $businessId)->find($id);
    }

    public static function monthlyEnquiries($businessId, $month)
    {
        return GymEnquiries::where('detail_id', $businessId)
            ->whereMonth('enquiry_date', $month)->whereYear('enquiry_date', now()->year)
            ->count();
    }

    public function displayFullName() {
        return $this->customer_name.' '.$this->customer_mname.' '.$this->customer_lname;
    }
}
