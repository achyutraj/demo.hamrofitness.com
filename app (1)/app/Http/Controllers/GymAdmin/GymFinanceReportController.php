<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\FinanceReportExport;
use App\Models\GymMembershipPayment;
use App\Models\GymPurchase;
use App\Models\LockerPayment;
use App\Models\LockerReservation;
use App\Models\ProductPayment;
use App\Models\ProductSales;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Excel;
use DataTables;

class GymFinanceReportController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['reportMenu'] = 'active';
        $this->data['financialreportMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_finance_report")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Finance Report';
        $this->data['paymentSources'] = listPaymentType();
        return View::make('gym-admin.reports.finance.index', $this->data);
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), ['type' => 'required', 'date_range' => 'required']);
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $choice = request()->get('type');
            $payment_source = request()->get('payment_source') ?? null;
            $date_range = explode('-', request()->get('date_range'));
            $date_range_start = Carbon::createFromFormat('M d, Y', trim($date_range[0]));
            $date_range_end = Carbon::createFromFormat('M d, Y', trim($date_range[1]));
            $payment = 0;
            if ($choice == 'all') {
                $query = GymMembershipPayment::select(
                    'gym_membership_payments.payment_amount')
                    ->leftJoin('gym_client_purchases', function ($join) {
                        $join->on('gym_membership_payments.purchase_id', '=', 'gym_client_purchases.id')
                            ->whereNotNull('gym_membership_payments.purchase_id');
                    })
                    ->whereBetween('payment_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                    ->where('gym_membership_payments.detail_id', $this->data['user']->detail_id);
                if($payment_source !== 'all'){
                    $query->where('gym_membership_payments.payment_source', $payment_source);
                }
                $payment = $query->sum('payment_amount');
            } elseif ($choice == 'allProduct') {
                $query = ProductPayment::select(
                    'product_payments.payment_amount')
                    ->leftJoin('product_sales', function ($join) {
                        $join->on('product_payments.product_sale_id', '=', 'product_sales.id')
                            ->whereNotNull('product_payments.product_sale_id');
                    })
                    ->whereBetween('payment_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                    ->where('product_payments.branch_id', '=', $this->data['user']->detail_id);
                    if($payment_source !== 'all'){
                        $query->where('product_payments.payment_source', $payment_source);
                    }
                   $payment = $query->sum('payment_amount');
            } elseif ($choice == 'dueProducts') {
                $total_payment = ProductSales::select(
                    'product_sales.id', 'total_amount', 'paid_amount', 'next_payment_date')
                    ->whereBetween('next_payment_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                    ->where('product_sales.payment_required', '=', 'yes')
                    ->where('product_sales.branch_id', '=', $this->data['user']->detail_id)
                    ->sum('product_sales.total_amount');

                $paid_payment = ProductSales::select(
                    'product_sales.id', 'total_amount', 'paid_amount', 'next_payment_date')
                    ->whereBetween('next_payment_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                    ->where('product_sales.payment_required', '=', 'yes')
                    ->where('product_sales.branch_id', '=', $this->data['user']->detail_id)
                    ->sum('product_sales.paid_amount');
                $payment = $total_payment - $paid_payment;

            } else if ($choice == 'lockerPayments') {
                $query = LockerPayment::select(
                    'locker_payments.payment_amount')
                    ->leftJoin('locker_reservations', function ($join) {
                        $join->on('locker_payments.reservation_id', '=', 'locker_reservations.id')
                            ->whereNotNull('locker_payments.reservation_id');
                    })
                    ->whereBetween('payment_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                    ->where('locker_payments.detail_id', $this->data['user']->detail_id);
                if($payment_source !== 'all'){
                    $query->where('locker_payments.payment_source', $payment_source);
                }
                $payment = $query->sum('payment_amount');
            }else if($choice == 'lockerDues'){
                $total_payment = LockerReservation::select(
                    'locker_reservations.id',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'amount_to_be_paid',
                    'paid_amount',
                    'locker_reservations.next_payment_date'
                )->leftJoin('lockers', function ($join) {
                    $join->on('lockers.id', '=', 'locker_reservations.locker_id')
                        ->whereNotNull('locker_reservations.locker_id');
                })
                    ->whereBetween('next_payment_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                    ->where('locker_reservations.detail_id', '=', $this->data['user']->detail_id)
                    ->sum('amount_to_be_paid');

                $paid_payment = LockerReservation::select(
                    'locker_reservations.id',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'amount_to_be_paid',
                    'paid_amount',
                    'locker_reservations.next_payment_date'
                )->leftJoin('lockers', function ($join) {
                    $join->on('lockers.id', '=', 'locker_reservations.locker_id')
                        ->whereNotNull('locker_reservations.locker_id');
                })
                    ->whereBetween('next_payment_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                    ->where('locker_reservations.detail_id', '=', $this->data['user']->detail_id)
                    ->sum('paid_amount');
                $payment = $total_payment - $paid_payment;
            }else {
                $total_payment = GymPurchase::select(
                    'gym_client_purchases.id',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'amount_to_be_paid',
                    'paid_amount',
                    'gym_client_purchases.next_payment_date'
                )->leftJoin('gym_memberships', function ($join) {
                    $join->on('gym_memberships.id', '=', 'gym_client_purchases.membership_id')
                        ->whereNotNull('gym_client_purchases.membership_id');
                })
                    ->whereBetween('next_payment_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                    ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id)
                    ->sum('amount_to_be_paid');

                $paid_payment = GymPurchase::select(
                    'gym_client_purchases.id',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'amount_to_be_paid',
                    'paid_amount',
                    'gym_client_purchases.next_payment_date'
                )->leftJoin('gym_memberships', function ($join) {
                    $join->on('gym_memberships.id', '=', 'gym_client_purchases.membership_id')
                        ->whereNotNull('gym_client_purchases.membership_id');
                })
                    ->whereBetween('next_payment_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                    ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id)
                    ->sum('paid_amount');
                $payment = $total_payment - $paid_payment;
            }
            $data = [
                'total' => $payment,
                'start_date' => $date_range_start->format('Y-m-d'),
                'end_date' => $date_range_end->format('Y-m-d'),
                'type' => $choice,
                'paymentType' => $payment_source,
                'report' => 'Finance'
            ];
            return Reply::successWithData('Reports Fetched', $data);
        }
    }

    public function ajax_create($type, $start_date, $end_date, $payment_source)
    {
        if ($type == 'all') {
            $query = GymMembershipPayment::select(
                'gym_clients.first_name',
                'gym_clients.middle_name',
                'gym_clients.last_name',
                'payment_amount',
                'payment_source',
                'payment_date', 'gym_membership_payments.remarks as remarks')
                ->leftJoin('gym_client_purchases', function ($join) {
                    $join->on('gym_membership_payments.purchase_id', '=', 'gym_client_purchases.id')
                        ->whereNotNull('gym_membership_payments.purchase_id');
                })
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_membership_payments.user_id')
                ->where('gym_membership_payments.detail_id', $this->data['user']->detail_id);

                if($payment_source !== 'all'){
                    $query->where('gym_membership_payments.payment_source', $payment_source);
                }
                $payment = $query->whereBetween('payment_date', [$start_date, $end_date]);
            return Datatables::of($payment)
                ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                ->editColumn('payment_amount', function ($row) {
                    return $this->data['gymSettings']->currency->acronym.' '.$row->payment_amount;
                })
                ->editColumn('payment_source', function ($row) {
                    return getPaymentType($row->payment_source);
                })
                ->editColumn('payment_date', function ($row) {
                    return $row->payment_date->toFormattedDateString();
                })
                ->editColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->rawColumns(['gym_clients.first_name', 'payment_source'])
                ->make();
        } else if ($type == 'allProduct') {
            $query = ProductPayment::select(
                'gym_clients.first_name',
                'gym_clients.middle_name',
                'gym_clients.last_name',
                'payment_amount',
                'payment_source',
                'payment_date', 'product_payments.remarks as remarks')
                ->leftJoin('product_sales', function ($join) {
                    $join->on('product_payments.product_sale_id', '=', 'product_sales.id')
                        ->whereNotNull('product_payments.product_sale_id');
                })
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_payments.user_id')
                ->where('product_payments.branch_id', $this->data['user']->detail_id);
                if($payment_source !== 'all'){
                    $query->where('product_payments.payment_source', $payment_source);
                }
                $payment = $query->whereBetween('payment_date', [$start_date, $end_date]);
            return Datatables::of($payment)
                ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                ->editColumn('payment_amount', function ($row) {
                    return $this->data['gymSettings']->currency->acronym.' '.$row->payment_amount;
                })
                ->editColumn('payment_source', function ($row) {
                    return getPaymentType($row->payment_source);
                })
                ->editColumn('payment_date', function ($row) {
                    return $row->payment_date->toFormattedDateString();
                })
                ->editColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->rawColumns(['gym_clients.first_name', 'payment_source'])
                ->make();

        } else if ($type == 'dueProducts') {
            $payment = ProductSales::select(
                'product_sales.id',
                'gym_clients.first_name',
                'gym_clients.middle_name',
                'gym_clients.last_name',
                'product_sales.customer_type as remarks',
                'total_amount as payment_source',
                'paid_amount as payment_amount',
                'product_sales.next_payment_date as payment_date'
            )->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_sales.client_id')
                ->whereBetween('next_payment_date', [$start_date, $end_date])
                ->where('product_sales.payment_required', '=', 'yes')
                ->where('product_sales.branch_id', $this->data['user']->detail_id);
            return Datatables::of($payment)
                ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                ->editColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->editColumn('payment_source', function ($row) {
                    return $this->data['gymSettings']->currency->acronym.' '.($row->payment_source - $row->payment_amount);
                })
                ->editColumn('payment_date', function ($row) {
                    return( date('M d, Y',strtotime($row->payment_date)));
                })
                ->editColumn('payment_amount', function ($row) {
                    return $this->data['gymSettings']->currency->acronym.' '.$row->payment_amount;
                })
                ->rawColumns(['gym_clients.first_name', 'total_amount'])
                ->make();

        } else if ($type == 'lockerPayments') {
            $query = LockerPayment::select(
                'gym_clients.first_name',
                'gym_clients.middle_name',
                'gym_clients.last_name',
                'payment_amount',
                'payment_source',
                'payment_date', 'locker_payments.remarks as remarks')
                ->leftJoin('locker_reservations', function ($join) {
                    $join->on('locker_payments.reservation_id', '=', 'locker_reservations.id')
                        ->whereNotNull('locker_payments.reservation_id');
                })
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'locker_payments.client_id')
                ->where('locker_payments.detail_id', $this->data['user']->detail_id);
                if($payment_source !== 'all'){
                    $query->where('locker_payments.payment_source', $payment_source);
                }
                $payment = $query->whereBetween('payment_date', [$start_date, $end_date])->get();
            return Datatables::of($payment)
                ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                ->editColumn('payment_amount', function ($row) {
                    return $this->data['gymSettings']->currency->acronym.' '.$row->payment_amount;
                })
                ->editColumn('payment_source', function ($row) {
                    return getPaymentType($row->payment_source);
                })
                ->editColumn('payment_date', function ($row) {
                    return $row->payment_date->toFormattedDateString();
                })
                ->editColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->rawColumns(['gym_clients.first_name', 'payment_source'])
                ->make();

        } else if ($type == 'lockerDues') {
            $payment = LockerReservation::select(
                'locker_reservations.id',
                'gym_clients.first_name',
                'gym_clients.middle_name',
                'gym_clients.last_name',
                'amount_to_be_paid as payment_source',
                'paid_amount as payment_amount',
                'next_payment_date as payment_date',
                'locker_reservations.remarks as remarks'
            )->leftJoin('gym_clients', 'gym_clients.id', '=', 'locker_reservations.client_id')
                ->whereBetween('next_payment_date', [$start_date, $end_date])
                 ->where('locker_reservations.payment_required', '=', 'yes')
                ->where('locker_reservations.detail_id', $this->data['user']->detail_id)->get();
            return Datatables::of($payment)
                ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                ->editColumn('payment_source', function ($row) {
                    return $this->data['gymSettings']->currency->acronym.' '.($row->payment_source - $row->payment_amount);
                })
                ->editColumn('payment_date', function ($row) {
                    return( date('M d, Y',strtotime($row->payment_date)));
                })
                ->editColumn('payment_amount', function ($row) {
                    return $this->data['gymSettings']->currency->acronym.' '.$row->payment_amount;
                })
                ->editColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->rawColumns(['gym_clients.first_name', 'payment_source', 'payment_date'])
                ->make();

        } else {
            $payment = GymPurchase::select(
                'gym_client_purchases.id',
                'gym_clients.first_name',
                'gym_clients.middle_name',
                'gym_clients.last_name',
                'amount_to_be_paid as payment_source',
                'paid_amount as payment_amount',
                'next_payment_date as payment_date',
                'gym_client_purchases.remarks as remarks'
            )->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                ->whereBetween('next_payment_date', [$start_date, $end_date])
                 ->where('gym_client_purchases.payment_required', '=', 'yes')
                ->where('gym_client_purchases.detail_id', $this->data['user']->detail_id);
            return Datatables::of($payment)
                ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                ->editColumn('payment_source', function ($row) {
                    return $this->data['gymSettings']->currency->acronym.' '.($row->payment_source - $row->payment_amount);
                })
                ->editColumn('payment_date', function ($row) {
                    return( date('M d, Y',strtotime($row->payment_date)));
                })
                ->editColumn('payment_amount', function ($row) {
                    return $this->data['gymSettings']->currency->acronym.' '.$row->payment_amount;
                })
                ->editColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->rawColumns(['gym_clients.first_name', 'payment_source', 'payment_date'])
                ->make();

        }
    }

    public function downloadFinanceReport($id, $sd, $ed,$payment_source)
    {   
        $payment = null;
        if ($id == 'all') {
            $query = GymMembershipPayment::select(
                'first_name',
                'middle_name',
                'last_name',
                'gym_membership_payments.payment_amount',
                'gym_membership_payments.payment_source',
                'gym_membership_payments.payment_date', 'gym_membership_payments.remarks')
                ->leftJoin('gym_client_purchases', function ($join) {
                    $join->on('gym_membership_payments.purchase_id', '=', 'gym_client_purchases.id')
                        ->whereNotNull('gym_membership_payments.purchase_id');
                })
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_membership_payments.user_id')
                ->where('gym_membership_payments.detail_id',$this->data['user']->detail_id);
                if($payment_source !== 'all'){
                    $query->where('gym_membership_payments.payment_source',$payment_source);
                }
                $payment= $query->whereBetween('payment_date', [$sd, $ed])->get();
        } else if ($id == 'allProduct') {
            $query = ProductPayment::select(
                'first_name',
                'middle_name',
                'last_name',
                'product_payments.payment_amount',
                'product_payments.payment_source',
                'product_payments.payment_date', 'product_payments.payment_date', 'product_payments.remarks')
                ->leftJoin('product_sales', function ($join) {
                    $join->on('product_payments.product_sale_id', '=', 'product_sales.id')
                        ->whereNotNull('product_payments.product_sale_id');
                })
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_payments.user_id')
                ->where('product_payments.branch_id',  $this->data['user']->detail_id);
                 if($payment_source !== 'all'){
                     $query->where('product_payments.payment_source',  $payment_source);
                 }
                $payment = $query->whereBetween('payment_date', [$sd, $ed])->get();
        } else if ($id == 'dueProducts') {
            $payment = ProductSales::select(
                'product_sales.id',
                'first_name',
                'middle_name',
                'last_name',
                'product_sales.customer_type',
                'total_amount as amount_to_be_paid',
                'paid_amount',
                'product_sales.next_payment_date'
            )
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_sales.client_id')
                ->whereBetween('next_payment_date', [$sd, $ed])
                ->where('payment_required', '=', 'yes')
                ->where('product_sales.branch_id', '=', $this->data['user']->detail_id)->get();
        }else if($id == 'lockerPayments') {
            $query = LockerPayment::select(
                'first_name',
                'middle_name',
                'last_name',
                'locker_payments.payment_amount',
                'locker_payments.payment_source',
                'locker_payments.payment_date', 'locker_payments.remarks')
                ->leftJoin('locker_reservations', function ($join) {
                    $join->on('locker_payments.reservation_id', '=', 'locker_reservations.id')
                        ->whereNotNull('locker_payments.reservation_id');
                })
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'locker_payments.client_id')
                ->where('locker_payments.detail_id',$this->data['user']->detail_id);
                if($payment_source !== 'all'){
                    $query->where('locker_payments.payment_source',$payment_source);
                }
                $payment= $query->whereBetween('payment_date', [$sd, $ed])->get();
        }else if($id == 'lockerDues') {
            $payment = LockerReservation::select(
                'locker_reservations.id',
                'first_name',
                'middle_name',
                'last_name',
                'amount_to_be_paid',
                'paid_amount',
                'next_payment_date', 'locker_reservations.remarks'
            )->leftJoin('gym_clients', 'gym_clients.id', '=', 'locker_reservations.client_id')
                ->whereBetween('next_payment_date', [$sd, $ed])
                 ->where('payment_required', '=', 'yes')
                ->where('locker_reservations.detail_id', $this->data['user']->detail_id)->get();
        } else {
            $payment = GymPurchase::select(
                'gym_client_purchases.id',
                'first_name',
                'middle_name',
                'last_name',
                'amount_to_be_paid',
                'paid_amount',
                'next_payment_date', 'gym_client_purchases.remarks'
            )->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                ->whereBetween('next_payment_date', [$sd, $ed])
                 ->where('payment_required', '=', 'yes')
                ->where('gym_client_purchases.detail_id', $this->data['user']->detail_id)->get();

        }
        $pdf = PDF::loadView('pdf.financeReport', compact(['payment', 'id', 'sd', 'ed']));
        return $pdf->download('financeReport.pdf');
    }

    public function downloadExcelFinanceReport($id, $sd, $ed,$payment_source)
    {
        $user = $this->data['user']->detail_id;
        return Excel::download(new FinanceReportExport($id, $sd, $ed, $user,$payment_source), 'financeReport.xls');

    }
}
