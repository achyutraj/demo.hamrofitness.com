<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";

    // Add your validation rules here
    public static $rules = [
        'name' => 'required'
    ];

    public function scopeActive($query) {
        return $query->where('categories.status', 'active');
    }

    // Don't forget to fill this array
    protected $fillable = ["name", "status", 'slug'];
}
