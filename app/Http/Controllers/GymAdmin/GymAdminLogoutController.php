<?php

namespace App\Http\Controllers\GymAdmin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class GymAdminLogoutController extends Controller
{
    public function index()
    {
        // Update login status of merchant
        $merchant                = Merchant::find(Auth::guard('merchant')->user()->id);
        $merchant->is_logged_in = 0;
        $merchant->save();
        Auth::guard('merchant')->logout();

        //remove business_id session
        if(Session::has('business_id')){
            Session::forget('business_id');
        }

        return Redirect::route('merchant.login.index');
    }
}
