<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BodyMeasurement;
use Illuminate\Http\Request;

class BodyMeasurementController extends CustomerBaseController
{
    public function index(){

        $this->data['title'] = 'HamroFitness | Body Measurement';
        $this->data['measurementMenu'] = 'active';

        $this->data['current'] = BodyMeasurement::getMeasurementData($this->data['customerValues']->id,1)->first();
        if($this->data['current'] != null){
            $this->data['history'] = BodyMeasurement::getMeasurementData($this->data['customerValues']->id,3,$this->data['current']->id)->get();
        }else{
            $this->data['history'] = [];
        }
        return view('customer-app.body_measurement.show',$this->data);
    }
}
