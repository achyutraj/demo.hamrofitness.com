<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\ProductSellReportExport;
use App\Models\Product;
use App\Models\ProductSales;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use DataTables;
use PDF;
use Excel;

class ProductSellReportController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['reportMenu'] = 'active';
        $this->data['productsellreportMenu'] = 'active';
    }
    public function index()
    {
        if(!$this->data['user']->can("product_report"))
        {
            return App::abort(401);
        }
        $this->data['title'] = 'Product Sell Report';
        return View::make('gym-admin.reports.product_sell.index',$this->data);
    }

    public function store()
    {
        $validator  = Validator::make(request()->all(),['date_range'=>'required']);

        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $date_range = explode('-',request()->get('date_range'));

            $date_range_start = Carbon::createFromFormat('M d, Y',trim($date_range[0]));
            $date_range_end   = Carbon::createFromFormat('M d, Y',trim($date_range[1]));

            $productSale = ProductSales::whereBetween(DB::raw('DATE(created_at)'),[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                ->where('branch_id','=',$this->data['user']->detail_id)
                ->sum('total_amount');
            $heading = 'Product Sale';
            $data = [
                'total'             => $productSale,
                'start_date'        => $date_range_start->format('Y-m-d'),
                'end_date'          => $date_range_end->format('Y-m-d'),
                'report'            => $heading
            ];
            return Reply::successWithData('Reports Fetched',$data);
        }
    }

    public function ajax_create($start_date,$end_date)
    {
        $data = ProductSales::select('customer_name','customer_type','product_name','created_at','product_amount')
        ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
            ->where('branch_id', '=', $this->data['user']->detail_id);

        return Datatables::of($data)
            ->editColumn('customer_name',function($row){
                return $row->customer_name;
            })
            ->editColumn('customer_type',function($row){
                return $row->customer_type;
            })
            ->editColumn('created_at',function($row){
                return $row->created_at->toFormattedDateString();
            })
            ->editColumn('product_name',function($row){
                $data = '';
                $arr['product_name'] = json_decode($row->product_name,true);
                for($i=0; $i < count( $arr['product_name']) ;$i++){
                    $pro = Product::find($arr['product_name'][$i]);
                    if($i == 0){
                        $data = $pro->name;
                    }else{
                        $data = $data.', '.$pro->name;
                    }
                }
                return $data;
            })
            ->editColumn('product_amount',function($row){
                $d = json_decode($row->product_amount,true);
                $total = array_sum($d);
                return $this->data['gymSettings']->currency->acronym.' '.$total;
            })
            ->rawColumns(['product_name','product_amount'])
            ->make();

    }
    public function downloadProductSaleReport($sd,$ed){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);
        $data = ProductSales::whereBetween(DB::raw('DATE(created_at)'), [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->where('branch_id', '=', $this->data['user']->detail_id)->get();
        $pdf = PDF::loadView('pdf.productsaleReport',compact(['data','sd','ed']));
        return $pdf->download('productsaleReport.pdf');
    }
    public function downloadExcelProductSaleReport($sd,$ed)
    {
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $user = $this->data['user']->detail_id;
        return Excel::download(new ProductSellReportExport($sDate,$eDate,$user),'productsaleReport.xls');
    }
}
