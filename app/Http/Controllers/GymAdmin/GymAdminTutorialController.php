<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\GymTutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;


class GymAdminTutorialController extends GymAdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['tutorialMenu'] = 'active';
        $this->data['tutorial']         = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_tutorials")) {
            return App::abort(401);
        }

        $this->data['tutorialMenu']     = 'active';
        $this->data['title'] = 'Gym Tutorials';
        return view('gym-admin.tutorial.index',$this->data);
    }

    public function ajax_create()
    {
        if (!$this->data['user']->can("view_tutorials")) {
            return App::abort(401);
        }
        $tutorials = GymTutorial::where('detail_id',$this->data['user']->detail_id)->orWhere('detail_id',null)->get();
        return Datatables::of($tutorials)
            ->editColumn('title', function ($row) {
                return $row->title;
            })
            ->editColumn('type', function ($row) {
                return $row->type;
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
                        <a href=" . route('gym-admin.tutorials.show', [$row->uuid]) . "><i class=\"fa fa-eye\"></i> View </a>
                    </li>
                    <li>
                        <a href=" . route('gym-admin.tutorials.edit', [$row->uuid]) . "><i class=\"fa fa-edit\"></i> Edit </a>
                    </li>
                    <li>
                        <a class=\"delete-button\" data-tutorial-id='$row->uuid' href=\"javascript:;\"><i class=\"fa fa-trash\"></i> Delete </a>
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
        $this->data['tutorialMenu']     = 'active';
        $this->data['title'] = 'Add Tutorial';
        return view('gym-admin.tutorial.create',$this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("add_tutorials")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),GymTutorial::rules('add'));

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $inputData = $request->all();
        if($request->get('is_default') == 0){
            $inputData['detail_id'] = $this->data['user']->detail_id;
        }
        if($request->hasFile('image')){
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = rand(10000, 99999) . "." . $extension;
            $directory = public_path('/uploads/gym_tutorial/');
            
            // Create directory if it doesn't exist
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            
            $path = $directory . $filename;
            $img = Image::make($image->getRealPath());
            $img->save($path);
            $inputData['image'] = $filename;
        }
        
        GymTutorial::create($inputData);
        return redirect()->route('gym-admin.tutorials.index')->with('success', 'Gym Tutorial Added Successfully');
        
    }

    public function edit($id)
    {
        if (!$this->data['user']->can("edit_tutorials")) {
            return App::abort(401);
        }
        $this->data['title'] = "Edit Tutorial";
        $this->data['tutorialMenu']     = 'active';
        $this->data['tutorial'] = GymTutorial::findByUid($id);
        return view('gym-admin.tutorial.edit',$this->data);
    }

    public function update(Request $request,$id)
    {
        $validator =  Validator::make($request->all(),GymTutorial::rules('add'));
       if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withErrors($errors)->withInput();
        }
        $inputData = $request->all();
        $package = GymTutorial::findByUid($id);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = rand(10000, 99999) . "." . $extension;
            $directory = public_path('/uploads/gym_tutorial/');
            
            // Create directory if it doesn't exist
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            
            $path = $directory . $filename;
            $img = Image::make($image->getRealPath());
            $img->save($path);
            $inputData['image'] = $filename;

            if ($package->image != null) {
                $path = public_path('uploads/gym_tutorial/');
                @unlink($path. $package->image);
            }
        }

        if($request->get('is_default') == 0){
            $inputData['detail_id'] = $this->data['user']->detail_id;
        }
        $package->update($inputData);
        return redirect()->route('gym-admin.tutorials.index')->with('success', 'Gym Tutorial updated Successfully');
    }

    public function remove($id) {
        if (!$this->data['user']->can("delete_tutorials")) {
            return App::abort(401);
        }

        $this->data['tutorial'] = GymTutorial::findByUid($id);
        return view('gym-admin.tutorial.destroy', $this->data);
    }

    public function destroy($id)
    {
        if (!$this->data['user']->can("delete_tutorials")) {
            return App::abort(401);
        }
        if(request()->ajax()){
            $package = GymTutorial::findByUid($id);
            if ($package->image != null) {
                $path = public_path('uploads/gym_tutorial/');
                @unlink($path. $package->image);
            }
            $package->delete();
            return Reply::success("GymTutorial deleted successfully.");
        }
    }

    public function show($id) {
        $this->data['tutorial'] = GymTutorial::findByUid($id);
        return view('gym-admin.tutorial.show', $this->data);
    }

}
