<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Controllers\GymAdmin\GymAdminBaseController;
use App\Models\DietPlan;
use App\Models\GymClient;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ManageDietPlansController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['businessMenu']     = 'active';
        $this->data['dietPlanMenu'] = 'active';
        $this->data['title']          = 'Diet Plans';
    }

    public function index()
    {
        if (!$this->data['user']->can("diet_plan")) {
            return App::abort(401);
        }
        $this->data['defaultDietPlan'] = DietPlan::where('client_id', '=', null)->where('branch_id', $this->data['user']->detail_id)->get();
        $this->data['dietPlan']        = DietPlan::where('client_id', '!=', null)->where('branch_id', $this->data['user']->detail_id)->get();
        $this->data['clients']         = GymClient::GetClients($this->data['user']->detail_id)->active()->get();
        $selected_clients              = [];
        foreach ($this->data['clients'] as $client) {
            $u = DietPlan::where('client_id', $client->id)->first();
            if ($u === null) {
                array_push($selected_clients, $client);
            }
        }
        $this->data['selectedClient'] = DietPlan::select('client_id')->where('branch_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.diet_plan.create', $this->data, compact('selected_clients'));
    }

    public function createDefaultDietPlan(Request $request)
    {
        $defaultDiet               = new DietPlan();
        $store_result              = array();
        $store_result['days']      = $request['days'];
        $store_result['breakfast'] = array($request['sunday']['breakfast'], $request['monday']['breakfast'], $request['tuesday']['breakfast'],
                                           $request['wednesday']['breakfast'], $request['thursday']['breakfast'], $request['friday']['breakfast'], $request['saturday']['breakfast']
        );
        $store_result['lunch']     = array($request['sunday']['lunch'], $request['monday']['lunch'], $request['tuesday']['lunch'],
                                           $request['wednesday']['lunch'], $request['thursday']['lunch'], $request['friday']['lunch'], $request['saturday']['lunch']
        );
        $store_result['dinner']    = array($request['sunday']['dinner'], $request['monday']['dinner'], $request['tuesday']['dinner'],
                                           $request['wednesday']['dinner'], $request['thursday']['dinner'], $request['friday']['dinner'], $request['saturday']['dinner']
        );
        $store_result['meal_4']    = array($request['sunday']['meal_4'], $request['monday']['meal_4'], $request['tuesday']['meal_4'],
            $request['wednesday']['meal_4'], $request['thursday']['meal_4'], $request['friday']['meal_4'], $request['saturday']['meal_4']
        );
        $store_result['meal_5']    = array($request['sunday']['meal_5'], $request['monday']['meal_5'], $request['tuesday']['meal_5'],
            $request['wednesday']['meal_5'], $request['thursday']['meal_5'], $request['friday']['meal_5'], $request['saturday']['meal_5']
        );
        $defaultDiet->days         = serialize($store_result['days']);
        $defaultDiet->breakfast    = json_encode($store_result['breakfast']);
        $defaultDiet->lunch        = json_encode($store_result['lunch']);
        $defaultDiet->dinner       = json_encode($store_result['dinner']);
        $defaultDiet->meal_4       = json_encode($store_result['meal_4']);
        $defaultDiet->meal_5       = json_encode($store_result['meal_5']);
        $defaultDiet->client_id    = $request->client_id;
        $defaultDiet->branch_id    = $this->data['user']->detail_id;
        $defaultDiet->save();
        return redirect()->back()->with('message', 'Diet Plan Succesfully Added');
    }

    public function exportDietPlan($id)
    {
        $diets = DietPlan::select('diet_plans.days', 'diet_plans.client_id', 'diet_plans.breakfast', 'diet_plans.lunch', 'diet_plans.dinner','diet_plans.meal_4','diet_plans.meal_5', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'diet_plans.client_id')
            ->where('diet_plans.branch_id', $this->data['user']->detail_id)
            ->where('diet_plans.id', $id)
            ->get();
        $pdf   = PDF::loadView('pdf.diet', compact('diets'));
        return $pdf->download('diet.pdf');

    }

    public function updateDefaultDietPlan(Request $request, $id)
    {
        $this->validate($request, [
            'sunday'    => 'array|required',
            'monday'    => 'array|required',
            'tuesday'   => 'array|required',
            "wednesday" => 'array|required',
            "thursday"  => 'array|required',
            "friday"    => 'array|required',
            "saturday"  => 'array|required'
        ]);
        $defaultDiet               = DietPlan::findorfail($id);
        $store_result              = array();
        $store_result['days']      = $request['days'];
        $store_result['breakfast'] = array($request['sunday']['breakfast'], $request['monday']['breakfast'], $request['tuesday']['breakfast'],
                                           $request['wednesday']['breakfast'], $request['thursday']['breakfast'], $request['friday']['breakfast'], $request['saturday']['breakfast']
        );
        $store_result['lunch']     = array($request['sunday']['lunch'], $request['monday']['lunch'], $request['tuesday']['lunch'],
                                           $request['wednesday']['lunch'], $request['thursday']['lunch'], $request['friday']['lunch'], $request['saturday']['lunch']
        );
        $store_result['dinner']    = array($request['sunday']['dinner'], $request['monday']['dinner'], $request['tuesday']['dinner'],
                                           $request['wednesday']['dinner'], $request['thursday']['dinner'], $request['friday']['dinner'], $request['saturday']['dinner']
        );
        $store_result['meal_4']    = array($request['sunday']['meal_4'], $request['monday']['meal_4'], $request['tuesday']['meal_4'],
            $request['wednesday']['meal_4'], $request['thursday']['meal_4'], $request['friday']['meal_4'], $request['saturday']['meal_4']
        );
        $store_result['meal_5']    = array($request['sunday']['meal_5'], $request['monday']['meal_5'], $request['tuesday']['meal_5'],
            $request['wednesday']['meal_5'], $request['thursday']['meal_5'], $request['friday']['meal_5'], $request['saturday']['meal_5']
        );
        $defaultDiet->days         = serialize($store_result['days']);
        $defaultDiet->breakfast    = json_encode($store_result['breakfast']);
        $defaultDiet->lunch        = json_encode($store_result['lunch']);
        $defaultDiet->dinner       = json_encode($store_result['dinner']);
        $defaultDiet->meal_4       = json_encode($store_result['meal_4']);
        $defaultDiet->meal_5       = json_encode($store_result['meal_5']);
        $defaultDiet->branch_id    = $this->data['user']->detail_id;
        $defaultDiet->save();
        return redirect()->back()->with('message', 'Default Diet Plan Succesfully Updated');
    }

    public function updateClientDietPlan(Request $request, $id)
    {
        $this->validate($request, [
            'sunday'    => 'array|required',
            'monday'    => 'array|required',
            'tuesday'   => 'array|required',
            "wednesday" => 'array|required',
            "thursday"  => 'array|required',
            "friday"    => 'array|required',
            "saturday"  => 'array|required'
        ]);
        $clientDiet                = DietPlan::findorfail($id);
        $store_result              = array();
        $store_result['days']      = $request['days'];
        $store_result['breakfast'] = array($request['sunday']['breakfast'], $request['monday']['breakfast'], $request['tuesday']['breakfast'],
                                           $request['wednesday']['breakfast'], $request['thursday']['breakfast'], $request['friday']['breakfast'], $request['saturday']['breakfast']
        );
        $store_result['lunch']     = array($request['sunday']['lunch'], $request['monday']['lunch'], $request['tuesday']['lunch'],
                                           $request['wednesday']['lunch'], $request['thursday']['lunch'], $request['friday']['lunch'], $request['saturday']['lunch']
        );
        $store_result['dinner']    = array($request['sunday']['dinner'], $request['monday']['dinner'], $request['tuesday']['dinner'],
                                           $request['wednesday']['dinner'], $request['thursday']['dinner'], $request['friday']['dinner'], $request['saturday']['dinner']
        );
        $store_result['meal_4']    = array($request['sunday']['meal_4'], $request['monday']['meal_4'], $request['tuesday']['meal_4'],
            $request['wednesday']['meal_4'], $request['thursday']['meal_4'], $request['friday']['meal_4'], $request['saturday']['meal_4']
        );
        $store_result['meal_5']    = array($request['sunday']['meal_5'], $request['monday']['meal_5'], $request['tuesday']['meal_5'],
            $request['wednesday']['meal_5'], $request['thursday']['meal_5'], $request['friday']['meal_5'], $request['saturday']['meal_5']
        );
        $clientDiet->days          = serialize($store_result['days']);
        $clientDiet->breakfast     = json_encode($store_result['breakfast']);
        $clientDiet->lunch         = json_encode($store_result['lunch']);
        $clientDiet->dinner        = json_encode($store_result['dinner']);
        $clientDiet->meal_4       = json_encode($store_result['meal_4']);
        $clientDiet->meal_5       = json_encode($store_result['meal_5']);
        $clientDiet->client_id     = $request->client_id;
        $clientDiet->branch_id     = $this->data['user']->detail_id;
        $clientDiet->save();
        return redirect()->back()->with('message', 'Client Diet Plan Updated Successfully');
    }

    public function deleteDietPlan($id)
    {
        $dietPlan = DietPlan::findorfail($id);
        $dietPlan->delete();
        return Reply::success("Diet Plan deleted successfully.");
    }

    public function show()
    {
        $diets = DietPlan::all();
        return view('pdf.diet', compact($diets));
    }
}
