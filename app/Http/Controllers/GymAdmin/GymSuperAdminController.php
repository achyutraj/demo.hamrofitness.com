<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Requests\GymAdmin\BranchSetup\BranchStoreRequest;
use App\Http\Requests\GymAdmin\BranchSetup\BranchUpdateRequest;
use App\Http\Requests\GymAdmin\BranchSetup\ManagerStoreRequest;
use App\Http\Requests\GymAdmin\BranchSetup\RoleAndPermissionUpdateRequest;
use App\Http\Requests\GymAdmin\BranchSetup\RoleStoreRequest;
use App\Models\BusinessBranch;
use App\Models\BusinessCategory;
use App\Models\BusinessRenewHistory;
use App\Models\Category;
use App\Models\Common;
use App\Models\GymClient;
use App\Models\GymEnquiries;
use App\Models\GymExpense;
use App\Models\GymMembershipPayment;
use App\Models\Role;
use App\Models\GymPurchase;
use App\Models\GymSetting;
use App\Models\Merchant;
use App\Models\MerchantBusiness;
use App\Models\ProductPayment;
use App\Models\ProductSales;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class GymSuperAdminController extends GymAdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['indexSuperAdmin'] = 'active';
        $this->data['title'] = 'Manage Branches';
        return view('gym-admin.super-admin.index', $this->data);
    }

    public function manageBranches()
    {
        $this->data['indexSuperAdmin'] = 'active';
        $this->data['title'] = 'Manage Branches';
        return view('gym-admin.super-admin.index', $this->data);
    }

    public function branchWithSMSCreditList()
    {
        $this->data['indexSuperAdmin'] = 'active';
        $this->data['title'] = 'Manage Branches';
        return view('gym-admin.super-admin.sms_index', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['title'] = "Manage Branches - Edit Branch";
        $this->data['branchData'] = Common::find($id);

        $this->data['roles'] = Role::where('name', '!=', 'Super Admin')->get();
        $this->data['managerData'] = Merchant::where('detail_id', $this->data['branchData']->id)->first();
        $this->data['managers'] = Merchant::select('merchants.id', 'merchants.first_name', 'merchants.middle_name')
            ->leftJoin('merchant_businesses', 'merchant_businesses.merchant_id', '=', 'merchants.id')
            ->where('merchant_businesses.detail_id', '=', $id)
            ->get();

        return view('gym-admin.super-admin.edit-branch', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BranchUpdateRequest $request, $id)
    {
        //region Update Branch Details
        $branchUpdateData = Common::find($id);
        $branchUpdateData->title = $request->get('title');
        $branchUpdateData->slug = Str::slug($request->title,'-');
        $branchUpdateData->address = $request->get('address');
        $branchUpdateData->owner_incharge_name = $request->get('owner_incharge_name');
        $branchUpdateData->phone = $request->get('phone');
        $branchUpdateData->email = $request->get('email');
        $branchUpdateData->has_device = $request->get('has_device');
        $branchUpdateData->start_date = $request->get('start_date');
        $branchUpdateData->end_date = $request->get('end_date');
        $branchUpdateData->save();
        //endregion

        $businessBranch = BusinessBranch::where('detail_id',$id)->first();
        $businessBranch->owner_incharge_name = $request->get('owner_incharge_name');
        $businessBranch->address = $request->get('address');
        $businessBranch->phone = $request->get('phone');
        $businessBranch->save();

        $merchant = Merchant::find($request->merchant_id);
        if (!is_null($request->get('username'))) {
            $merchant->username = $request->get('username');
        }
        if (!is_null($request->get('password'))) {
            $merchant->password = Hash::make($request->password);
        }
        $merchant->save();

        return Reply::redirect(route('gym-admin.superadmin.index'), 'Branch is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        MerchantBusiness::select('merchant_id')
            ->leftJoin('merchants', 'merchants.id', '=', 'merchant_businesses.merchant_id')
            ->where('merchant_businesses.detail_id', '=', $id)
            ->where('merchants.is_admin', '=', 0)
            ->delete();
        $branch = Common::count();
        if ($branch > 1) {
            Common::destroy($id);
            return Reply::success('Branch is deleted successfully.');
        }

        return Reply::error('Branch cannot be deleted. There should be at least one branch');
    }

    public function showDashboard()
    {
        $this->data['indexSuperAdmin '] = '';
        $this->data['title'] = 'Super Admin Dashboard';
        $this->data['branchCount'] = Common::count();
        $this->data['customerCount'] = GymClient::where('is_client','yes')->count();
        $totalMembershipEarnings = GymMembershipPayment::leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'purchase_id')
            ->sum('payment_amount');
        $totalProductEarnings = ProductPayment::leftJoin('product_sales', 'product_sales.id', '=', 'product_sale_id')
            ->sum('payment_amount');
        $this->data['totalEarnings'] = $totalMembershipEarnings + $totalProductEarnings;

        $currentMembershipMonthEarnings = GymMembershipPayment::whereBetween('gym_membership_payments.payment_date', [Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')])
            ->sum('payment_amount');
        $currentProductMonthEarnings = ProductPayment::whereBetween('product_payments.payment_date', [Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')])
            ->sum('payment_amount');
        $this->data['currentMonthEarnings'] = $currentMembershipMonthEarnings + $currentProductMonthEarnings;


        $this->data['currentMonthEnquiries'] = GymEnquiries::whereBetween('enquiry_date', [Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')])
            ->count();

        $unpaidMembershipMember = GymPurchase::where('next_payment_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('payment_required', 'yes')
            ->count();
        $unpaidProductMembers = ProductSales::where('next_payment_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('payment_required', 'yes')
            ->count();
        $this->data['unpaidMembers'] = $unpaidMembershipMember + $unpaidProductMembers;

        $this->data['expiringBranches'] = Common::select('common_details.id', 'common_details.title', 'common_details.owner_incharge_name', 'common_details.email', 'common_details.phone', 'common_details.start_date as joins_on', 'common_details.end_date as expires_on','common_details.has_device as has_device')
            ->leftJoin('business_branches', 'business_branches.detail_id', '=', 'common_details.id')
            ->leftJoin('merchants', 'merchants.detail_id', '=', 'common_details.id')
            ->where('common_details.end_date', '<=', Carbon::today()->addDays(45))
            ->where('common_details.end_date', '>=', Carbon::today())
            ->orderBy('common_details.end_date', 'desc')->groupBy('common_details.id')
            ->get();

        $this->data['recentlyActive'] = Merchant::recentlyActive();

        $this->data['userActiveInDays'] = Merchant::userActiveInDays(30);

        $this->data['notActiveUsers'] = Merchant::notActiveUsers();

        $dueMembershipPayments = GymPurchase::sum(DB::raw('amount_to_be_paid - paid_amount'));

        $dueProductPayments = ProductSales::sum(DB::raw('total_amount - paid_amount'));

        $this->data['duePayments'] = $dueMembershipPayments + $dueProductPayments;

        $this->data['currentMonthExpense'] = GymExpense::whereBetween('purchase_date', [Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')])
            ->sum('price');

        return view('gym-admin.super-admin.dashboard', $this->data);
    }

    public function getData()
    {
        $branches = Common::select('common_details.id', 'common_details.title', 'common_details.owner_incharge_name', 'common_details.email', 'common_details.phone',
                'common_details.end_date as expires_on','common_details.has_device as has_device','common_details.auth_key',
                'common_details.start_date as start_date','common_details.address as address')
            ->leftJoin('business_branches', 'business_branches.detail_id', '=', 'common_details.id')
            ->leftJoin('merchants', 'merchants.detail_id', '=', 'common_details.id')
            ->groupBy('common_details.id')->groupBy('common_details.id')->get();

        return Datatables::of($branches)->addColumn('actions', function ($row) {
            $action = '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown" ><i class="fa fa-gears"></i> <span class="hidden-xs hidden-medium">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">';

                    if($row->has_device){
                        $action .= '<li>
                        <a href="' . route('gym-admin.superadmin.branchKeyGenerate', $row->id) . '"> <i class="fa fa-key"></i>Key Generate</a>
                    </li>';
                    }
            $action .= '<li>
                            <a href="' . route('gym-admin.superadmin.edit', $row->id) . '"> <i class="fa fa-edit"></i>Edit</a>
                        </li>
                        <li>
                            <a data-branch-id="'.$row->id.'" href="javascript:;"  class="view-renew-history"> <i class="fa fa-eye"></i>View Histroy</a>
                        </li>
                        <li>
                            <a data-branch-id="'.$row->id.'" href="javascript:;"  class="branch-renew-model"> <i class="icon-refresh"></i>Add Renew</a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="deleteModal(' . $row->id . ')"> <i class="fa fa-trash"></i>Delete</a>
                        </li>
                    </ul>
                </div>';

                return $action;
        })
            ->addColumn('expires_on', function ($row) {
                $date = $row->expires_on;
                $status = 'success';
                if ($date > Carbon::today()->addDays(45)) {
                    $status = 'success';
                } elseif ($date > Carbon::today()->format('Y-m-d')) {
                    $status = 'warning';
                } else {
                    $status = 'danger';
                }
                return $date . ' <div class="badge badge-' . $status . '">' . Carbon::parse($row->expires_on)->diffForHumans() . '</div>';
            })
            ->addColumn('has_device', function ($row) {
                $data = $row->has_device == 1 ? 'Yes' : 'No';
                $status = 'success';
                if ($row->has_device == 0) {
                    $status = 'danger';
                }
                return ' <div class="badge badge-' . $status . '">' . $data . '</div>';
            })
            ->addColumn('sms_status', function ($row) {
                if ($row->setting->sms_status == 'disabled') {
                    return '<div class="badge badge-danger">Disabled</div>';
                } else{
                    return '<div class="badge badge-success">Enabled</div>';
                }

            })
            ->editColumn('owner_incharge_name', function ($row) {
                return $row->owner_incharge_name ;
            })
            ->editColumn('start_date', function ($row) {
                return date('M d, Y',strtotime($row->start_date)) ;
            })
            ->editColumn('email', function ($row) {
                return $row->email.'<br>'.$row->auth_key;
            })
            ->editColumn('phone', function ($row) {
                return $row->phone;
            })
            ->removeColumn('id')
            ->rawColumns(['actions', 'expires_on','has_device','email','sms_status'])
            ->make(true);
    }

    public function getBranchDataWithSMSCredit()
    {
        $branches = Common::select('common_details.id', 'common_details.title', 'common_details.owner_incharge_name', 'common_details.email', 'common_details.phone',
                'common_details.end_date as expires_on','common_details.has_device as has_device','common_details.auth_key',
                'common_details.start_date as start_date','common_details.address as address')
            ->leftJoin('business_branches', 'business_branches.detail_id', '=', 'common_details.id')
            ->leftJoin('merchants', 'merchants.detail_id', '=', 'common_details.id')
            ->leftJoin('gym_settings', 'gym_settings.detail_id', '=', 'common_details.id')
            ->where('gym_settings.sms_status','enabled')
            ->groupBy('common_details.id')->groupBy('common_details.id')->get();

        return Datatables::of($branches)->addColumn('actions', function ($row) {
            $action = '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown" ><i class="fa fa-gears"></i> <span class="hidden-xs hidden-medium">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">';

                    if($row->has_device){
                        $action .= '<li>
                        <a href="' . route('gym-admin.superadmin.branchKeyGenerate', $row->id) . '"> <i class="fa fa-key"></i>Key Generate</a>
                    </li>';
                    }
            $action .= '<li>
                            <a href="' . route('gym-admin.superadmin.edit', $row->id) . '"> <i class="fa fa-edit"></i>Edit</a>
                        </li>
                        <li>
                            <a data-branch-id="'.$row->id.'" href="javascript:;"  class="view-renew-history"> <i class="fa fa-eye"></i>View Histroy</a>
                        </li>
                        <li>
                            <a data-branch-id="'.$row->id.'" href="javascript:;"  class="branch-renew-model"> <i class="icon-refresh"></i>Add Renew</a>
                        </li>
                        <li>
                            <a href="javascript:;" onclick="deleteModal(' . $row->id . ')"> <i class="fa fa-trash"></i>Delete</a>
                        </li>
                    </ul>
                </div>';

                return $action;
        })
            ->addColumn('expires_on', function ($row) {
                $date = $row->expires_on;
                $status = 'success';
                if ($date > Carbon::today()->addDays(45)) {
                    $status = 'success';
                } elseif ($date > Carbon::today()->format('Y-m-d')) {
                    $status = 'warning';
                } else {
                    $status = 'danger';
                }
                return $date . ' <div class="badge badge-' . $status . '">' . Carbon::parse($row->expires_on)->diffForHumans() . '</div>';
            })
            ->addColumn('has_device', function ($row) {
                $data = $row->has_device == 1 ? 'Yes' : 'No';
                $status = 'success';
                if ($row->has_device == 0) {
                    $status = 'danger';
                }
                return ' <div class="badge badge-' . $status . '">' . $data . '</div>';
            })
            ->addColumn('sms_status', function ($row) {
                $credit = getSMSCreditBalance($row->id);
                return '<div class="badge badge-success">SMS Credit: '.$credit.'</div>';

            })
            ->editColumn('owner_incharge_name', function ($row) {
                return $row->owner_incharge_name ;
            })
            ->editColumn('start_date', function ($row) {
                return date('M d, Y',strtotime($row->start_date)) ;
            })
            ->editColumn('email', function ($row) {
                return $row->email.'<br>'.$row->auth_key;
            })
            ->editColumn('phone', function ($row) {
                return $row->phone;
            })
            ->removeColumn('id')
            ->rawColumns(['actions', 'expires_on','has_device','email','sms_status'])
            ->make(true);
    }

    public function branchPage($id = null)
    {
        $this->data['title'] = "Manage Branches - Add Branch";
        $this->data['completedItems'] = 1;
        $this->data['completedItemsRequired'] = 5;
        $this->data['manager_id'] = session('manager_id');
        $this->data['branchData'] = Common::find($id);
        return view('gym-admin.super-admin.create-branches.branch', $this->data);
    }

    public function storeBranchPage(BranchStoreRequest $request)
    {
        try{
            DB::beginTransaction();
            //region Common Detail
            $branch = Common::firstOrNew(['id' => $request->get('branch_id')]);
            $branch->title = $request->title;
            $branch->slug = Str::slug($request->title,'-');
            $branch->owner_incharge_name = $request->owner_incharge_name;
            $branch->address = $request->address;
            $branch->phone = $request->phone;
            $branch->email = $request->email;
            $branch->start_date = $request->start_date;
            $branch->end_date = $request->end_date;
            $branch->has_device = $request->has_device;
            $branch->save();
            //endregion

            //region Business Branch
            $businessBranch = new BusinessBranch();
            $businessBranch->detail_id = $branch->id;
            $businessBranch->owner_incharge_name = $branch->owner_incharge_name;
            $businessBranch->address = $branch->address;
            $businessBranch->phone = $branch->phone;
            $businessBranch->save();
            //endregion

            //region Category
            $category = Category::first();
            //endregion

            //region Business Category
            $businessCategory = new BusinessCategory();
            $businessCategory->category_id = $category->id;
            $businessCategory->detail_id = $branch->id;
            $businessCategory->save();
            //endregion

            //region Gym Setting
            $gymSetting = new GymSetting();
            $gymSetting->detail_id = $branch->id;
            $gymSetting->currency_id = $this->data['gymSettings']->currency_id;
            $option = GymSetting::getOptions();
            $gymSetting->options = json_encode($option);
            $gymSetting->save();
            //endregion

            //Create Template for Sms
            $templates = getSmsTemplate($branch->id);
            $branch->templates()->createMany($templates);
            DB::commit();
            session([
                'branch_id' => $branch->id
            ]);
            return Reply::redirect(route('gym-admin.superadmin.manager'), 'Branch is added successfully.');

        }catch(Exception $e){
            DB::rollBack();
            throw $e;
        }

    }

    public function managerPage($id = null)
    {
        $this->data['title'] = "Manage Branches - Add Manager";
        $this->data['completedItems'] = 2;
        $this->data['completedItemsRequired'] = 5;
        $this->data['branch_id'] = session('branch_id');
        $this->data['managerData'] = Merchant::find($id);
        return view('gym-admin.super-admin.create-branches.manager', $this->data);
    }

    public function storeManagerPage(ManagerStoreRequest $request)
    {
        try{
            DB::beginTransaction();
            //region Merchant
            $manager = Merchant::firstOrNew(['id' => $request->get('manager_id')]);
            $manager->first_name = $request->first_name;
            $manager->middle_name = $request->middle_name;
            $manager->last_name = $request->last_name;
            $manager->email = $request->email;
            $manager->gender = $request->gender;
            $manager->mobile = $request->mobile;
            $manager->date_of_birth = $request->date_of_birth;
            if (!is_null($request->get('password'))) {
                $manager->password = Hash::make($request->password);
            }
            $manager->detail_id = $request->get('branch_id');
            $manager->username = $request->username;
            $manager->user_type = 'branch_admin';
            $manager->save();
            //endregion

            //region Merchant Business
            $merchantBusiness = MerchantBusiness::firstOrNew(
                ['merchant_id' => $manager->id],
                ['detail_id' => $request->get('branch_id')]
            );
            $merchantBusiness->detail_id = $request->get('branch_id');
            $merchantBusiness->merchant_id = $manager->id;
            $merchantBusiness->save();
            //endregion
            DB::commit();
            session(['manager_id' => $manager->id]);
            return Reply::redirect(route('gym-admin.superadmin.role'), 'Manager is added successfully.');
        }catch(Exception $e){
            DB::rollBack();
            throw $e;
        }

    }

    public function rolePage($id = null)
    {
        $this->data['title'] = "Manage Branches - Add Role";
        $this->data['completedItems'] = 3;
        $this->data['completedItemsRequired'] = 5;
        $this->data['manager_id'] = session('manager_id');
        $this->data['branchData'] = Common::find(session('branch_id'));
        $this->data['role'] = Role::where('name', 'Branch Manager')->first();
        return view('gym-admin.super-admin.create-branches.role', $this->data);
    }

    public function storeRolePage(RoleStoreRequest $request)
    {
        //region Gym Merchant Role
        $id = session('manager_id');
        $gymManager = Merchant::find($id);
        $gymManagerRole = Role::find($request->get('role_id'));
        DB::table('model_has_roles')->where('model_id', $gymManagerRole)->delete();
        $gymManager->assignRole($request->input('role_id'));
        return Reply::redirect(route('gym-admin.superadmin.complete'), 'Role is added successfully.');
    }

    public function completePage()
    {
        $this->data['title'] = "Manage Branches - Complete";

        //region forget previous stored session
        session()->forget('manager_id');
        session()->forget('branch_id');
        session()->forget('role_id');
        //endregion

        return view('gym-admin.super-admin.create-branches.complete', $this->data);
    }

    public function setBusinessId(Request $request)
    {
        session(['business_id' => $request->businessId]);

        return Reply::dataOnly(['success' => true]);
    }

    public function updateRolesAndPermissionPage(RoleAndPermissionUpdateRequest $request)
    {
        //region check role for particular manager exist
        $checkManager = Merchant::find($request->manager_id);
        $checkManagerRole = $checkManager->roles();

        if ($checkManagerRole > 0) {
            return Reply::success('Role is already assigned to manager');
        }
        //endregion
        $checkManager->assignRole($request->get('role_id'));
        return Reply::redirect(route('gym-admin.superadmin.index'), 'Role is changed successfully.');
    }

    public function updateRolesAndPermission(RoleAndPermissionUpdateRequest $request)
    {
        //region check role for particular manager exist
        $checkManager = Merchant::find($request->get('manager_id'));
        $checkManagerRole = $checkManager->roles()->count();

        if ($checkManagerRole > 0) {
            return Reply::success('Role is already assigned to manager');
        }
        DB::table('model_has_roles')->where('model_id', $request->get('manager_id'))->delete();
        $checkManager->assignRole($request->input('role_id'));
        return Reply::redirect(route('gym-admin.superadmin.index'), 'Role is changed successfully.');
    }

    public function branchKeyGenerate($branchId){
        $branch = Common::findOrFail($branchId);
        $branch->update([
            'auth_key' => Str::random(30)
        ]);
        return redirect()->back()->with('success','Key is generate successfully.');

    }

    public function renewBranchModel($id)
    {
        $this->data['enquiry'] = Common::where('id',$id)->first();
        $endDate = Carbon::parse($this->data['enquiry']->end_date);
        $now = Carbon::now();

        if ($endDate->isPast()) {
            $this->data['remaining_days'] = 0;
        } else {
            $this->data['remaining_days'] = $now->diffInDays($endDate);
        }

        return view('gym-admin.super-admin.business-renew', $this->data);
    }

    public function saveBranchRenew(Request $request)
    {
        $followUpValidator = Validator::make($inputData = $request->all(), BusinessRenewHistory::$rules);

        if ($followUpValidator->fails()) {
            return Reply::formErrors($followUpValidator);
        } else {
            $branch = Common::where('id',$inputData['detail_id'])->first();

            $inputData['detail_id']    = $branch->id;
            $inputData['renew_end_date'] = Carbon::createFromFormat('m/d/Y', $inputData['renew_start_date'])
                                        ->addMonths($inputData['package_offered'])
                                        ->addDays($inputData['remaining_days']);
            BusinessRenewHistory::create($inputData);

            // Save follow up dates to gym_enquiry table
            $branch->end_date     = $inputData['renew_end_date'];
            $branch->save();

            return Reply::success('Branch Renew created successfully.');
        }

    }

    public function renewHistory($id)
    {
        $this->data['branch'] = Common::where('id', $id)->first();
        return view('gym-admin.super-admin.view-renew-history', $this->data);
    }

}
