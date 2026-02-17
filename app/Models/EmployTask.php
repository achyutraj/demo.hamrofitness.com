<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployTask extends Model
{
    use HasFactory;

    protected $table = 'employ_tasks';

    protected $fillable = ['id','employ_id','detail_id', 'heading', 'description', 'deadline', 'status', 'priority'];
}
