<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MerchantLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = Merchant::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response(['error_message' => 'The provided credentials are incorrect.']);
        }
        $token=  $user->createToken($request->device_name,['merchant'])->plainTextToken;
        return response([
            'user' => $user,
            'token' => $token
        ]);
    }
}
