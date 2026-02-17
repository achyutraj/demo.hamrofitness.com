<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class GymAdminManageRolesController extends GymAdminBaseController
{

    public function index() {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Roles';
        return view('gym-admin.gymroles.index', $this->data);
    }


    public function ajaxCreate() {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }
        if($this->data['user']->is_admin == 1){
            $roles = Role::get();
        }else{
            $roles = Role::where('detail_id',$this->data['user']->detail_id)->get();
        }
        return Datatables::of($roles)
            ->editColumn('name',function($row){
                if($this->data['user']->is_admin == 1){
                    $branchName = $row->commonDetails !== null ? ' ('.$row->commonDetails->title.')' : '';
                    return $row->name.$branchName ;
                }else{
                    return $row->name;
                }
            })
            ->addColumn(
                'action', function($row) {
                return '<div class="btn-group">
                        <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs">Action</span>
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li>
                                <a href="' . route('gym-admin.gymmerchantroles.edit', $row->id) . '" > <i class="fa fa-edit"></i> Edit</a>
                            </li>
                            <li>
                                <a href="' . route('gym-admin.gymmerchantroles.assign-permission', $row->id) . '" data-role-id="'.$row->id.'" class="assign-permissions"> <i class="fa fa-pencil"></i> Assign Permissions</a>
                            </li>
                            <li>
                                <a href="javascript:;" data-role-id="'.$row->id.'" class="remove-role"> <i class="fa fa-trash"></i> Remove</a>
                            </li>
                        </ul>
                    </div>';
            }
            )
            ->rawColumns(['action'])
            ->make();
    }

    public function create() {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Add Role';
        return view('gym-admin.gymroles.create', $this->data);
    }

    public function store(Request $request) {

        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }
        $validate = Validator::make($request->all(),[
            'name' => [
                'required',
                Rule::unique('roles')->where(function ($query) {
                    $query->where('detail_id',  $this->data['user']->detail_id);
                })
            ],
        ]);
        if($validate->fails()){
            $errors = $validate->errors();
            return redirect()->back()->with('errors',$errors)->withInput();
        }

        $role = new Role();
        $role->name = $request->input('name');
        $role->detail_id = $this->data['user']->detail_id;
        $role->guard_name = 'web';
        $role->save();
        return redirect(route('gym-admin.users.index'))->with('message','New Role Added Successfully');
    }

    public function edit($id) {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Edit Role';
        $this->data['role'] = Role::find($id);
        return view('gym-admin.gymroles.edit', $this->data);
    }

    public function update(Request $request,$id) {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        if($id == 1){
            return redirect(route('gym-admin.users.index'))->with('message','Super Admin Role cannot be edited.');
        }
        $role = Role::findorfail($id);
        $role->name = $request->name;
        $role->detail_id = $this->data['user']->detail_id;
        $role->guard_name = 'web';
        $role->update();
        return redirect(route('gym-admin.users.index'))->with('message','Role updated Successfully.');

    }

    public function destroy($id) {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }
        if($id == 1){
            return redirect(route('gym-admin.users.index'))->with('message','Super Admin Role cannot be deleted.');
        }
        $role = Role::find($id);
        $role->delete();
        return Reply::redirect(route('gym-admin.users.index'), 'Role Deleted Successfully.');
    }

    public function assignPermission($id) {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Assign Permission';

        $this->data['role'] = Role::find($id);

        $this->data['permissions'] = $this->data['role']->getAllPermissions();
        if($this->data['user']->is_admin == 1){
            $this->data['permissions_all'] = Permission::get();
        }else{
            $this->data['permissions_all'] = Permission::whereNotIn('id',[1,114,104])->get();
        }

        return view('gym-admin.gymroles.assign_permission', $this->data);
    }

    public function assignPermissionStore($id, Request $request) {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }
        $permissions = $request->input('permissions');
        $role = Role::find($id);
        $role->syncPermissions($permissions);
        return Reply::redirect(route('gym-admin.users.index'), 'Permissions updated.');
    }

}
