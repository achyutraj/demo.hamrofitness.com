<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = "states";

    public function state() {
        return $this->hasMany(City::class, 'state_id');
    }

    protected $fillable = ['country_id', 'name', 'state_code'];
}
