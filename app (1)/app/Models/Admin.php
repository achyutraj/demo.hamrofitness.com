<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
//use Zizaco\Entrust\Traits\EntrustUserTrait;

class Admin extends Model
{
    use HasFactory;

    use Authenticatable, CanResetPassword, Notifiable;

    protected $table = 'admins';

    public $timestamps = false;

    // Add your validation rules here
    public static $rules = [
        'name'     => 'required',
        'email'    => 'required',
        'password' => 'required|min:6'
    ];

    protected $fillable = ["email", "password", "name", "status"];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Returns the slack web hook address for user
     * @return mixed
     */
    public function routeNotificationForSlack()
    {
        return env('SLACK_WEB_HOOK_PATH');
    }
}
