<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    protected $table = "add_business";

    // Add your validation rules here
    public static $rules = [
        'name' => 'required',
        'mobile' => 'required',
        'business_name' => 'required',
        'category_id' => 'required',
        'business_location' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = ['name','mobile','business_name','email','status','business_location','cookie_id','category_id'];

    public static function PendingBusinessCount()
    {
        return Business::where('status','=','pending')->count();
    }
}
