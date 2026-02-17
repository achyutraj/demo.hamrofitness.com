<?php

namespace App\Http\Controllers\Customer;

use App\Models\GymClient;
use Illuminate\Support\Facades\Auth;

class DeveloperController extends CustomerBaseController
{
    public function settings()
    {
        $user = Auth::guard('customer')->user();
        $this->data['api_token'] = $user->api_token;
        return view('customer-app.developers.settings', $this->data);
    }

    public function docs()
    {
        return view('customer-app.developers.document',$this->data);
    }

    public function generate()
    {
        $user = Auth::guard('customer')->user();
        $gym_client = GymClient::find($user->id);
        $token = $gym_client->createToken($gym_client->email,['customer'])->plainTextToken;
        $gym_client->api_token = $token;
        $gym_client->update();

        return response()->json([
            'status' => true,
            'message'=> 'Token Generate'
        ]);

    }
}
