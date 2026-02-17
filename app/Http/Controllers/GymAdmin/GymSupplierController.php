<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\GymSupplier;
use Illuminate\Http\Request;
use App\Http\Controllers\GymAdmin\GymAdminBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class GymSupplierController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['paymentMenu'] = 'active';
        $this->data['supplierMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_suppliers")) {
            return App::abort(401);
        }
        $this->data['title'] = "All Suppliers";

        $this->data['suppliers'] = GymSupplier::where('branch_id', $this->data['user']->detail_id)->get();

        return view('gym-admin.gymsuppliers.index', $this->data);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_suppliers")) {
            return App::abort(401);
        }
        $this->data['title'] = "Add Supplier";

        return view('gym-admin.gymsuppliers.create', $this->data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), GymSupplier::rules('add'));
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $inputData = $request->all();

            $inputData['branch_id'] = $this->data['user']->detail_id;

            GymSupplier::create($inputData);
            return Reply::redirect(route('gym-admin.suppliers.index'), "Supplier added successfully");
        }
    }

    public function edit($id)
    {
        if (!$this->data['user']->can("edit_suppliers")) {
            return App::abort(401);
        }
        $this->data['title'] = "Edit Supplier";

        $this->data['supplier'] = GymSupplier::find($id);

        return view('gym-admin.gymsuppliers.edit', $this->data);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        if (!$this->data['user']->can("edit_suppliers")) {
            return App::abort(401);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'required|digits:10',
            'email' => 'nullable|email|unique:gym_suppliers,email,' . $id,
        ]);
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $inputData = $request->all();

            $supplier = GymSupplier::find($id);
            $supplier->name = $inputData['name'];
            $supplier->email = $inputData['email'];
            $supplier->phone = $inputData['phone'];
            $supplier->address = $inputData['address'];
            $supplier->save();

            return Reply::redirect(route('gym-admin.suppliers.index'), "Supplier updated successfully");
        }
    }

    public function destroy($id)
    {
        if (!$this->data['user']->can("delete_suppliers")) {
            return App::abort(401);
        }
        if(request()->ajax()){
            $supplier = GymSupplier::find($id);
            if($supplier->product->count() > 0){
                return Reply::error("Supplier has product");
            }
            $supplier->delete();

            return Reply::redirect(route('gym-admin.suppliers.index'), "Supplier deleted successfully");
        }
    }
}
