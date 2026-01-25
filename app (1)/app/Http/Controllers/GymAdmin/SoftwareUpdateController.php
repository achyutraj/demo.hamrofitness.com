<?php

namespace App\Http\Controllers\GymAdmin;


use App\Classes\Reply;
use Illuminate\Http\Request;
use App\Models\SoftwareUpdate;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class SoftwareUpdateController extends GymAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->data['updatesMenu'] = 'active';
    }

    public function index() {
        if(!$this->data['user']->can("view_software_updates"))
        {
            return App::abort(401);
        }

        $this->data['UpcomingInfo'] = SoftwareUpdate::GetUpcomingInfo();
        $this->data['title'] = "Upcoming Updates";
        return view('gym-admin.software-update.index', $this->data);
    }

    public function create()
    {
        return view('gym-admin.software-update.create-edit', $this->data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),SoftwareUpdate::$rules);
        if($validator->fails()){
            return Reply::formErrors($validator);
        }
        $info = SoftwareUpdate::create([
           'category_id' => 1,
           'title' => $request->get('title'),
           'details' => $request->get('details'),
           'date' => $request->get('date'),
        ]);
        return Reply::redirect(route('gym-admin.upcoming.index'), 'Software added Successfully');

    }
    public function edit($id){
        $this->data['info'] = SoftwareUpdate::find($id);
        return view('gym-admin.software-update.create-edit', $this->data);
    }

    public function update(Request $request,$id){
        $info = SoftwareUpdate::find($id);
        $info->update($request->all());
        return Reply::redirect(route('gym-admin.upcoming.index'), 'Software updated Successfully');
    }

}
