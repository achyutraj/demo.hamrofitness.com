<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\Locker;
use App\Models\LockerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Support\Facades\View;

class LockerController extends GymAdminBaseController
{

    public function index()
    {
        if (!$this->data['user']->can("view_lockers")) {
            return App::abort(401);
        }
        $this->data['lockerMenu']     = 'active';
        $this->data['lockerModuleMenu']     = 'active';
        $this->data['title'] = 'Locker Management';
        return view('gym-admin.lockers.index',$this->data);
    }

    public function ajax_create()
    {
        if (!$this->data['user']->can("view_lockers")) {
            return App::abort(401);
        }
        $lockers = Locker::businessLockers($this->data['user']->detail_id);
        return Datatables::of($lockers)
            ->editColumn('locker_category_id', function ($row) {
                return $row->lockerCategory->title ?? '';
            })
            ->editColumn('details', function ($row) {
                return $row->details;
            })
            ->editColumn('status', function ($row) {
                return $row->getStatusType($row->status);
            })
            ->addColumn('action', function ($row) {
                    return "<div class=\"btn-group\">
                    <button class=\"btn btn-xs blue dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"true\"><i class=\"fa fa-gears\"></i><span class=\"hidden-xs\">ACTION</span>
                    <i class=\"fa fa-angle-down\"></i>
                    </button>
                    <ul class=\"dropdown-menu pull-right\" role=\"menu\">
                    <li>
                        <a href=" . route('gym-admin.lockers.edit', [$row->uuid]) . "><i class=\"fa fa-edit\"></i> Edit </a>
                    </li>
                    <li>
                        <a class=\"delete-button\" data-locker-id='$row->uuid' href=\"javascript:;\"><i class=\"fa fa-trash\"></i> Delete </a>
                    </li>

                    </ul>
                </div>";
            })
            ->rawColumns(['action','status','details'])
            ->make(true);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_lockers")) {
            return App::abort(401);
        }
        $this->data['title'] = "Add Locker";
        $this->data['categories'] = LockerCategory::businessLockerCategory($this->data['user']->detail_id);
        return view('gym-admin.lockers.create',$this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("add_lockers")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),Locker::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $inputData = $request->all();
            $inputData['locker_category_id'] = $request->get('category');
            $inputData['detail_id'] = $this->data['user']->detail_id;
            Locker::create($inputData);
            return Reply::redirect(route('gym-admin.lockers.index'), 'Locker Added Successfully');
        }
    }

    public function edit($id)
    {
        if (!$this->data['user']->can("edit_lockers")) {
            return App::abort(401);
        }
        $this->data['title'] = "Edit Locker";
        $this->data['package'] = Locker::businessLockerDetail($this->data['user']->detail_id,$id);
        $this->data['categories'] = LockerCategory::where('detail_id',$this->data['user']->detail_id,$id)->get();
        return view('gym-admin.lockers.edit',$this->data);
    }

    public function update(Request $request,$id)
    {
        if (!$this->data['user']->can("edit_lockers")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),Locker::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $inputData = $request->all();

            $package = Locker::findByUid($id);
            $package->locker_num = $inputData['locker_num'];
            $package->status = $inputData['status'];
            $package->details = $inputData['details'];
            $package->locker_category_id = $inputData['category'];
            $package->save();
            return Reply::redirect(route('gym-admin.lockers.index'), 'Locker updated Successfully');
        }
    }

    public function removeLocker($id) {
        if (!$this->data['user']->can("delete_lockers")) {
            return App::abort(401);
        }

        $this->data['locker'] = Locker::findByUid($id);
        return View::make('gym-admin.lockers.destroy', $this->data);
    }
    public function destroy($id)
    {
        if (!$this->data['user']->can("delete_lockers")) {
            return App::abort(401);
        }
        if(request()->ajax()){
            $package = Locker::businessLockerDetail($this->data['user']->detail_id,$id);
            if($package->reservations->count() > 0){
                return Reply::error("Unable to remove. Locker has reservations.");
            }
            $package->delete();
            return Reply::success("Locker deleted successfully.");
        }
    }


}
