<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\Role;
use App\Models\Merchant;
use App\Models\MerchantBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DataTables;
use function PHPUnit\Framework\isEmpty;

class GymAdminManageUsersController extends GymAdminBaseController
{

    public function index() {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Users List';
        $this->data['userCount'] = Merchant::leftJoin('merchant_businesses', 'merchant_businesses.merchant_id', '=', 'merchants.id')
                    ->where('merchant_businesses.detail_id', $this->data['user']->detail_id)
                    ->select('merchants.id', 'merchants.username')
                    ->count();

        return view('gym-admin.users.index', $this->data);
    }

    public function create() {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Add User';
        return view('gym-admin.users.create', $this->data);
    }

    public function ajaxCreate() {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        if($this->data['user']->is_admin == 0) {
            $result = Merchant::leftJoin('merchant_businesses', 'merchant_businesses.merchant_id', '=', 'merchants.id')
                ->where('merchant_businesses.detail_id', $this->data['user']->detail_id)
                ->where('merchants.is_admin', '!=', 1)
                ->where('merchants.id', '!=', $this->data['user']->id)
                ->select('merchants.id', 'merchants.username')->get();
        } else {
            $result = Merchant::leftJoin('merchant_businesses', 'merchant_businesses.merchant_id', '=', 'merchants.id')
                ->where('merchant_businesses.detail_id', $this->data['user']->detail_id)
                ->groupBy('merchants.username')
                ->select('merchants.id', 'merchants.username')->get();
        }
        return Datatables::of($result)
            ->addColumn(
                'edit', function($row) {
                    return '<div class="btn-group">
                        <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs">Action</span>
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li>
                                <a href="javascript:;" data-user-id="'.$row->id.'" class="assign-role"> <i class="fa fa-pencil"></i> Assign Role</a>
                            </li>

                        </ul>
                    </div>';
                }
            )
            ->addColumn('role',function ($row){
                return $row->roles[0]->name ?? '';
            })
            ->rawColumns(['edit','role'])
            ->make();
    }

    public function edit($id) {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'User Edit';
        $this->data['merchant'] = Merchant::merchantDetail($this->data['user']->detail_id, $id);
        return view('gym-admin.users.edit', $this->data);
    }

    public function store() {

        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        $validator = Validator::make(request()->all(), Merchant::$addUserRules);

        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }

        $profile = new Merchant();
        $profile->first_name = request()->get('first_name');
        $profile->last_name = request()->get('last_name');
        $profile->mobile = request()->get('mobile');
        $profile->email = request()->get('email');
        $profile->gender = request()->get('gender');
        $profile->username = request()->get('username');
        $profile->gender = request()->get('gender');
        if (!empty(request()->get('date_of_birth') ))
            $profile->date_of_birth = request()->get('date_of_birth');

        if (!empty(request()->get('password')) ) {
            $profile->password = Hash::make(request()->get('password'));
        }
        $profile->save();
        $insert = [
            "merchant_id" => $profile->id,
            "detail_id" => $this->data['user']->detail_id
        ];

        MerchantBusiness::firstOrCreate($insert);

        return Reply::redirect(route('gym-admin.users.index'), 'New user added.');
    }

    public function update($id) {
        $validator = Validator::make(request()->all(), Merchant::updateRules($id));

        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }

        $id = request()->get('id');
        $profile = Merchant::find($id);
        $profile->first_name = request()->get('first_name');
        $profile->last_name = request()->get('last_name');
        $profile->mobile = request()->get('mobile');
        $profile->email = request()->get('email');
        $profile->gender = request()->get('gender');

        if (!empty(request()->get('date_of_birth') ))
            $profile->date_of_birth = request()->get('date_of_birth');

        if (!empty(request()->get('password')) ) {
            $profile->password = Hash::make(request()->get('password'));
        }

        $profile->save();
        return Reply::success('User details updated.');
    }

    public function destroy($id, Request $request) {
        if(!$this->data['user']->can("manage_permissions"))
        {
            return App::abort(401);
        }

        if ($request->ajax()) {
            Merchant::find($id)->delete();
            return Reply::redirect(route('gym-admin.users.index'), 'User removed successfully');
        }

        return Reply::error('Request not Valid');
    }

    public function assignRoleModal($id) {
        $this->data['roles'] = Role::where('detail_id',$this->data['user']->detail_id)->get();
        $this->data['user'] = Merchant::find($id);
        return view('gym-admin.users.assign_role_modal', $this->data);
    }

    public function assignRoleStore($id) {

        $this->data['title'] = "Assign Role";
        $this->data['roleSelected'] = "active open";

        $input = request()->all();
        $validator = Validator::make($input,[
            'role_id' => 'required',
        ] );

        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }
        $user = Merchant::find($id);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($input['role_id']);
        return Reply::redirect(route('gym-admin.users.index'), 'Role assigned.');
    }

}
