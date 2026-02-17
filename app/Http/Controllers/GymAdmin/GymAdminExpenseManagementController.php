<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Requests\GymAdmin\GymExpense\StoreUpdateRequest;
use App\Models\ExpenseCategory;
use App\Models\GymExpense;
use App\Models\GymSupplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use DataTables;

class GymAdminExpenseManagementController extends GymAdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->data['paymentMenu'] = 'active';
        $this->data['expenseMenu'] = 'active';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->data['user']->can("expense")) {
            return App::abort(401);
        }

        $this->data['title'] = 'GymExpense Manager';
        return view('gym-admin.expense.index', $this->data);
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

        $this->data['title'] = "GymExpense Create";
        $this->data['categories'] = ExpenseCategory::whereNull('detail_id')
                                    ->orWhere(function($query){
                                        $query->where('detail_id',$this->data['user']->detail_id);
                                    })->latest()->get();
        $this->data['suppliers'] = GymSupplier::where('branch_id',$this->data['user']->detail_id)->get();
        $this->data['paymentSources'] = listPaymentType() ;
        return view('gym-admin.expense.create-edit', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requestcompact
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateRequest $request)
    {
        $inputData = $request->all();
        $inputData['detail_id'] = $this->data['user']->detail_id;
        $inputData['category_id'] = $request->get('expense_category');
        $inputData['item_name'] = $request->get('item_name');
        $inputData['price'] = $request->get('price');
        $inputData['remarks'] = $request->get('remarks');
        $inputData['purchase_date'] = Carbon::createFromFormat('m/d/Y', $request->get('purchase_date'))->format('Y-m-d');
        $inputData['supplier_id'] = $request->get('supplier_id');
        $inputData['payment_status'] = $request->get('payment_status');
        $inputData['payment_source'] = $request->get('payment_status') == 'paid' ? $request->get('payment_source') : null;
        $gymExpense = GymExpense::create($inputData);

        if($request->hasFile('bill')) {
            $this->uploadBill($request->bill, $gymExpense->uuid);
        }
        return Reply::redirect(route('gym-admin.expense.index'), "GymExpense created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$this->data['user']->can("expense")) {
            return App::abort(401);
        }

        $this->data['expense'] = GymExpense::findByUid($id);
        $this->data['title'] = "GymExpense Update";
        $this->data['categories'] = ExpenseCategory::whereNull('detail_id')
            ->orWhere(function($query){
                $query->where('detail_id',$this->data['user']->detail_id);
            })->latest()->get();
        $this->data['suppliers'] = GymSupplier::where('branch_id',$this->data['user']->detail_id)->get();
        $this->data['paymentSources'] = listPaymentType() ;
        return View::make('gym-admin.expense.create-edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateRequest $request, $id)
    {
        $gymExpense = GymExpense::findByUid($id);
        $gymExpense->category_id = $request->get('expense_category');
        $gymExpense->item_name = $request->get('item_name');
        $gymExpense->purchase_date = Carbon::createFromFormat('m/d/Y', $request->get('purchase_date'))->format('Y-m-d');
        $gymExpense->supplier_id = $request->get('supplier_id');
        $gymExpense->price = $request->get('price');
        $gymExpense->remarks = $request->get('remarks');
        $gymExpense->payment_status = $request->get('payment_status');
        $gymExpense->payment_source = $request->get('payment_status') == 'paid' ? $request->get('payment_source') : null;

        if($request->hasFile('bill')) {
            $this->uploadBill($request->bill, $id);
        }

        $gymExpense->save();

        return Reply::redirect(route('gym-admin.expense.index'), "GymExpense updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$this->data['user']->can("expense")) {
            return App::abort(401);
        }

        $expense = GymExpense::findByUid($id);
        $filepath = public_path('/uploads/bill/'.$expense->bill);
        if($expense->bill != null && file_exists($filepath)){
            unlink($filepath);
        }
        $expense->delete();
        return Reply::redirect(route('gym-admin.expense.index'), "GymExpense removed successfully");
    }

    public function getExpense()
    {
        if (!$this->data['user']->can("expense")) {
            return App::abort(401);
        }

        $expense = GymExpense::with('category')->where('detail_id',$this->data['user']->detail_id);
        return Datatables::of($expense)
            ->addColumn('action', function ($row) {
            $action =  '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a href="' . route('gym-admin.expense.show', $row->uuid) . '"> <i class="fa fa-edit"></i>Edit</a>
                        </li>';
                if($row->bill != null){
                    $action .= '<li>
                            <a href="' . route('gym-admin.expense.download', $row->uuid) . '"> <i class="fa fa-download"></i>Download Bill</a>
                        </li>';
                }
                $action .= '<li>
                            <a href="javascript:;" class="delete-button" data-expense-id="'.$row->uuid.'"> <i class="fa fa-trash"></i> Delete</a>
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
            ->editColumn('purchase_date', function ($row) {
                return Carbon::createFromFormat('Y-m-d', $row->purchase_date)->toFormattedDateString();
            })
            ->editColumn('price', function ($row) {
                return $this->data['gymSettings']->currency->acronym.' '.round($row->price, 2);
             })
             ->editColumn('payment_status', function ($row) {
                return $row->payment_status == 'paid' ? '<span class="label label-success">Paid</span>' : '<span class="label label-danger">Unpaid</span>';
            })
            ->rawColumns(['action','purchase_date','category_id','supplier_id','payment_status'])
            ->make();
    }

    public function removeExpense($id)
    {
        $this->data['expense'] = GymExpense::findByUid($id);
        return View::make('gym-admin.expense.destroy', $this->data);
    }

    public function uploadBill($file, $id)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = $id . "-" . rand(10000, 99999) . "." . $extension;
        if($this->data['gymSettings']->local_storage == 1) {
            if(!file_exists(public_path()."/uploads/bill/")) {
                File::makeDirectory(public_path()."/uploads/bill/", $mode = 0777, true, true);
            }
            $destinationPath = public_path()."/uploads/bill/$filename";
            Image::make($file->getRealPath())->save($destinationPath);

        } else {
            $destinationPath = "/uploads/bill/$filename";
            $this->uploadImageS3($file, $destinationPath);
        }
        $gymExpense = GymExpense::findByUid($id);
        $gymExpense->bill = $filename;
        $gymExpense->save();
    }

    public function uploadImageS3($imageMake, $filePath) {

        if (get_class($imageMake) === 'Intervention\Image\Image') {
            Storage::put($filePath, $imageMake->stream()->__toString(), 'public');
        } else {
            Storage::put($filePath, fopen($imageMake, 'r'), 'public');
        }
    }

    public function downloadExpense($id){
        $gymExpense = GymExpense::findByUid($id);
        $filepath = public_path('uploads/bill/'.$gymExpense->bill);
        return response()->download($filepath);
    }

    public function addExpenseCategory(Request $request){
        $category = ExpenseCategory::create([
            'title' => $request->get('title'),
            'detail_id' => $this->data['user']->detail_id,
        ]);
        return Reply::success("GymExpense Category created successfully");
    }

    public function updateExpenseCategory(Request $request,$id){
        $category = ExpenseCategory::findByUid($id);
        $category->update([
            'title' => $request->get('title')
        ]);
        return Reply::success("GymExpense Category updated successfully");

    }
}
