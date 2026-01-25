<?php

namespace App\Http\Controllers\Merchant;

use App\Mail\FitsigmaEmailVerification;
use App\Models\Common;
use App\Models\GymSetting;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MerchantsController extends MerchantBaseController
{

    public function index()
    {
        if ($this->data['userCheck']) {
            if (Auth::guard('merchant')->user()->is_admin != 1) {
                return redirect()->route('gym-admin.dashboard.index');
            } else {
                return redirect()->route('gym-admin.superadmin.dashboard');
            }

        }
        $this->data['gymSettings'] = GymSetting::first();
        return view("fitsigma.login", $this->data);
    }

    /**
     * Store a newly created merchant in storage.
     *
     * @return string
     */
    public function store()
    {
        if (request()->ajax()) {
            $auth        = false;
            $credentials = array(
                "username" => trim(request()->get('username')),
                "password" => trim(request()->get('password'))
            );

            if(trim(request()->get('username')) == '' && trim(request()->get('password')) == ''){
                $message = 'Please Provide Login Credentials.';
                $url     = '';
                return response()->json(
                    [
                        'success' => $auth,
                        'url'     => $url,
                        'message' => $message
                    ]
                );
            }elseif (trim(request()->get('username')) == '') {
                $message = 'Username cannot be blank.';
                $url     = '';
                return response()->json(
                    [
                        'success' => $auth,
                        'url'     => $url,
                        'message' => $message
                    ]
                );
            } elseif (trim(request()->get('password')) == '') {
                $message = 'Password cannot be blank.';
                $url     = '';
                return response()->json(
                    [
                        'success' => $auth,
                        'url'     => $url,
                        'message' => $message
                    ]
                );
            }

            $date    = Carbon::today()->format('Y-m-d'); // today
            if (Auth::guard('merchant')->attempt($credentials, true)) {
                if(Auth::guard('merchant')->user()->is_admin !== 1){
                    //check merchant account trial end period
                    $common = Common::find(Auth::guard('merchant')->user()->detail_id);
                    if($common->end_date <= $date){
                        $message = 'Your Account has been expired. Please Contact Administrator.';
                        $url     = '';
                        return response()->json(
                            [
                                'success' => $auth,
                                'url'     => $url,
                                'message' => $message
                            ]
                        );
                    }
                }

                $auth    = true; // Success
                $message = 'Login successful. Redirecting ...';
                if(Auth::guard('merchant')->user()->is_admin == 1){
                    $url     = route('gym-admin.superadmin.dashboard');
                }else{
                    $url     = route('gym-admin.dashboard.index');
                }
            } else {
                $message = 'Invalid username or password.';
                $url     = '';
            }
        }
        return response()->json(
            [
                'success' => $auth,
                'url'     => $url,
                'message' => $message
            ]
        );
    }

    public function sendResetPasswordLink()
    {

        if (trim(request()->get('email')) == '') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Email cannot be blank.'
                ]
            );
        }

        $merchant = Merchant::getByEmail(request()->get('email'));

        if (is_null($merchant)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Email not registered.'
                ]
            );
        } else {
            $resetToken                     = Str::random(40);
            $merchant->reset_password_token = $resetToken;
            $merchant->save();

            $email = request()->get('email');

            $eText = 'This email was sent automatically by HamroFitness in response to your request to recover your password. This is done for your protection. Only you, the recipient of this email can take the next step in the password recover process.';

            $this->data['title']       = "Forgot Password";
            $this->data['mailHeading'] = "Reset Password";
            $this->data['emailText']   = $eText;

            $this->data['url'] = url("/merchant/reset/" . $resetToken);

            try {
                Mail::to($email)->send(new FitsigmaEmailVerification($this->data));
            } catch (\Exception $e) {
                $response['errorEmailMessage'] = 'error';
            }

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Check your email inbox for password reset link.'
                ]
            );

        }

    }

    public function resetPassword($token)
    {

        $this->data['merchant'] = Merchant::where('reset_password_token', $token)->first();

        if (is_null($this->data['merchant'])) {
            abort(403);
        }
        $this->data['gymSettings'] = GymSetting::first();

        return view('fitsigma.reset', $this->data);
    }

    public function updatePassword()
    {
        $inputData = request()->get('formData');
        parse_str($inputData, $formFields);

        if (trim($formFields['password']) == '') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Password cannot be blank.'
                ]
            );
        } elseif ($formFields['password'] != $formFields['confirm_password']) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Password does not match. Enter the same password in both fields.'
                ]
            );
        } elseif ($formFields['password'] == $formFields['confirm_password']) {

            $merchant = Merchant::where('reset_password_token', $formFields['reset_token'])->first();

            if (is_null($merchant)) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Invalid reset token.'
                    ]
                );
            }

            $merchant->reset_password_token = '';
            $merchant->password             = Hash::make($formFields['password']);
            $merchant->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Password has been reset successfully.<br> Click <strong><a href="' . route('merchant.login.index') . '">here</a></strong> to login.'
                ]
            );
        }


        $merchant = Merchant::getByEmail($formFields['email']);
    }
}
