<?php

namespace App\Http\Controllers\Customer;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Mail\AdminSubscriptionNotification;
use App\Models\Locker;
use App\Models\LockerCategory;
use App\Models\LockerReservation;
use App\Models\Merchant;
use App\Models\MerchantNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class LockerReservationController extends CustomerBaseController
{
    public function index()
    {
        $this->data['title'] = 'HamroFitness | Locker Reservation';
        $this->data['lockerMenu'] = 'active';
        $this->data['reservationMenu'] = 'active';
        return view('customer-app.lockers.reservations.index', $this->data);
    }

    public function create()
    {
        $this->data['lockers'] = Locker::where('status','available')->where('detail_id',$this->data['customerValues']->detail_id)->get();
        return view('customer-app.lockers.reservations.create', $this->data);
    }

    public function store(Request $request)
    {
        $inputData = $request->all();
        $inputData['purchase_date']    = Carbon::today()->format('Y-m-d');
        $inputData['start_date']       = Carbon::createFromFormat('m/d/Y', $request->get('joining_date'));
        $inputData['end_date']    = LockerReservation::getLockerExpire($request->get('locker_id'),$request->get('joining_date'));
        $inputData['payment_required'] = 'yes';
        $inputData['amount_to_be_paid'] = $request->get('purchase_amount');
        $inputData['status'] = 'pending';
        $inputData['detail_id'] =  $this->data['customerValues']->detail_id;
        $inputData['client_id'] =  $this->data['customerValues']->id;
        LockerReservation::create($inputData);
        Locker::find($request->get('locker_id'))->update(['status'=>'requested']);

        //region Notification
        $notification = new MerchantNotification();
        $notification->detail_id = $this->data['customerValues']->detail_id;
        $notification->notification_type = 'Locker Reservation';
        $notification->title = 'New locker reservation is added by customer';
        $notification->save();
        //endregion

        $admin = Merchant::find($this->data['customerValues']->detail_id);

        $eText = "".$this->data['customerValues']->first_name.' '.$this->data['customerValues']->middle_name.' '.$this->data['customerValues']->last_name."added a locker reservation";

        $this->data['title'] = "Locker Reservation Notification";
        $this->data['mailHeading'] = "Locker Reservation Notification";
        $this->data['emailText'] = $eText;
        $this->data['url'] = '';

        try {
            Mail::to($admin->email)->send(new AdminSubscriptionNotification($this->data));
        } catch (\Exception $e) {
            $response['errorEmailMessage'] = 'error';
        }
        return Reply::redirect(route('customer-app.reservations.index'), 'Locker Reservation added successfully.');
    }

    public function show($id)
    {
        $this->data['reservation'] = LockerReservation::findByUid($id);
        return view('customer-app.lockers.reservations.view-modal', $this->data);
    }

    public function destroy($id)
    {
        $data = LockerReservation::findByUid($id);
        if($data->status == 'active'){
            return Reply::error('Reservation unable to delete');
        }
        Locker::find($data->locker_id)->update(['status'=>'available']);
        $data->delete();
        return Reply::success('Reservation deleted successfully');
    }

    public function getData()
    {
        $purchase = LockerReservation::where('client_id', $this->data['customerValues']->id)->get();
        return Datatables::of($purchase)
            ->editColumn('locker_id', function ($row) {
                return ucwords($row->locker->locker_num);
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                if (!is_null($row->next_payment_date)) {
                    if (Carbon::today()->diffInDays($row->next_payment_date, false) <= 0) {
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
                    return '<button class="btn btn-sm btn-info waves-effect view-reservation" data-pk="'.$row->uuid.'">View</button>';
                } else {
                    return '<button class="btn btn-sm btn-danger waves-effect delete-reservation" data-pk="'.$row->uuid.'">Delete</button>';
                }
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->toFormattedDateString();
            })
            ->editColumn('end_date', function ($row) {
                if (!is_null($row->end_date)) {
                    $status = 'danger';
                    if($row->end_date > Carbon::now()){
                        $status = 'success';
                    }
                    return $row->end_date->toFormattedDateString() . ' <label class="label label-'.$status.'">'.$row->end_date->diffForHumans().'</label>';
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
            ->rawColumns(['action','amount_to_be_paid','status','locker_id','end_date'])
            ->make(true);
    }

    public function getLockerAmount(Request $request)
    {
        $locker = Locker::find($request->locker_id);
        $price = $locker->lockerCategory->price;
        return json_encode($price);
    }

    public function getLockerCategory($id)
    {
        $this->data['category'] = LockerCategory::find($id);
        $view                    = view('customer-app.lockers.reservations.category', $this->data)->render();
        return Reply::successWithData('Locker fetched', ['data' => $view]);
    }
}
