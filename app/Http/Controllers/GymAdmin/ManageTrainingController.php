<?php

namespace App\Http\Controllers\GymAdmin;

use App\Models\Activity;
use App\Classes\Reply;
use App\Models\GymSetting;
use PDF;
use Illuminate\Http\Request;
use App\Models\TrainingPlan;
use App\Models\GymClient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;

class ManageTrainingController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['businessMenu']     = 'active';
        $this->data['trainingPlanMenu'] = 'active';
        $this->data['title']          = 'Training Plans';
    }

    public function index()
    {
        if (!$this->data['user']->can("training_plan")) {
            return App::abort(401);
        }
        $this->data['defaultTrainingPlan'] = TrainingPlan::where('client_id', '=', null)
            ->where('branch_id', '=', $this->data['user']->detail_id)
            ->get();
        $this->data['levelActivity'] = Activity::where('branch_id',$this->data['user']->detail_id)->get();
        $this->data['TrainingPlan']        = TrainingPlan::where('client_id', '!=', null)
            ->where('branch_id', '=', $this->data['user']->detail_id)
            ->get();
        $this->data['clients']             = GymClient::GetClients($this->data['user']->detail_id)->active()->get();
        return view('gym-admin.training_plan.create', $this->data);
    }

    public function createDefaultTrainingPlan(Request $request)
    {
        $validate = Validator::make(request()->all(), GymSetting::rules('training'));
        if ($validate->fails()) {
            return Reply::formErrors($validate);
        }
        $training             = new TrainingPlan();
        $training->level      = $request->level;
        $training->days       = json_encode($request->days);
        $training->activity   = json_encode($request->activity);
        $training->sets       = json_encode($request->sets);
        $training->repetition = json_encode($request->repetition);
        $training->weights    = json_encode($request->weights);
        $training->restTime   = json_encode($request->restTime);
        $training->client_id  = $request->client_id;
        $training->branch_id  = $this->data['user']->detail_id;
        $training->startDate  = json_encode($request->startDate);
        $training->endDate    = json_encode($request->endDate);
        $training->save();
        return redirect()->back()->with('message', 'Training Created Successfully');

    }

    public function updateDefaultTrainingPlan(Request $request, $id)
    {
        $training = TrainingPlan::findorfail($id);
        $validate = Validator::make(request()->all(), GymSetting::rules('training'));
        if ($validate->fails()) {
            return Reply::formErrors($validate);
        }
        $training->level      = $request->level;
        $training->days       = json_encode($request->days);
        $training->activity   = json_encode($request->activity);
        $training->sets       = json_encode($request->sets);
        $training->repetition = json_encode($request->repetition);
        $training->weights    = json_encode($request->weights);
        $training->restTime   = json_encode($request->restTime);
        $training->branch_id  = $this->data['user']->detail_id;
        $training->save();
        return redirect()->back()->with('message', 'Default Training Updated Successfully');

    }

    public function updateClientTrainingPlan(Request $request, $id)
    {
        $training = TrainingPlan::findorfail($id);
        $validate = Validator::make(request()->all(), GymSetting::rules('training'));
        if ($validate->fails()) {
            return Reply::formErrors($validate);
        }
        $training->level      = $request->level;
        $training->days       = json_encode($request->days);
        $training->activity   = json_encode($request->activity);
        $training->sets       = json_encode($request->sets);
        $training->repetition = json_encode($request->repetition);
        $training->weights    = json_encode($request->weights);
        $training->restTime   = json_encode($request->restTime);
        $training->client_id  = $request->client_id;
        $training->branch_id  = $this->data['user']->detail_id;
        $training->startDate  = json_encode($request->startDate);
        $training->endDate    = json_encode($request->endDate);
        $training->save();
        return redirect()->back()->with('message', 'Client Training Updated Successfully');

    }

    public function deleteDefaultTrainingPlan()
    {
        $training = TrainingPlan::where('client_id', '=', null)->delete();
        return Reply::success("Training Plan deleted successfully.");
    }

    public function deleteTrainingPlan($id)
    {
        $training = TrainingPlan::find($id)->delete();
        return Reply::success("Training Plan deleted successfully.");
    }

    public function downloadClientTrainingPlan($id)
    {
        $clientTrainingPlan = TrainingPlan::select('training_plans.client_id', 'training_plans.level', 'training_plans.activity', 'training_plans.days', 'training_plans.sets', 'training_plans.repetition', 'training_plans.weights', 'training_plans.restTime', 'training_plans.startDate', 'training_plans.endDate', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'training_plans.client_id')
            ->where('training_plans.branch_id', $this->data['user']->detail_id)
            ->where('training_plans.id', $id)
            ->get();
        $pdf                = PDF::loadView('pdf.clientTraining', compact('clientTrainingPlan'));
        return $pdf->download('clientTraining.pdf');

    }

    public function downloadDefaultTrainingPlan()
    {
        $defaultTrainingPlan = TrainingPlan::where('client_id', '=', null)->where('branch_id', $this->data['user']->detail_id)->select('activity', 'level', 'days', 'sets', 'repetition', 'weights', 'restTime')->get();
        $pdf                 = PDF::loadView('pdf.defaultTraining', compact('defaultTrainingPlan'));
        return $pdf->download('defaultTraining.pdf');

    }
}
