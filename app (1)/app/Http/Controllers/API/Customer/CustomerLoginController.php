<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\RegisterStoreRequest;
use App\Models\BusinessCustomer;
use App\Models\GymClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerLoginController extends Controller
{
    public function register(RegisterStoreRequest $request)
    {
        $customer = new GymClient();
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->save();

        $business = new BusinessCustomer();
        $business->detail_id = $request->branch_id;
        $business->customer_id = $customer->id;
        $business->save();
        return response()->json($customer, 200);
    }

    /**
     * Login API
     *
     * @OA\Post(
     *     path="/api/customer/login",
     *     tags={"Login"},
     *     summary="Customer login",
     *     description="Authenticate customer and return token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="device_name", type="string", format="device", example="device")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'device_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = GymClient::where('status',1)->where('is_client',  'yes')
                ->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response(['error_message' => 'The provided credentials are incorrect.']);
        }
        $user->update(['device_name'=>$request->device_name]);
        $authToken = 'authToken'.$request->device_name;
        $token=  $user->createToken($authToken)->accessToken;
        return response()->json(['token' => $token], 200);
    }

}
