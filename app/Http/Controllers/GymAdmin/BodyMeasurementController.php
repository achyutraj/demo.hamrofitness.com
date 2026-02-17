<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\BodyMeasurement;
use App\Models\GymClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BodyMeasurementController extends GymAdminBaseController
{
    public function index()
    {
        if (!$this->data['user']->can("body_measurement")) {
            return App::abort(401);
        }
        $this->data['measurementMenu']     = 'active';
        $this->data['title'] = 'Body Measurement';
        return view('gym-admin.body_measurement.index',$this->data);
    }

    public function ajax_create()
    {
        if (!$this->data['user']->can("body_measurement")) {
            return App::abort(401);
        }
        $data = GymClient::GetClients($this->data['user']->detail_id)->withCount('bodyMeasurement')->has('latestMeasurement')->get();
        return Datatables::of($data)
            ->editColumn('first_name',function($row){
                return $row->fullName ?? '';
            })
            ->addColumn('count',function($row){
                return $row->body_measurement_count;
            })
            ->addColumn('added_by',function($row){
                return ucfirst($row->latestMeasurement->added_by);
            })
            ->addColumn('entry_date',function($row){
                return $row->latestMeasurement->entry_date->toFormattedDateString();
            })
            ->addColumn('action', function ($row) {
                return "<div class=\"btn-group\">
                    <button class=\"btn btn-xs blue dropdown-toggle\">
                        <a class=\"white\" href=" . route('gym-admin.measurements.show', [$row->id]) . "><span><i class=\"fa fa-eye\"></i> View</span> </a>
                    </button>
                </div>";
            })
            ->rawColumns(['action','added_by','entry_date','count'])
            ->make(true);
    }

    public function create()
    {
        if (!$this->data['user']->can("body_measurement")) {
            return App::abort(401);
        }
        $this->data['title'] = "Add Measurement";
        $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->get();
        return view('gym-admin.body_measurement.create',$this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("body_measurement")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),BodyMeasurement::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $inputData = $request->all();
            $inputData['entry_date']    = Carbon::createFromFormat('m/d/Y', $request->get('entry_date'));
            $inputData['client_id'] = $request->get('client');
            $find = BodyMeasurement::where('client_id',$inputData['client_id'])->whereDate('entry_date',$inputData['entry_date'])->first();
            if($find != null){
                return Reply::error('Measurement of given date has been already added.');
            }
            $inputData['added_by'] = $this->data['user']->username;
            BodyMeasurement::create($inputData);
            return Reply::redirect(route('gym-admin.measurements.index'), 'Measurement Added Successfully');
        }
    }

    public function show($id){
        $clientId = $id;
        $this->data['current'] = BodyMeasurement::getMeasurementData($clientId,1)->first();
        if($this->data['current'] != null){
            $this->data['history'] = BodyMeasurement::getMeasurementData($clientId,3,$this->data['current']->id)->get();
        }else{
            $this->data['history'] = [];
        }
        return view('gym-admin.body_measurement.show',$this->data);
    }

    public function edit($id)
    {
        if (!$this->data['user']->can("body_measurement")) {
            return App::abort(401);
        }
        $this->data['title'] = "Edit Measurement";
        $this->data['measurement'] = BodyMeasurement::findByUid($id);
        $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->get();
        return view('gym-admin.body_measurement.edit',$this->data);
    }

    public function update(Request $request,$id)
    {
        if (!$this->data['user']->can("body_measurement")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),BodyMeasurement::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $inputData = $request->all();
            $inputData['entry_date']    = Carbon::createFromFormat('m/d/Y', $request->get('entry_date'));
            $inputData['client_id'] = $request->get('client');
            $find = BodyMeasurement::where('client_id',$inputData['client_id'])->whereDate('entry_date',$inputData['entry_date'])->first();
            if($find != null){
                return Reply::error('Measurement of given date has been already added.');
            }
            $measure = BodyMeasurement::findByUid($id);
            $measure->update($inputData);
            return Reply::redirect(route('gym-admin.measurements.index'), 'Measurement updated Successfully');
        }
    }

    public function removeData($id) {
        if (!$this->data['user']->can("body_measurement")) {
            return App::abort(401);
        }
        $this->data['measurement'] = BodyMeasurement::findByUid($id);
        return view('gym-admin.body_measurement.destroy', $this->data);
    }

    public function destroy($id)
    {
        if (!$this->data['user']->can("body_measurement")) {
            return App::abort(401);
        }
        if(request()->ajax()){
            $measure = BodyMeasurement::findByUid($id);
            $measure->delete();
            return Reply::success("Measurement deleted successfully.");
        }
    }

    //Progress Tracker
    public function getClientDate($id)
    {
        $this->data['data'] = BodyMeasurement::getMeasurementData($id)->get();
        $view                    = view('gym-admin.body_measurement.date_ajax', $this->data)->render();
        return Reply::successWithData('Client Entry Date fetched', ['data' => $view]);
    }

    public function progressIndex()
    {
        if (!$this->data['user']->can("body_progress_tracker")) {
            return App::abort(401);
        }

        $this->data['trackerMenu']     = 'active';
        $this->data['title'] = 'Progress Tracker';
        $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->has('bodyMeasurement')->get();
        return view('gym-admin.body_measurement.progress',$this->data);
    }

    public function clientProgressReport(){
        $search = request()->get('client');
        $start = Carbon::createFromFormat('Y-m-d', request()->get('from_date'))->format('Y-m-d');
        $end = Carbon::createFromFormat('Y-m-d', request()->get('to_date'))->format('Y-m-d');
        $this->data['show'] = true;
        $this->data['progress'] = BodyMeasurement::getMeasurementData($search,2,null,$start,$end)->get();
        $this->data['measurements'] = BodyMeasurement::clientProgressTracker($search,$start,$end);
        $view                    = view('gym-admin.body_measurement.date_ajax', $this->data)->render();
        return Reply::successWithData('Client Data fetched', ['data' => $view]);
    }

    //Fitness Calculation
    public function fitnessCalculation()
    {
        $this->data['title'] = 'Fitness Calculator';
        $this->data['calculate_show'] = false;
        return view('gym-admin.body_measurement.calculator',$this->data);
    }

    public function calculation(){
        $this->data['calculate_show'] = true;

        $this->data['type'] = request()->get('type');
        if( $this->data['type'] == 'bmi'){
            $this->bmiCalculate();
        }elseif( $this->data['type'] == 'fat'){
            $this->fatCalculate();
        }else{
            $this->calorieCalculate();
            if(request()->get('age') < 15){
                return Reply::error('Age must be greater or equal to 15');
            }
        }
        $view = view('gym-admin.body_measurement.date_ajax',$this->data)->render();
        return Reply::successWithData('Data fetched', ['data' => $view]);

    }

    public function bmiCalculate(){
        $age = request()->get('age');
        $height_cm = request()->get('height');
        $weight = request()->get('weight');
        $height = $height_cm * 0.01;
        $res = $weight/($height * $height);

        if($res < 18.5 ){
            $status = 'underweight';
            $status_color = 'warning';
            $color = '#e3e317';
        }elseif($res > 18.5 && $res < 25){
            $status = 'normal';
            $status_color = 'success';
            $color = '#5cb85c';
        }elseif($res > 25 && $res < 30 ){
            $status = 'overweight';
            $status_color = 'danger';
            $color = '#e43a45';
        }else{
            $status = 'obesity';
            $status_color = 'danger';
            $color = '#e43a45';
        }

        $this->data['result'] = [
            'percent' => round($res,1),
            'status' => $status,
            'status_color' => $status_color,
            'color' => $color,
        ];
        return $this->data['result'] ;
    }

    public function fatCalculate(){
        $gender = request()->get('gender');
        $height = request()->get('height');
        $neck = request()->get('neck');
        $waist = request()->get('waist');
        $hip = request()->get('hip');

        $color = '#5cb85c';
        if($gender == 'male'){
            $percentage = 495 / (1.0324 - 0.19077 * log10($waist - $neck) + 0.15456 * log10($height)) - 450;
            $round_percent = round($percentage,0);
            if($round_percent >= 2 && $round_percent <= 5){
                $status = 'Essential fat';
            }else if($round_percent >= 6 && $round_percent <= 13 ){
                $status = 'Athletes';
            }else if($round_percent >= 14 && $round_percent <= 17 ){
                $status = 'Fitness';
            }else if($round_percent >= 18 && $round_percent <= 24 ){
                $status = 'Average';
            }else{
                $status = 'Obese';
                $color = '#e43a45';
            }
        }else{
            $percentage = 495 / (1.29579 - 0.35004 * log10($waist + $hip - $neck) + 0.22100 * log10($height)) - 450;
            $round_percent = round($percentage,0);
            if($round_percent >= 10 && $round_percent <= 13){
                $status = 'Essential fat';
            }else if($round_percent >= 14 && $round_percent <= 20 ){
                $status = 'Athletes';
            }else if($round_percent >= 21 && $round_percent <= 24 ){
                $status = 'Fitness';
            }else if($round_percent >= 25 && $round_percent <= 31 ){
                $status = 'Average';
            }else{
                $status = 'Obese';
                $color = '#e43a45';
            }
        }
        $this->data['result'] = [
            'percent' => round($percentage,1),
            'status' => $status,
            'color' => $color,
        ];
        return $this->data['result'] ;
    }

    public function calorieCalculate(){
        $age = request()->get('age');
        $gender = request()->get('gender');
        $height = request()->get('height');
        $weight = request()->get('weight');
        $activity = request()->get('activity');
        $color = '#5cb85c';
        if($gender == 'female'){
            $result = (10*$weight + 6.25*$height - 5*$age - 161 )* $activity;
        }else{
            $result = (10*$weight + 6.25*$height - 5*$age + 5) * $activity;
        }
        $this->data['result'] = [
            'percent' => round($result,1),
            'color' => $color,
        ];
        return $this->data['result'] ;
    }
}
