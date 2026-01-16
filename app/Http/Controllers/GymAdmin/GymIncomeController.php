<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\GymAdmin\GymIncome\StoreUpdateRequest;
use App\Models\GymSupplier;
use App\Models\Income;
use App\Models\IncomeCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use DataTables;

class GymIncomeController extends GymAdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->data['paymentMenu'] = 'active';
        $this->data['incomeMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("expense")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Income Collection';
        return view('gym-admin.incomes.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->data['user']->can("expense")) {
            return App::abort(401);
        }

        $this->data['title'] = "Income Create";
        $this->data['categories'] = IncomeCategory::whereNull('detail_id')
            ->orWhere(function($query){
                $query->where('detail_id',$this->data['user']->detail_id);
            })->latest()->get();
        $this->data['paymentSources'] = listPaymentType() ;
        $this->data['suppliers'] = GymSupplier::where('branch_id',$this->data['user']->detail_id)->get();
        return view('gym-admin.incomes.create-edit', $this->data);
    }

    public function store(StoreUpdateRequest $request)
    {
        $inputData = $request->all();
        $inputData['detail_id'] = $this->data['user']->detail_id;
        $inputData['category_id'] = $request->get('income_category');
        $inputData['purchase_date'] = Carbon::createFromFormat('m/d/Y', $request->get('purchase_date'))->format('Y-m-d');
        $inputData['supplier_id'] = $request->get('supplier_id');
        $gymIncome['payment_source'] = $request->get('payment_source') ?? null;

        $income = Income::create($inputData);
        if($request->hasFile('bill')) {
            $this->uploadBill($request->bill, $income->uuid);
        }
        return Reply::redirect(route('gym-admin.incomes.index'), "Income added successfully");
    }

    public function show($id)
    {
        if (!$this->data['user']->can("expense")) {
            return App::abort(401);
        }

        $this->data['income'] = Income::findByUid($id);
        $this->data['title'] = "Income Update";
        $this->data['categories'] = IncomeCategory::whereNull('detail_id')
            ->orWhere(function($query){
                $query->where('detail_id',$this->data['user']->detail_id);
            })->latest()->get();
        $this->data['paymentSources'] = listPaymentType() ;
        $this->data['suppliers'] = GymSupplier::where('branch_id',$this->data['user']->detail_id)->get();
        return view('gym-admin.incomes.create-edit', $this->data);
    }

    public function update(StoreUpdateRequest $request, $id)
    {
        $gymIncome = Income::findByUid($id);
        $gymIncome->category_id = $request->get('income_category');
        $gymIncome->price = $request->get('price');
        $gymIncome->remarks = $request->get('remarks');
        $gymIncome->purchase_date = Carbon::createFromFormat('m/d/Y', $request->get('purchase_date'))->format('Y-m-d');
        $gymIncome->supplier_id = $request->get('supplier_id');
        $gymIncome->payment_source = $request->get('payment_source') ?? null;

        if($request->hasFile('bill')) {
            $this->uploadBill($request->bill, $id);
        }
        $gymIncome->save();
        return Reply::redirect(route('gym-admin.incomes.index'), "Income updated successfully");
    }

    public function destroy($id)
    {
        if (!$this->data['user']->can("expense")) {
            return App::abort(401);
        }

        $income = Income::findByUid($id);
        $filepath = public_path('/uploads/income_bill/'.$income->bill);
        if($income->bill != null && file_exists($filepath)){
            unlink($filepath);
        }
        $income->delete();
        return Reply::redirect(route('gym-admin.incomes.index'), "Income removed successfully");
    }

    public function ajax_create()
    {
        if (!$this->data['user']->can("expense")) {
            return App::abort(401);
        }

        $income = Income::with('category')->where('detail_id',$this->data['user']->detail_id);
        return Datatables::of($income)
            ->addColumn('action', function ($row) {
                $action =  '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a href="' . route('gym-admin.incomes.show', $row->uuid) . '"> <i class="fa fa-edit"></i>Edit</a>
                        </li>';
                if($row->bill != null){
                    $action .= '<li>
                            <a href="' . route('gym-admin.incomes.download', $row->uuid) . '"> <i class="fa fa-download"></i>Download Bill</a>
                        </li>';
                }
                $action .= '<li>
                            <a href="javascript:;" class="delete-button" data-income-id="'.$row->uuid.'"> <i class="fa fa-trash"></i> Delete</a>
                        </li>
                    </ul>
                </div>';

                return $action;
            })
            ->addColumn('category_id', function ($row) {
                return $row->category->title ?? null;
            })
            ->addColumn('supplier_id', function ($row) {
                return $row->supplier->name ?? '---';
            })
            ->editColumn('remarks', function ($row) {
                return $row->remarks;
            })
            ->editColumn('payment_source',function($row){
                return !is_null($row->payment_source) ? getPaymentType($row->payment_source) : '';
            })
            ->editColumn('purchase_date', function ($row) {
                return $row->purchase_date->toFormattedDateString();
            })
            ->editColumn('price', function ($row) {
                return $this->data['gymSettings']->currency->acronym.' '.round($row->price, 2);
            })
            ->rawColumns(['action','supplier_id','purchase_date','category_id','payment_source'])
            ->make();
    }

    public function remove($id)
    {
        $this->data['income'] = Income::findByUid($id);
        return view('gym-admin.incomes.destroy', $this->data);
    }

    public function uploadBill($file, $id)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = $id . "-" . rand(10000, 99999) . "." . $extension;
        if($this->data['gymSettings']->local_storage == 1) {
            if(!file_exists(public_path()."/uploads/income_bill/")) {
                File::makeDirectory(public_path()."/uploads/income_bill/", $mode = 0777, true, true);
            }
            $destinationPath = public_path()."/uploads/income_bill/$filename";
            Image::make($file->getRealPath())->save($destinationPath);

        } else {
            $destinationPath = "/uploads/income_bill/$filename";
            $this->uploadImageS3($file, $destinationPath);
        }
        $gymIncome = Income::findByUid($id);
        $gymIncome->bill = $filename;
        $gymIncome->save();
    }

    public function download($id){
        $gymIncome = Income::findByUid($id);
        $filepath = public_path('uploads/income_bill/'.$gymIncome->bill);
        return response()->download($filepath);
    }

    public function addIncomeCategory(Request $request){
        $category = IncomeCategory::create([
            'title' => $request->get('title'),
            'detail_id' => $this->data['user']->detail_id,
        ]);
        return Reply::success("Income Category created successfully");
    }

    public function updateIncomeCategory(Request $request,$id){
        $category = IncomeCategory::findByUid($id);
        $category->update([
            'title' => $request->get('title')
        ]);
        return Reply::success("Income Category updated successfully");
    }
}
