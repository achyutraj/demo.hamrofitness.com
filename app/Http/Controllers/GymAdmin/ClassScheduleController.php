<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\ClassSchedule;
use App\Models\Classes;
use App\Models\Trainers;
use App\Models\GymClient;
use App\Models\GymSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class ClassScheduleController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['classScheduleMenu']     = 'active';
        $this->data['title'] = 'Class Schedule';
    }

    public function index()
    {
        if (!$this->data['user']->can("class_schedule")) {
            return App::abort(401);
        }
        $this->data['title'] = 'Class Schedule';
        $this->data['class'] = ClassSchedule::where('detail_id',$this->data['user']->detail_id)->latest()->get();
        return view('gym-admin.classSchedule.index',$this->data);
    }

    public function create()
    {
        if (!$this->data['user']->can("class_schedule")) {
            return App::abort(401);
        }
        $this->data['title'] = "Add Class Schedule";
        $this->data['classes'] = Classes::where('branch_id',$this->data['user']->detail_id)->get();
        $this->data['trainers'] = Trainers::where('branch_id',$this->data['user']->detail_id)->get();
        $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->get();
        $this->data['weekends'] = weekends();
        return view('gym-admin.classSchedule.create',$this->data);
    }
    public function store(Request $request){

        if (!$this->data['user']->can("class_schedule")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),ClassSchedule::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }
        $inputData = $request->only('class','trainer','days','client','assign_to','startTime','endTime');
        $inputData['days'] = json_encode($request->days);
        $inputData['detail_id'] = $this->data['user']->detail_id;
        $inputData['class_id'] = $request->get('class');
        $inputData['trainer_id'] = $request->get('trainer');
        $inputData['has_client'] = $request->get('assign_to') == 'true' ? 1 : 0;
        $class = ClassSchedule::create($inputData);

        if($request->get('assign_to') == 'true'){
            $class->clients()->sync($request->get('client'));
        }
        return Reply::redirect(route('gym-admin.class-schedule.index'),'Class Schedule Created Successfully');
    }

    public function edit($id){
        if (!$this->data['user']->can("class_schedule")) {
            return App::abort(401);
        }
        $this->data['title'] = "Edit Class Schedule";
        $this->data['schedule'] = ClassSchedule::findByUid($id);
        $this->data['classes'] = Classes::where('branch_id',$this->data['user']->detail_id)->get();
        $this->data['trainers'] = Trainers::where('branch_id',$this->data['user']->detail_id)->get();
        $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->get();
        $this->data['weekends'] = weekends();
        $this->data['client_assign'] = $this->data['schedule']->clients()->pluck('client_id')->toArray();
        $this->data['select_days'] = json_decode($this->data['schedule']->days,true);

        return view('gym-admin.classSchedule.edit',$this->data);
    }

    public function update(Request $request,$id){
        if (!$this->data['user']->can("class_schedule")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),ClassSchedule::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }
        $inputData = $request->only('class','trainer','days','client','assign_to','startTime','endTime');
        $inputData['days'] = json_encode($request->days);
        $inputData['detail_id'] = $this->data['user']->detail_id;
        $inputData['class_id'] = $request->get('class');
        $inputData['trainer_id'] = $request->get('trainer');
        $inputData['has_client'] = $request->get('assign_to') == 'true' ? 1 : 0;
        $class = ClassSchedule::findByUid($id);
        $class->update($inputData);
        if($request->get('assign_to') == 'true'){
            $class->clients()->sync($request->get('client'));
        }

        return Reply::redirect(route('gym-admin.class-schedule.index'),'Class Schedule updated Successfully');
    }

    public function destroy($id)
    {
        $class = ClassSchedule::findByUid($id);
        if($class->has_client == 1){
            $clientId = $class->clients()->pluck('client_id')->toArray();
            $class->clients()->detach($clientId);
        }
        $class->delete();
        return Reply::redirect(route('gym-admin.class-schedule.index'),'Class Schedule deleted Successfully');
    }
}
