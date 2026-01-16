<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = "areas";

    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public static $rules = [
        'name' => 'required|unique:areas'
    ];

    public static function byCity($cityID)
    {
        return Area::where('city_id','=',$cityID)
            ->orderBy('name','ASC')->get();
    }

    protected $fillable = ['name', 'city_id', 'slug', 'latitude', 'longitude'];

}
