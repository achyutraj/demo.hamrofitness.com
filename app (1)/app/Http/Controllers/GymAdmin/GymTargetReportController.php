<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\TargetReportExport;
use App\Models\GymMembershipPayment;
use App\Models\GymPurchase;
use App\Models\Product;
use App\Models\SetTarget;
use App\Models\ProductPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use DataTables;
use Excel;

class GymTargetReportController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['reportMenu'] = 'active';
        $this->data['targetreportMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_target_report")) {
            return App::abort(401);
        }

        $this->data['title'] = "Target Reports";
        $this->data['targets'] = SetTarget::where('detail_id', '=', $this->data['user']->detail_id)->get();

        return View::make('gym-admin.reports.target.index', $this->data);
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), ['target' => 'required']);

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        }

        $target = SetTarget::find(request()->get('target'));
        $information = [];
        if ($target->targetType->type == 'subscription') {

            $date = Carbon::createFromFormat('Y-m-d', $target->date);
            $start_date = Carbon::createFromFormat('Y-m-d', $target->start_date);

            $users = GymPurchase::whereBetween('purchase_date', [$start_date->format('Y-m-d'), $date->format('Y-m-d')])
                ->where('detail_id', '=', $this->data['user']->detail_id)->count();
            $report = "Memberships";

            $target_remaining = ($target->value - $users);
            $target_achieve_percent = ($users / $target->value) * 100;

            $information = [
                'target_achieved' => $users,
                'target' => $target->value,
                'target_id' => $target->id,
                'target_remaining' => $target_remaining,
                'percent' => $target_achieve_percent,
                'report' => $report
            ];

        } else if ($target->targetType->type == 'revenue') {

            $report = 'Revenue';

            $date = Carbon::createFromFormat('Y-m-d', $target->date);
            $start_date = Carbon::createFromFormat('Y-m-d', $target->start_date);

            $membership_sales = GymMembershipPayment::leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'purchase_id')
                ->whereBetween('payment_date', [$start_date->format('Y-m-d'), $date->format('Y-m-d')])
                ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id)->sum('payment_amount');

            $product_sales = ProductPayment::whereBetween('payment_date', [$start_date->format('Y-m-d'), $date->format('Y-m-d')])
                ->where('branch_id', $this->data['user']->detail_id)
                ->sum('payment_amount');

            $sales = $membership_sales + $product_sales;

            $target_remaining = ($target->value - $sales);
            $target_achieve_percent = round(($sales / $target->value) * 100);

            $information = [
                'target_achieved' => $sales,
                'target' => $target->value,
                'target_id' => $target->id,
                'target_remaining' => $target_remaining,
                'percent' => $target_achieve_percent,
                'report' => $report
            ];
        }
        return Reply::successWithData('Report fetched', ['data' => $information]);
    }

    public function ajax_create($id, $type)
    {
        $target = SetTarget::find($id);
        $date = Carbon::createFromFormat('Y-m-d', $target->date);
        $start_date = Carbon::createFromFormat('Y-m-d', $target->start_date);
        if ($type == 'membership') {
            if ($target->targetType->type == 'subscription') {

                $users = GymPurchase::select('gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'gym_memberships.title', 'gym_client_purchases.amount_to_be_paid', 'purchase_date')
                    ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
                    ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
                    ->whereBetween('purchase_date', [$start_date->format('Y-m-d'), $date->format('Y-m-d')])
                    ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id);

                return Datatables::of($users)
                    ->editColumn('gym_clients.first_name', function ($row) {
                        return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                    })
                    ->editColumn('purchase_date', function ($row) {
                        return $row->purchase_date->toFormattedDateString();
                    })
                   ->rawColumns(['gym_clients.first_name','purchase_date'])
                    ->make();
            }

            if ($target->targetType->type == 'revenue') {
                $sales = GymMembershipPayment::select('first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'gym_memberships.title', 'payment_amount', 'payment_date')
                    ->leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'purchase_id')
                    ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
                    ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id);
                return Datatables::of($sales)
                    ->editColumn('gym_clients.first_name', function ($row) {
                        return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                    })
                    ->editColumn('payment_amount', function ($row) {
                        return $this->data['gymSettings']->currency->acronym.' '.$row->payment_amount;
                    })
                    ->editColumn('gym_memberships.title', function ($row) {
                        return $row->title;
                    })
                    ->editColumn('payment_date', function ($row) {
                        return $row->payment_date->toFormattedDateString();
                    })
                    ->rawColumns(['gym_clients.first_name','payment_date'])
                    ->make();

            }
        }

        if ($type == 'product') {
            $product_sales = ProductPayment::select('gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'product_sales.product_name', 'product_sales.product_quantity', 'payment_amount', 'payment_date')
                ->leftJoin('product_sales', 'product_sales.id', 'product_payments.product_sale_id')
                ->leftJoin('gym_clients', 'gym_clients.id', 'product_payments.user_id')
                ->where('product_payments.branch_id', '=', $this->data['user']->detail_id);
            return Datatables::of($product_sales)
                ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                ->editColumn('payment_amount', function ($row) {
                    return $this->data['gymSettings']->currency->acronym.' '.$row->payment_amount;
                })
                ->editColumn('payment_date', function ($row) {
                    return $row->payment_date->toFormattedDateString();
                })
                ->editColumn('product_name', function ($row) {
                    $decoded_product = json_decode($row->product_name,true);
                    $decoded_quantity = json_decode($row->product_quantity,true);
                    $product = '';
                    for ($i = 0; $i < count($decoded_product); $i++) {
                        $pro = Product::find($decoded_product[$i]);
                        $product .= $pro->name . ', Qty: ' . $decoded_quantity[$i] . '<br> ';
                    }
                    return $product;
                })
                ->rawColumns(['gym_clients.first_name','payment_date','product_name'])
                ->make();
        }
    }

    public function downloadTargetData($id, $type)
    {
        $target = SetTarget::find($id);
        $heading = $target->title;
        $endDate = $target->date;
        $startDate = $target->start_date;
        if ($type == 'membership') {
            $sales = GymMembershipPayment::leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')
                ->leftJoin('set_targets', 'set_targets.detail_id', '=', 'gym_client_purchases.detail_id')
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
                ->where('set_targets.id', $id)
                ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id)->get();
        } else{
            $sales = ProductPayment::select('gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'product_sales.product_name', 'product_sales.product_quantity', 'payment_amount', 'payment_date')
                ->leftJoin('product_sales', 'product_sales.id', 'product_payments.product_sale_id')
                ->leftJoin('gym_clients', 'gym_clients.id', 'product_payments.user_id')
                ->where('product_payments.branch_id', '=', $this->data['user']->detail_id)->get();
        }
        $pdf = PDF::loadView('pdf.target', compact(['type', 'sales', 'heading', 'startDate', 'endDate']));
        return $pdf->download('targetReport.pdf');
    }

    public function downloadExcelTargetData($id, $type)
    {
        $target = SetTarget::find($id);
        $user = $this->data['user']->detail_id;
        return Excel::download(new TargetReportExport($id,$type,$user),'targetReport.xls');
    }
}
