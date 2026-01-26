<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\BusinessCustomer;
use App\Models\GymClient;
use App\Models\GymSetting;

class CustomerBaseController extends Controller
{
    public $data = [];

    public function getCustomerData()
    {
        $this->data['customerValues'] = GymClient::select('id')
                                ->where('id',auth()->guard('customer-api')->user()->id)->first();
                                
        if($this->data['customerValues']) {
            $business = BusinessCustomer::where('customer_id','=', $this->data['customerValues']->id)
                ->first();
            $this->data['customerValues']->detail_id = $business->detail_id;
        }
        return $this->data['customerValues'];
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
