<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TemplateController extends GymAdminBaseController
{
    public function index()
    {
        if (!$this->data['user']->can("templates")) {
            return App::abort(401);
        }
        $this->data['templateMenu'] = 'active';
        $this->data['title']           = "SMS Template";
        return view('gym-admin.templates.index', $this->data);
    }

    public function ajax_create() {
        if (!$this->data['user']->can("templates")) {
            return App::abort(401);
        }
        $templates = Template::where('detail_id',$this->data['user']->detail_id)->get();
        return DataTables::of($templates)
            ->editColumn('status',function($row){
                if($row->status) {
                    return '<label class="label label-success">Active</label>';
                }else{
                    return '<label class="label label-danger">Inactive</label>';
                }
            })
            ->addColumn('action',function($row){
                return "<div class=\"btn-group\">
                    <button class=\"btn btn-xs blue dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"true\"><i class=\"fa fa-gears\"></i><span class=\"hidden-xs hidden-medium\">ACTION</span>
                    <i class=\"fa fa-angle-down\"></i>
                    </button>
                    <ul class=\"dropdown-menu pull-right\" role=\"menu\">
                    <li>
                        <a href=" . route('gym-admin.templates.edit', [$row->uuid]) . "><i class=\"fa fa-edit\"></i> Edit </a>
                    </li>
                    </ul>
                </div>";
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    public function create() {
        if (!$this->data['user']->can("templates")) {
            return App::abort(401);
        }
        $this->data['templateMenu']     = 'active';
        $this->data['title'] = 'Add Template';
        $this->data['tags'] = Template::getTags();
        $this->data['types'] = Template::getTypes();
        return view('gym-admin.templates.create',$this->data);
    }

    public function store(Request $request){
        if (!$this->data['user']->can("templates")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),Template::rules('add'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }
        $oldTemplate = Template::where('detail_id',$this->data['user']->detail_id)
                    ->where('type',$request->get('type'))->get();
        if(count($oldTemplate) > 0){
            $type = ucfirst($request->get('type'));
            return Reply::error($type.' Template has already been created.');
        }
        $template = new Template();
        $template->name = $request->get('name');
        $template->status = true;
        $template->type = $request->get('type');
        $template->detail_id = $this->data['user']->detail_id;
        $template->message = $request->get('message');
        $template->save();
        return Reply::redirect(route('gym-admin.templates.index'), 'Template store Successfully');

    }

    public function edit($id){
        if (!$this->data['user']->can("templates")) {
            return App::abort(401);
        }
        $this->data['templateMenu']     = 'active';
        $this->data['title'] = 'Edit Template';

        $this->data['template'] = Template::businessTemplate($this->data['user']->detail_id,$id);
        $this->data['tags'] = Template::getTags();
        return view('gym-admin.templates.edit',$this->data);
    }

    public function update(Request $request,$id) {
        if (!$this->data['user']->can("templates")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),Template::rules('update'));
        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }
        $template = Template::businessTemplate($this->data['user']->detail_id,$id);
        $template->update([
            'name' => $request->get('name'),
            'message' => $request->get('message'),
        ]);
        return Reply::redirect(route('gym-admin.templates.index'), 'Template updated Successfully');
    }

}
