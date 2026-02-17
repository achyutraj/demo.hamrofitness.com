<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\Classes;
use App\Models\Trainers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClassesController extends GymAdminBaseController
{
    public function index(){
        $this->data['class'] = Classes::where('branch_id','=', $this->data['user']->detail_id)->get();
        $this->data['trainer'] = Trainers::where('branch_id','=', $this->data['user']->detail_id)->get();
        $this->data['title'] = "Classes & Trainers";
        return view ('gym-admin.setting.trainerClasses',$this->data);
    }

    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'class_name' => [
                'required',
                Rule::unique('classes')->where(function ($query) {
                    $query->where('branch_id',  $this->data['user']->detail_id);
                })
            ],
        ]);

        if ($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput($request->all());
        }
        $class = new Classes();
        $class->branch_id = $this->data['user']->detail_id;
        $class->class_name = $request->get('class_name');
        $class->save();
        return redirect()->back()->with('message','Classes added successfully');
    }

    public function update(Request $request,$id){
        $validate = Validator::make($request->all(),[
            'class_name' => 'required|unique:classes,class_name,'.$id,
        ]);
        if ($validate->fails()){
            $request->session()->put('class_id',$id);
            return redirect()->back()->withErrors($validate)->withInput($request->all());
        }
        $class = Classes::findOrFail($id);
        $class->branch_id = $this->data['user']->detail_id;
        $class->class_name = $request->get('class_name');
        $class->save();
        return redirect()->back()->with('message','Classes updated successfully');
    }

    public function delete($id){
        $class = Classes::find($id);
        $class->delete();
        return Reply::success("Classes deleted successfully.");
    }
}
