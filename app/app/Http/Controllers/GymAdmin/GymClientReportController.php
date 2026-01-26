<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\ClientReportExport;
use App\Models\GymClient;
use App\Models\GymPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Excel;
use PDF;
use DataTables;

class GymClientReportController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['reportMenu'] = 'active';
        $this->data['clientreportMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_client_report")) {
            return App::abort(401);
        }
        $this->data['title'] = 'Client Reports';
        return View::make('gym-admin.reports.client.index', $this->data);
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), ['client_type' => 'required', 'date_range' => 'required']);

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $choice = request()->get('client_type');
            $date_range = explode('-', request()->get('date_range'));

            $date_range_start = Carbon::createFromFormat('M d, Y', trim($date_range[0]));
            $date_range_end = Carbon::createFromFormat('M d, Y', trim($date_range[1]));
            $total = 0;
            $report = '';

            switch ($choice) {
                case 'new':
                    $total = GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                        ->whereBetween('gym_clients.joining_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                        ->where('gym_clients.is_client','yes')
                        ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)->count();

                    $report = 'New Clients';
                    break;
                case 'expire':
                    $expires = GymPurchase::whereBetween('expires_on', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                        ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                        ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id)
                        ->count();
                    $total = $expires;
                    $report = 'Expiring Clients';
                    break;
                case 'big_spenders':
                    $total = GymPurchase::whereBetween('purchase_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                        ->where('detail_id', '=', $this->data['user']->detail_id)
                        ->where('purchase_amount', '>=', 10000)->count();
                    $report = 'Big Clients';
                    break;
                case 'birthday':
                    if ($date_range_start->month > $date_range_end->month) {
                        $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_clients.id as clientID')
                            ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                            ->whereRaw("DAYOFYEAR(gym_clients.dob)>= '".$date_range_start->dayOfYear."' OR DAYOFYEAR(gym_clients.dob) <='".$date_range_end->dayOfYear."' AND business_customers.detail_id =".$this->data['user']->detail_id)
                            ->where('gym_clients.is_client','yes')->count();

                    } else {
                        $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_clients.id as clientID')
                            ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                            ->whereRaw("DAYOFYEAR(gym_clients.dob)>= '".$date_range_start->dayOfYear."' AND DAYOFYEAR(gym_clients.dob) <='".$date_range_end->dayOfYear."' AND business_customers.detail_id =".$this->data['user']->detail_id)
                            ->where('gym_clients.is_client','yes')->count();

                    }

                    $total = $clients;

                    $report = 'Clients BirthDays';
                    break;
                case 'lost':
                    $purchases = GymPurchase::whereBetween('purchase_date', [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
                        ->where('detail_id', '=', $this->data['user']->detail_id)
                        ->get();
                    $users = [];
                    foreach ($purchases as $purchase) {
                        if ($purchase->membership_id != null) {
                            $days = $purchase->start_date->diffInDays(Carbon::now('Asia/Kathmandu'));

                            if (($purchase->membership->duration * 30) < $days) {
                                array_push($users, $purchase->client_id);
                            }
                        }
                    }
                    $total = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_clients.id as clientID')
                        ->whereIn('id', $users)->count();

                    $report = 'Lost Clients';
                    break;
                case 'active':
                    $clientIds = GymPurchase::where('expires_on','>=',now())
                        ->where('detail_id', '=', $this->data['user']->detail_id)
                        ->groupBy('client_id','membership_id')
                        ->orderBy('expires_on','desc')
                        ->pluck('client_id');

                    $total = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_clients.id as clientID')
                        ->whereIn('id', $clientIds)->count();
                    $report = 'Active Clients';
                    break;
                case 'inactive':
                    $activeIds = GymPurchase::where('expires_on','>=',now())
                        ->where('detail_id', '=', $this->data['user']->detail_id)
                        ->groupBy('client_id','membership_id')
                        ->pluck('client_id');

                    $clientIds = GymPurchase::where('detail_id', $this->data['user']->detail_id)
                        ->whereNotIn('client_id',$activeIds)
                        ->groupBy('client_id','membership_id')
                        ->pluck('client_id');

                    $total = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.id as clientID')
                        ->whereIn('id', $clientIds)->count();
                    $report = 'InActive Clients';
                    break;
                case 'default':
                    $total = 0;
                    $report = 'Invalid Request';
            }

            $data = [
                'total'      => $total,
                'start_date' => $date_range_start->format('Y-m-d'),
                'end_date'   => $date_range_end->format('Y-m-d'),
                'type'       => $choice,
                'report'     => $report,
            ];

            return Reply::successWithData('Reports Fetched', $data);
        }
    }

    public function ajax_create($type, $start_date, $end_date)
    {
        $clients = null;
        $sDate = new Carbon($start_date);
        $eDate = new Carbon($end_date);
        switch ($type) {
            case 'new':
                $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','address','joining_date')
                    ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                    ->whereBetween('gym_clients.joining_date', [$sDate->format('Y-m-d'), $eDate->format('Y-m-d')])
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id', '=', $this->data['user']->detail_id);
                break;
            case 'expire':
                $clients = GymPurchase::select('gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'gym_clients.email',
                    'gym_clients.mobile', 'gym_clients.gender', 'gym_memberships.title', 'gym_client_purchases.start_date', 'gym_client_purchases.expires_on')
                    ->whereBetween('expires_on', [$sDate->format('Y-m-d'), $eDate->format('Y-m-d')])
                    ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
                    ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id);

                break;
            case 'big_spenders':
                $clients = GymPurchase::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','address','joining_date', 'gym_clients.id as clientID')
                    ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                    ->whereBetween('purchase_date', [$sDate->format('Y-m-d'), $eDate->format('Y-m-d')])
                    ->where('purchase_amount', '>=', 10000)
                    ->where('business_customers.detail_id', '=', $this->data['user']->detail_id);

                break;
            case 'birthday':
                $start = Carbon::createFromFormat('Y-m-d', $start_date);
                $end = Carbon::createFromFormat('Y-m-d', $end_date);
                if ($start->month > $end->month) {
                    $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address','gym_clients.dob as dob', DB::raw('DATE_FORMAT(dob, "%d %M, %Y")'), 'gym_clients.id as clientID')
                        ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                        ->whereRaw("DAYOFYEAR(gym_clients.dob)>= '".$start->dayOfYear."' OR DAYOFYEAR(gym_clients.dob) <='".$end->dayOfYear."' AND business_customers.detail_id =".$this->data['user']->detail_id)
                        ->where('gym_clients.is_client','yes')->get();

                } else {
                    $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.dob as dob',DB::raw('DATE_FORMAT(dob, "%d %M, %Y")'), 'gym_clients.id as clientID')
                        ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                        ->whereRaw("DAYOFYEAR(gym_clients.dob)>= '".$start->dayOfYear."' AND DAYOFYEAR(gym_clients.dob) <='".$end->dayOfYear."' AND business_customers.detail_id =".$this->data['user']->detail_id)
                        ->where('gym_clients.is_client','yes')->get();

                }
                break;
            case 'lost':
                $purchases = GymPurchase::whereBetween('purchase_date', [$sDate->format('Y-m-d'), $eDate->format('Y-m-d')])
                    ->where('detail_id', '=', $this->data['user']->detail_id)->get();
                $users = [];
                foreach ($purchases as $purchase) {
                    if ($purchase->membership_id != null) {
                        $days = $purchase->start_date->diffInDays(Carbon::now('Asia/Kathmandu'));
                        if (($purchase->membership->duration * 30) < $days) {
                            array_push($users, $purchase->client_id);
                        }
                    }
                }
                $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.id as clientID')
                    ->whereIn('id', $users);

                break;
            case 'active':
                    $clientIds = GymPurchase::where('expires_on','>=',now())
                        ->where('detail_id', '=', $this->data['user']->detail_id)
                        ->groupBy('client_id','membership_id')
                        ->orderBy('expires_on','desc')
                        ->pluck('client_id');

                    $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.id as clientID')
                        ->whereIn('id', $clientIds);
                    break;
            case 'inactive':
                    $activeIds = GymPurchase::where('expires_on','>=',now())
                        ->where('detail_id', '=', $this->data['user']->detail_id)
                        ->groupBy('client_id','membership_id')
                        ->pluck('client_id');

                    $clientIds = GymPurchase::where('detail_id', $this->data['user']->detail_id)
                        ->whereNotIn('client_id',$activeIds)
                        ->groupBy('client_id','membership_id')
                        ->pluck('client_id');

                    $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.id as clientID')
                        ->whereIn('id', $clientIds)->get();
                    break;

        }
        if ($type == 'birthday') {
            return Datatables::of($clients)
                ->editColumn('first_name', function ($row) {
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return '<i class="fa fa-envelope"></i> '.$row->email;
                })->editColumn('mobile', function ($row) {
                    return '<i class="fa fa-mobile"></i> '.$row->mobile;
                })->editColumn('gender', function ($row) {
                    if ($row->gender == 'female') {
                        return '<i class="fa fa-female"></i> Female';
                    } else {
                        return '<i class="fa fa-male"></i> Male';
                    }
                })
                ->editColumn('joining_date', function ($row) {
                    return $row->joining_date->toFormattedDateString();
                })
                ->editColumn('address', function ($row) {
                    return $row->address;
                })
                ->editColumn('dob', function ($row) {
                    return $row->dob->toFormattedDateString();
                })
                ->rawColumns(['email','mobile','gender','joining_date','address'])
                ->make();
        } else if ($type == 'expire') {
            return Datatables::of($clients)
                ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
                })
                ->editColumn('gym_clients.email', function ($row) {
                    return '<i class="fa fa-envelope"></i> '.$row->email;
                })->editColumn('gym_clients.mobile', function ($row) {
                    return '<i class="fa fa-mobile"></i> '.$row->mobile;
                })->editColumn('gym_clients.gender', function ($row) {
                    if ($row->gender == 'female') {
                        return '<i class="fa fa-female"></i> Female';
                    } else {
                        return '<i class="fa fa-male"></i> Male';
                    }
                })->editColumn('gym_memberships.title', function ($row) {
                    return $row->title;
                })->editColumn('start_date', function ($row) {
                    return $row->start_date->toFormattedDateString();
                })->editColumn('expires_on', function ($row) {
                    return $row->expires_on->toFormattedDateString();
                })
                ->rawColumns(['gym_clients.email','gym_clients.mobile','gym_clients.gender'])
                ->make();
        } else {
            return Datatables::of($clients)
                ->editColumn('first_name', function ($row) {
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
                })
                ->editColumn('joining_date', function ($row) {
                    return $row->joining_date->toFormattedDateString();
                })
                ->editColumn('address', function ($row) {
                    return $row->address;
                })
                ->editColumn('email', function ($row) {
                    return '<i class="fa fa-envelope"></i> '.$row->email;
                })->editColumn('mobile', function ($row) {
                    return '<i class="fa fa-mobile"></i> '.$row->mobile;
                })->editColumn('gender', function ($row) {
                    if ($row->gender == 'female') {
                        return '<i class="fa fa-female"></i> Female';
                    } else {
                        return '<i class="fa fa-male"></i> Male';
                    }
                })
                ->rawColumns(['email','mobile','gender','address','joining_date'])
                ->make(true);
        }

    }

    public function show($id)
    {
        $purchases = GymPurchase::where('client_id', '=', $id)->where('detail_id', '=', $this->data['user']->detail_id)->get();
        $memberships = [];
        foreach ($purchases as $purchase) {
            if ($purchase->membership_id != null) {
                array_push($memberships, $purchase->membership->title);
            }
        }

        $this->data['memberships'] = $memberships;
        return View::make('gym-admin.reports.client.show', $this->data);
    }

    public function downloadClientReport($id, $sd, $ed)
    {
        $clients = null;
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        switch ($id) {
            case 'new':
                $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.id as clientID')
                    ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                    ->whereBetween('gym_clients.joining_date', [$sDate->format('Y-m-d'), $eDate->format('Y-m-d')])
                    ->where('gym_clients.is_client','yes')->where('business_customers.detail_id', '=', $this->data['user']->detail_id)->get();
                break;
            case 'expire':
                $clients = GymPurchase::select('gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'gym_clients.email',
                    'gym_clients.mobile', 'gym_clients.gender', 'gym_memberships.title as membership', 'gym_client_purchases.start_date as start_date', 'gym_client_purchases.expires_on as expires_on')
                    ->whereBetween('expires_on', [$sDate->format('Y-m-d'), $eDate->format('Y-m-d')])
                    ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
                    ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id)
                    ->get();
                break;
            case 'big_spenders':
                $clients = GymPurchase::select('gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'gym_clients.email', 'gym_clients.mobile', 'gym_clients.gender','gym_clients.joining_date','gym_clients.address', 'gym_clients.id as clientID')
                    ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                    ->whereBetween('purchase_date', [$sDate->format('Y-m-d'), $eDate->format('Y-m-d')])
                    ->where('purchase_amount', '>=', 10000)
                    ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)->get();

                break;
            case 'birthday':
                $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'dob','joining_date','address', 'gym_clients.id as clientID')
                    ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                    ->whereMonth('gym_clients.dob', '>=', $sDate->format('m'))
                    ->whereMonth('gym_clients.dob', '<=', $eDate->format('m'))
                    ->where('gym_clients.is_client','yes')->where('business_customers.detail_id', '=', $this->data['user']->detail_id)->get();
                break;
            case 'lost':
                $purchases = GymPurchase::whereBetween('purchase_date', [$sDate->format('Y-m-d'), $eDate->format('Y-m-d')])
                    ->where('detail_id', '=', $this->data['user']->detail_id)->get();
                $users = [];
                foreach ($purchases as $purchase) {
                    if ($purchase->membership_id != null) {
                        $days = $purchase->start_date->diffInDays(Carbon::now('Asia/Kathmandu'));
                        if (($purchase->membership->duration * 30) < $days) {
                            array_push($users, $purchase->client_id);
                        }
                    }
                }
                $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.id as clientID')
                    ->whereIn('id', $users)->get();
                break;
            case 'active':
                $clientIds = GymPurchase::where('expires_on','>=',now())
                    ->where('detail_id', '=', $this->data['user']->detail_id)
                    ->groupBy('client_id','membership_id')
                    ->orderBy('expires_on','desc')
                    ->pluck('client_id');

                $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.id as clientID')
                    ->whereIn('id', $clientIds)->get();
                break;
            case 'inactive':
                    $activeIds = GymPurchase::where('expires_on','>=',now())
                        ->where('detail_id', '=', $this->data['user']->detail_id)
                        ->groupBy('client_id','membership_id')
                        ->pluck('client_id');

                    $clientIds = GymPurchase::where('detail_id', $this->data['user']->detail_id)
                        ->whereNotIn('client_id',$activeIds)
                        ->groupBy('client_id','membership_id')
                        ->pluck('client_id');

                $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.id as clientID')
                        ->whereIn('id', $clientIds)->get();
                    break;

        }

        $pdf = PDF::loadView('pdf.clientReport', compact(['clients', 'id', 'sd', 'ed']));
        return $pdf->download('clientReport.pdf');
    }

    public function downloadExcelClientReport($id, $sd, $ed)
    {
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $user = $this->data['user']->detail_id;
        return Excel::download(new ClientReportExport($id,$sDate,$eDate,$user),'clientReport.xls');
    }
}
