<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
    use HasFactory;

    protected $table = "common_details";

    protected $fillable = ['title', 'address','latitude', 'longitude', 'email', 'phone', 'phone2', 'status', 'website', 'owner_incharge_name', 'owner_incharge_name2', 'last_updated', 'bitly_link',
        'search_title','start_date','end_date','has_device','auth_key'];

    // Add your validation rules here
    public static $rules = [
        'title'               => 'required',
        'address'             => 'required',
        'owner_incharge_name' => 'required',
        'email'               => 'required|email',
        'phone'               => 'required',
        'category_id'         => 'required'
    ];

    //Validation Rules for add business
    public static $businessRules = [
        'title'               => 'required',
        'email'               => 'required',
        'owner_incharge_name' => 'required',
        'phone'               => 'required',
        'address'             => 'required'
    ];

    public static function Detail($id)
    {
        return Common::where('id', '=', $id)->first();
    }

    public static function allActiveGyms()
    {
        return Common::where('status', '=', 'active')
            ->get();
    }

    public static function allActiveBusiness()
    {
        return Common::where('status', '=', 'active')
            ->orderBy('title', 'asc')
            ->listed()
            ->get();
    }

    public function subCategory()
    {
        return $this->hasMany(BusinessCategory::class, 'detail_id');
    }

    public static function aceBusinesses()
    {
        return Common::all();
    }

    public function memberships()
    {
        return $this->hasMany(GymMembership::class, 'detail_id', 'id');
    }

    public function templates()
    {
        return $this->hasMany(Template::class, 'detail_id', 'id');
    }

    public function setting()
    {
        return $this->hasOne(GymSetting::class, 'detail_id');
    }

    public function histories()
    {
        return $this->hasMany(BusinessRenewHistory::class, 'detail_id', 'id');
    }
}
