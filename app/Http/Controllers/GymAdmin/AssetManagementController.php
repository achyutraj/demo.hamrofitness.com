<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\AssetManagement;
use App\Models\AssetService;
use App\Models\Employ;
use App\Models\EmployAsset;
use App\Models\GymSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AssetManagementController extends GymAdminBaseController
{
    public function index()
    {
        if (!$this->data['user']->can("view_assets")) {
            return App::abort(401);
        }

        $this->data['title'] = "Asset Management";
        $this->data['employs'] = Employ::where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['assets'] = AssetManagement::with('suppliers')->where('branch_id', $this->data['user']->detail_id)->get();
        $this->data['employAssets'] = EmployAsset::whereRelation('employee', 'detail_id', $this->data['user']->detail_id)->get();
        $this->data['suppliers'] = GymSupplier::where('branch_id', $this->data['user']->detail_id)->get();
        $this->data['assetServices'] = AssetService::where('detail_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.assets.index', $this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("add_assets")) {
            return App::abort(401);
        }
        $assets = new AssetManagement();
        $assets->tag = $request->get('tag');
        $assets->name = $request->get('name');
        $assets->brand_name = $request->get('brand_name');
        $assets->branch_id = $this->data['user']->detail_id;
        $assets->supplier_id = $request->get('supplier');
        $assets->quantity = $request->get('quantity');
        $assets->asset_model = $request->get('asset_model');
        $assets->purchase_date = $request->get('purchase_date');
        $assets->save();
        return redirect()->back()->with('message', 'Asset Data Stored Successfully');
    }

    public function edit(Request $request, $id)
    {
        if (!$this->data['user']->can("edit_assets")) {
            return App::abort(401);
        }
        $assets = AssetManagement::findOrFail($id);
        $assets->tag = $request->tag;
        $assets->name = $request->name;
        $assets->brand_name = $request->brand_name;
        $assets->supplier_id = $request->supplier;
        $assets->quantity = $request->quantity;
        $assets->asset_model = $request->asset_model;
        $assets->tag = $request->tag;
        $assets->purchase_date = $request->purchase_date;
        $assets->save();
        return redirect()->back()->with('message', 'Asset Data Edited Successfully');
    }

    public function delete($id)
    {
        if (!$this->data['user']->can("delete_assets")) {
            return App::abort(401);
        }
        $assets = AssetManagement::find($id);
        if($assets->employeeAssets->count() > 0) {
            return Reply::error("Unable to remove. Asset has assign to employee.");
        }
        $assets->delete();
        return Reply::success("Asset deleted successfully.");
    }

    // Assign Employ
    public function assignUser(Request $request)
    {
        $assets = AssetManagement::findOrFail($request->get('asset_id'));
        $employ = new EmployAsset();
        $employ->asset_id = $request->get('asset_id');
        $employ->employ_id = $request->get('employ_id');
        $employ->quantity = $request->get('quantity_working');
        $employ->working_quantity = $request->get('quantity_working');
        $employ->working_remarks = $request->get('working_remarks');
        $employ->save();
        $assets->quantity_working = $assets->quantity_working + $request->get('quantity_working');
        $assets->save();
        return redirect()->back()->with('message', 'Asset Data Successfully assigned to user');
    }

    public function editAssetUsage(Request $request, $id)
    {
        $emps = EmployAsset::findOrFail($id);
        $emps->working_quantity = $request->get('quantity_working') - ($request->get('quantity_repairing') + $request->get('quantity_damaged'));
        $emps->repair_quantity = $request->get('quantity_repairing');
        $emps->damaged_quantity = $request->get('quantity_damaged');
        $emps->working_remarks = $request->get('working_remarks');
        $emps->save();

        $assets = AssetManagement::findOrFail($emps->asset_id);
        $data = EmployAsset::selectRaw('asset_id,sum(working_quantity) as workingQty, sum(repair_quantity) as repairingQty , sum(damaged_quantity) as damageQty')->where('asset_id', $emps->asset_id)
            ->groupBy('asset_id')->get();
        $assets->quantity_working = $data[0]->workingQty;
        $assets->quantity_repair = $data[0]->repairingQty;
        $assets->quantity_damaged = $data[0]->damageQty;
        $assets->save();
        return redirect()->back()->with('message', 'Asset Data Successfully Updated');

    }

    public function deleteAssetUsage($id)
    {
        $empAsset = EmployAsset::find($id);
        $assets = AssetManagement::find($empAsset->asset_id);
        $assets->quantity_working = ($assets->quantity_working - $empAsset->quantity);
        $assets->save();
        $empAsset->delete();
        return Reply::success("Assigned Asset Data Successfully Deleted.");
    }
    
    //Asset Services
    public function assetServicesStore(Request $request)
    {
        $service = new AssetService();
        $service->asset_id = $request->get('asset_id');
        $service->detail_id = $this->data['user']->detail_id;
        $service->added_by = $this->data['user']->id;
        $service->service_by = $request->get('service_by');
        $service->service_date = $request->get('service_date');
        $service->next_service_date = $request->get('next_service_date');
        $service->remarks = $request->get('remarks');
        $service->save();
        return redirect()->back()->with('message', 'Asset Service Successfully Added');
    }

    public function assetServicesUpdate(Request $request)
    {
        $service = AssetService::findOrFail($request->get('service_id'));
        $service->service_by = $request->get('service_by');
        $service->service_date = $request->get('service_date');
        $service->next_service_date = $request->get('next_service_date');
        $service->remarks = $request->get('remarks');
        $service->save();
        return redirect()->back()->with('message', 'Asset Service Successfully Updated');
    }

    public function assetServicesDelete($id)
    {
        $service = AssetService::findOrFail($id);
        $service->delete();
        return Reply::success("Asset Service deleted successfully.");
    }
}
