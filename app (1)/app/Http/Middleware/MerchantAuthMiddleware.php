<?php

namespace App\Http\Middleware;

use App\Models\Common;
use App\Models\Merchant;
use App\Models\MerchantBusiness;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MerchantAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('locked')) {
            return redirect()->route('merchant.lockscreen');
        }
        //dd(Auth::guard('merchant')->check());
        if (Auth::guard('merchant')->check()) {

            // Update last activity time of merchant
            $merchant                = Merchant::find(Auth::guard('merchant')->user()->id);
            $merchant->is_logged_in  = 1;
            $merchant->last_activity = Carbon::now();
            $merchant->save();

            $date = Carbon::today()->format('Y-m-d'); // today

            if (Auth::guard('merchant')->user()) {
                //check merchant account trial end period
                if($merchant->is_admin !== 1){
                    $common = Common::find(Auth::guard('merchant')->user()->detail_id);
                    if($common->end_date <= $date){
                        Auth::guard('merchant')->logout();

                        //remove business_id session
                        if ($request->session()->has('business_id')) {
                            $request->session()->forget('business_id');
                        }
                        //remove business_id session
                        if ($request->session()->has('locked')) {
                            $request->session()->forget('locked');
                        }

                        return redirect()->route('merchant.login.index');
                    }
                }

                return $next($request);
            } 
        } else {
            return Redirect::route('merchant.login.index');
        }
    }
}
