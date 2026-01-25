<?php

namespace App\Http\Controllers\API\Device;

use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Http\Request;

class DeviceBaseController extends Controller
{
    public $data = [];

    //here , company refers as each fitness branch

    public function __construct(){
        $this->data['companyCheck'] = false;
        $company = Common::where('auth_key',request()->get('key'))->first();
        if($company == null){
            $this->data['company'] = null;
        }else{
            $this->data['companyCheck'] = true;
            $this->data['company'] = $company;
        }
    }

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];
        return response()->json($response, 200);
    }

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
