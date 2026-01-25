<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareUpdate extends Model
{
    use HasFactory;

    protected $table = 'software_updates';

    protected $dates = ['date'];

    protected $fillable = ['category_id','title','details','date'];

    // Add your validation rules here
    public static $rules = [
        'title' => 'required',
        'details' => 'required',
        'date' => 'required',
    ];

    public static function GetUpcomingInfo() {
        return SoftwareUpdate::select('title', 'details', 'date')
            ->orderBy('date', 'desc')
            ->get();
    }

}
