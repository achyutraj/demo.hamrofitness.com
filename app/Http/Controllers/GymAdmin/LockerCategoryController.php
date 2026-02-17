<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\LockerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class LockerCategoryController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['lockerMenu'] = 'active';
        $this->data['locker']         = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_lockers")) {
            return App::abort(401);
        }

        $this->data['categoryMenu']     = 'active';
        $this->data['title'] = 'Locker Category';
        $this->data['locker_categories'] = LockerCategory::withCount('lockers')
            ->where('detail_id',$this->data['user']->detail_id)->get();
        return view('gym-admin.lockers.category.index',$this->data);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_lockers")) {
            return App::abort(401);
        }
        $this->data['categoryMenu']     = 'active';
        $this->data['title'] = 'Add Locker Category';
        return view('gym-admin.lockers.category.create',$this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("add_lockers")) {
            return App::abort(401);
        }

        $validator =  Validator::make($request->all(),LockerCategory::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $inputData = $request->all();

            $inputData['detail_id'] = $this->data['user']->detail_id;
            LockerCategory::create($inputData);
            return Reply::redirect(route('gym-admin.locker-category.index'), 'LockerCategory Added Successfully');
        }
    }

    public function edit($id)
    {
        $this->data['title'] = "Edit Locker Category";
        $this->data['categoryMenu']     = 'active';
        $this->data['package'] = LockerCategory::businessLockerCategoryDetail($this->data['user']->detail_id,$id);
        $this->data['reservations'] = $this->data['package']->reservations->count();

        return view('gym-admin.lockers.category.edit',$this->data);
    }

    public function update(Request $request,$id)
    {
        $validator =  Validator::make($request->all(),LockerCategory::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $inputData = $request->all();
            $package = LockerCategory::findByUid($id);
            $package->update($inputData);
            return Reply::redirect(route('gym-admin.locker-category.index'), 'LockerCategory updated Successfully');
        }
    }

    public function destroy($id)
    {
        if(request()->ajax()){
            $package = LockerCategory::businessLockerCategoryDetail($this->data['user']->detail_id,$id);
            $reservations = $package->reservations->count();
            if($reservations > 0){
                return Reply::error("Unable to remove. LockerCategory has reservations.");
            }
            $package->lockers()->delete();
            $package->delete();
            return Reply::success("LockerCategory deleted successfully.");
        }
    }
}
