<?php

namespace App\Http\Controllers\Customer;

use App\Classes\Reply;
use App\Http\Requests\CustomerApp\ManageSubscription\StoreSubscriptionRequest;
use App\Mail\AdminSubscriptionNotification;
use App\Models\GymClient;
use App\Models\GymMembership;
use App\Models\Merchant;
use App\Models\Role;
use App\Models\GymPurchase;
use App\Models\MerchantNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\Datatables\Datatables;

class CustomerManageSubscriptionController extends CustomerBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = 'HamroFitness | Subscription';
        $this->data['subscriptionMenu'] = 'active';

        return view('customer-app.manage-subscription.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['businesses'] = GymClient::leftJoin('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
            ->leftJoin('common_details', 'common_details.id','=', 'business_customers.detail_id')
            ->where('gym_clients.is_client','yes')
            ->where('business_customers.customer_id', '=', $this->data['customerValues']->id)
            ->select('common_details.id as id', 'common_details.title')
            ->get();

        return view('customer-app.manage-subscription.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $purchase = new GymPurchase();
        $purchase->client_id = $this->data['customerValues']->id;
        $purchase->membership_id = $request->get('membership_id');
        $purchase->detail_id = $request->get('branch_id');
        $purchase->purchase_amount = $request->get('cost');
        $purchase->amount_to_be_paid = $request->get('cost');
        $purchase->paid_amount = 0;
        $purchase->discount = 0;
        $purchase->start_date = Carbon::createFromFormat('m/d/Y', $request->get('joining_date'))->format('Y-m-d');
        $purchase->status = 'pending';
        $purchase->payment_required = 'yes';
        $purchase->purchase_date = Carbon::now()->format('Y-m-d');
        $purchase->save();

        //region Notification
        $notification = new MerchantNotification();
        $notification->detail_id = $request->get('branch_id');
        $notification->notification_type = 'Subscription';
        $notification->title = 'New subscription is added by customer';
        $notification->save();
        //endregion

        $admin = Merchant::find($this->data['customerValues']->detail_id);

        $eText = "".$this->data['customerValues']->first_name.' '.$this->data['customerValues']->middle_name.' '.$this->data['customerValues']->last_name."added a Subscription";

        $this->data['title'] = "Subscription Notification";
        $this->data['mailHeading'] = "Subscription Notification";
        $this->data['emailText'] = $eText;
        $this->data['url'] = '';

        try {
            Mail::to($admin->email)->send(new AdminSubscriptionNotification($this->data));
        } catch (\Exception $e) {
            $response['errorEmailMessage'] = 'error';
        }


        return Reply::redirect(route('customer-app.manage-subscription.index'), 'Subscription is added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->data['showSubscription'] = GymPurchase::find($id);

        return view('customer-app.manage-subscription.view-modal', $this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        GymPurchase::destroy($id);

        return Reply::success('Subscription is deleted successfully');
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        $purchase = GymPurchase::select('amount_to_be_paid', 'paid_amount', 'gym_memberships.title as membership_title', 'start_date', 'next_payment_date', 'expires_on', 'gym_client_purchases.id', 'gym_client_purchases.status')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('client_id', $this->data['customerValues']->id)
            ->orderBy('gym_client_purchases.id', 'desc')
            ->get();

        return Datatables::of($purchase)
            ->addColumn('membership_title', function ($row) {
                return ucwords($row->membership_title);
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                if (!is_null($row->next_payment_date)) {
                    if (Carbon::now('Asia/Kathmandu')->diffInDays($row->next_payment_date, false) <= 0) {
                        $paymentDate =  $row->next_payment_date->toFormattedDateString() . ' <label class="label label-danger">Due</label>';
                    }
                    else {
                        $paymentDate = $row->next_payment_date->toFormattedDateString();
                    }
                }
                else if ($row->amount_to_be_paid <= $row->paid_amount) {
                    $paymentDate = '<label class="label label-success">Payment Complete</label>';
                }
                else {
                    $paymentDate = '<label class="label label-warning">No Payment Received</label>';
                }

                $str = '<ul>
                            <li>Amount To Be Paid:  '.$this->data['gymSettings']->currency->acronym.' '.$row->amount_to_be_paid.'</li>
                            <li>Remaining Amount:  '.$this->data['gymSettings']->currency->acronym.' '.($row->amount_to_be_paid - $row->paid_amount).'</li>
                            <li>Next Payment: '.$paymentDate.'</li>
                        </ul>';

                return $str;
            })
            ->addColumn('action', function ($row) {
                if($row->status == 'active') {
                    return '<button class="btn btn-sm btn-success waves-effect view-subscription" data-pk="'.$row->id.'">View</button>';
                } else if($row->status == 'freeze') {
                    return '<button class="btn btn-sm btn-info waves-effect view-subscription" data-pk="'.$row->id.'">View</button>';
                }  else {
                    return '<button class="btn btn-sm btn-danger waves-effect delete-subscription" data-pk="'.$row->id.'">Delete</button>';
                }
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->toFormattedDateString();
            })
            ->editColumn('expires_on', function ($row) {
                if (!is_null($row->expires_on)) {
                    $status = 'danger';
                    if($row->expires_on > Carbon::now()){
                        $status = 'success';
                    }
                    return $row->expires_on->toFormattedDateString() . ' <label class="label label-'.$status.'">'.$row->expires_on->diffForHumans().'</label>';
                } else {
                    return '-';
                }
            })
            ->editColumn('status', function($row) {
                if($row->status == 'active') {
                    return '<label class="label label-success">'.ucwords($row->status).'</label>';
                } else {
                    return '<label class="label label-danger">'.ucwords($row->status).'</label>';
                }
            })
            ->removeColumn('id')
            ->removeColumn('paid_amount')
            ->removeColumn('next_payment_date')
            ->rawColumns(['action','amount_to_be_paid','status','membership_title','expires_on'])
            ->make(true);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getMembership(Request $request)
    {
        $memberships = GymMembership::where('detail_id', '=', $request->branch_id)
            ->get();

        $output = [];
        foreach ($memberships as $membership) {
            array_push($output, $membership);
        }

        return json_encode($output);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getMembershipAmount(Request $request)
    {
        $price = GymMembership::find($request->membership_id);

        return json_encode($price);
    }
}
