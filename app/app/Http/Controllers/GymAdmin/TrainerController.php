<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Controllers\GymAdmin\GymAdminBaseController;
use App\Models\Trainers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TrainerController extends GymAdminBaseController
{
    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'name' => [
                'required',
                Rule::unique('trainers')->where(function ($query) {
                    $query->where('branch_id',  $this->data['user']->detail_id);
                })
            ],
            'address' => 'required',
            'email' => 'required|email|unique:trainers,email',
            'phone' => 'required|unique:trainers,phone|digits:10'
        ]);
        if ($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput($request->all());
        }
        else{
            $trainer = new Trainers();
            $trainer->name = $request->name;
            $trainer->branch_id = $this->data['user']->detail_id;
            $trainer->address = $request->address;
            $trainer->email = $request->email;
            $trainer->phone = $request->phone;
            $trainer->save();
            return redirect()->back()->with('message','Trainer added successfully');
        }
    }

    public function update(Request $request,$id){
        $validate = Validator::make($request->all(),[
            'name' => ['required',Rule::unique('trainers')->ignore($id)],
            'email' => 'required|email|unique:trainers,email,'.$id,
            'phone' => ['required',Rule::unique('trainers')->ignore($id)],
            'address' => 'required|string',
        ]);
        if ($validate->fails()){
            $request->session()->put('trainer_id',$id);
            return redirect()->back()->withErrors($validate)->withInput($request->all());
        }
        $trainer = Trainers::findorfail($id);
        $trainer->name = $request->name;
        $trainer->branch_id = $this->data['user']->detail_id;
        $trainer->address = $request->address;
        $trainer->email = $request->email;
        $trainer->phone = $request->phone;
        $trainer->save();
        return redirect()->back()->with('message','Trainer updated successfully');
    }

    public function delete($id){
        $trainer = Trainers::find($id);
        if($trainer->class_schedules->count() > 0){
            return Reply::error("Unable to remove. Trainer has class schedule");
        }
        $trainer->delete();
        return Reply::success('Trainer removed successfully');
    }
}
