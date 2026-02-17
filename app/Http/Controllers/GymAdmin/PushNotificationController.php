<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DataTables;

class PushNotificationController extends GymAdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['notificationMenu'] = 'active';
        $this->data['notification']         = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_tutorials")) {
            return App::abort(401);
        }

        $this->data['notificationMenu']     = 'active';
        $this->data['title'] = 'Push Notification';
        return view('gym-admin.push_notification.index',$this->data);
    }

    public function ajax_create()
    {
        if (!$this->data['user']->can("view_tutorials")) {
            return App::abort(401);
        }
        $tutorials = PushNotification::get();
        return Datatables::of($tutorials)
            ->editColumn('title', function ($row) {
                return $row->title;
            })
            ->editColumn('status', function ($row) {
                return $row->status ? 'Active' : 'Inactive';
            })
            ->addColumn('action', function ($row) {
                    return "<div class=\"btn-group\">
                    <button class=\"btn btn-xs blue dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"true\"><i class=\"fa fa-gears\"></i><span class=\"hidden-xs\">ACTION</span>
                    <i class=\"fa fa-angle-down\"></i>
                    </button>
                    <ul class=\"dropdown-menu pull-right\" role=\"menu\">
                    <li>
                        <a href=" . route('gym-admin.notifications.edit', [$row->uuid]) . "><i class=\"fa fa-edit\"></i> Edit </a>
                    </li>
                    <li>
                        <a class=\"delete-button\" data-notification-id='$row->uuid' href=\"javascript:;\"><i class=\"fa fa-trash\"></i> Delete </a>
                    </li>

                    </ul>
                </div>";
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_tutorials")) {
            return App::abort(401);
        }
        $this->data['notificationMenu']     = 'active';
        $this->data['title'] = 'Add Push Notification';
        return view('gym-admin.push_notification.create',$this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("add_tutorials")) {
            return App::abort(401);
        }

        $validator =  Validator::make($request->all(),PushNotification::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $inputData = $request->all();
            PushNotification::create($inputData);
            return Reply::redirect(route('gym-admin.notifications.index'), 'Push Notification Added Successfully');
        }
    }

    public function edit($id)
    {
        if (!$this->data['user']->can("edit_tutorials")) {
            return App::abort(401);
        }
        $this->data['title'] = "Edit Push Notification";
        $this->data['notificationMenu']     = 'active';
        $this->data['notification'] = PushNotification::findByUid($id);
        return view('gym-admin.push_notification.edit',$this->data);
    }

    public function update(Request $request,$id)
    {
        $validator =  Validator::make($request->all(),PushNotification::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $inputData = $request->all();
            $package = PushNotification::findByUid($id);
            $package->update($inputData);
            return Reply::redirect(route('gym-admin.notifications.index'), 'PushNotification updated Successfully');
        }
    }

    public function remove($id) {
        if (!$this->data['user']->can("delete_tutorials")) {
            return App::abort(401);
        }

        $this->data['notification'] = PushNotification::findByUid($id);
        return view('gym-admin.push_notification.destroy', $this->data);
    }

    public function destroy($id)
    {
        if (!$this->data['user']->can("delete_tutorials")) {
            return App::abort(401);
        }
        if(request()->ajax()){
            $package = PushNotification::findByUid($id);
            $package->delete();
            return Reply::success("PushNotification deleted successfully.");
        }
    }

    public function show($id) {
        $this->data['tutorial'] = PushNotification::findByUid($id);
        return view('gym-admin.tutorial.show', $this->data);
    }

}
