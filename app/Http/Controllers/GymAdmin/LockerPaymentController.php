<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\GymClient;
use App\Models\Locker;
use App\Models\LockerPayment;
use App\Models\LockerReservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class LockerPaymentController extends GymAdminBaseController
{
    public function index()
    {
        if (!$this->data['user']->can("view_payments")) {
            return App::abort(401);
        }
        $this->data['lockerMenu']     = 'active';
        $this->data['reservationPaymentMenu'] = 'active';
        $this->data['title'] = 'Locker Payments';
        return view('gym-admin.lockers.payments.index', $this->data);
    }

    public function ajax_create()
    {
        if (!$this->data['user']->can("view_payments")) {
            return App::abort(401);
        }
        $payments = LockerPayment::withoutTrashed()->where('detail_id',$this->data['user']->detail_id)->get();

        return Datatables::of($payments)
            ->editColumn('client_id', function ($row) {
                return $row->client->fullName ?? '';
            })
            ->editColumn('locker', function ($row) {
                return $row->reservation->locker->locker_num ?? '';
            })
            ->editColumn('payment_source', function ($row) {
                return getPaymentType($row->payment_source);
            })
            ->editColumn('payment_date', function ($row) {
                return $row->payment_date->toFormattedDateString();
            })
            ->editColumn('payment_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
            })
            ->editColumn('payment_id', function ($row) {
                return $row->payment_id ;
            })
            ->addColumn('remarks', function ($row) {
                return $row->remarks;
            })
            ->addColumn('action', function ($row) {
                return "<div class=\"btn-group\">
                            <button class=\"btn btn-xs blue dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"true\"><i class=\"fa fa-gears\"></i><span class=\"hidden-xs\">ACTION</span>
                                <i class=\"fa fa-angle-down\"></i>
                            </button>
                            <ul class=\"dropdown-menu  pull-right\" role=\"menu\">
                                <li>
                                    <a href='" . route("gym-admin.gym-invoice.create-locker-payment-invoice", $row->uuid) . "'><i class=\"fa fa-file\"></i> Generate Invoice </a>
                                </li>
                                <li>
                                    <a href='" . route("gym-admin.reservation-payments.edit", $row->uuid) . "'><i class=\"fa fa-edit\"></i> Edit </a>
                                </li>
                                <li>
                                    <a class=\"remove-payment\" data-payment-id=\"$row->uuid\"  href=\"javascript:;\"><i class=\"fa fa-trash\"></i> Delete </a>
                                </li>
                            </ul>
                        </div>";
            })
            ->rawColumns(['action','client_id','payment_source','locker','payment_date'])
            ->make(true);
    }

    public function ajax_create_deleted()
    {
        if (!$this->data['user']->can("view_payments")) {
            return App::abort(401);
        }

        $payments = LockerPayment::onlyTrashed()
            ->where('detail_id', $this->data['user']->detail_id);

        return Datatables::of($payments)
            ->editColumn('client_id', function ($row) {
                return $row->client->fullName ?? '';
            })
            ->addColumn('locker', function ($row) {
                return $row->reservation->locker->locker_num ?? '';
            })
            ->editColumn('payment_source', function ($row) {
                return getPaymentType($row->payment_source);
            })
            ->editColumn('payment_date', function ($row) {
                return $row->payment_date->toFormattedDateString();
            })
            ->editColumn('payment_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
            })
            ->editColumn('payment_id', function ($row) {
                return $row->payment_id ;
            })
            ->editColumn('deleted_at', function ($row) {
                return $row->deleted_at->toFormattedDateString();
            })
            ->rawColumns(['payment_source','locker','payment_date','deleted_at'])
            ->make(true);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_payments")) {
            return App::abort(401);
        }
        $this->data['title']       = 'Add Payment';
        $this->data['clients']     = GymClient::GetClients($this->data['user']->detail_id)->active()->whereHas('reservations')->get();
        $this->data['lockers'] = Locker::businessLockers($this->data['user']->detail_id);
        $this->data['payment_sources'] = listPaymentType();
        return view('gym-admin.lockers.payments.create', $this->data);
    }

    public function userPayCreate($reservationId)
    {
        if (!$this->data['user']->can("add_payments")) {
            return App::abort(401);
        }
        $this->data['title']       = 'Add Payment';
        $this->data['userReservation'] = true;
        $this->data['clients']     = GymClient::GetClients($this->data['user']->detail_id)->whereHas('reservations')->get();
        $this->data['reservation'] = LockerReservation::findOrFail($reservationId);
        if (is_null($this->data['reservation'])) {
            return App::abort(404);
        }
        $this->data['payment_sources'] = listPaymentType();
        $this->data['amount'] = $this->data['reservation'] ? $this->data['reservation']->amount_to_be_paid : 0;
        return view('gym-admin.lockers.payments.create', $this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("add_payments")) {
            return App::abort(401);
        }
        $validator = Validator::make($request->all(), LockerPayment::rules('add'));
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $purchase                   = LockerReservation::find($request->get('reservation_id'));
            $remain = $purchase->amount_to_be_paid - $purchase->paid_amount;
            if(request()->get('payment_amount') > $remain){
                return Reply::error("Remaining amount is ".$remain);
            }
            $inputData = $request->all();
            $inputData['payment_date']    = Carbon::createFromFormat('m/d/Y', $request->get('payment_date'))->format('Y-m-d') ?? today()->format('Y-m-d');
            $inputData['client_id'] = $request->get('client');
            $inputData['detail_id'] = $this->data['user']->detail_id;
             //Update the details of next payment in gym_client_reservation
             $this->updateReservation($purchase->uuid);
            LockerPayment::create($inputData);
            return Reply::redirect(route('gym-admin.reservation-payments.index'), 'Locker Payment Added Successfully');
        }
    }

    public function edit($id)
    {
        if (!$this->data['user']->can("edit_payments")) {
            return App::abort(401);
        }
        $this->data['title']            = 'Edit Locker Payment';
        $this->data['clients']          = GymClient::GetClients($this->data['user']->detail_id);
        $this->data['payment']          = LockerPayment::findByUid($id);
        $this->data['purchases']        = LockerReservation::find($this->data['payment']->reservation_id);
        $this->data['remaining_amount'] = ($this->data['purchases']->amount_to_be_paid - $this->data['purchases']->paid_amount);
        $this->data['payment_sources'] = listPaymentType();

        return view('gym-admin.lockers.payments.edit', $this->data);
    }

    public function update(Request $request,$id)
    {
        if (!$this->data['user']->can("edit_payments")) {
            return App::abort(401);
        }
        $validator = Validator::make(request()->all(), LockerPayment::rules('add'));

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $purchase    = LockerReservation::find(request()->get('reservation_id'));
            $remain = $purchase->amount_to_be_paid - $purchase->paid_amount;
            if(request()->get('payment_amount') > $remain){
                return Reply::error("Remaining amount is ".$remain);
            }
            $inputData = $request->all();
            $inputData['payment_date']       = Carbon::createFromFormat('m/d/Y', $request->get('payment_date'));
            $inputData['payment_required'] = 'yes';
            $payment = LockerPayment::findByUid($id);
            $payment->update($inputData);

            //Update the details of next payment in gym_client_purchases
            $this->updateReservation($purchase->uuid,'edit');
            return Reply::redirect(route('gym-admin.reservation-payments.index'), 'Payment Updated Successfully');
        }
    }

    public function destroy($id, Request $request)
    {
        if (!$this->data['user']->can("delete_payments")) {
            return App::abort(401);
        }

        if ($request->ajax()) {
            $payment      = LockerPayment::findByUid($id);
            $old_amount   = $payment->payment_amount;
            if ($payment->reservation_id) {
                $purchase              = LockerReservation::find($payment->reservation_id);
                $purchase->paid_amount = $purchase->paid_amount - $old_amount;
                $purchase->save();
            }
            LockerPayment::findByUid($id)->delete();
            return Reply::success('Payment removed successfully');
        }
        return Reply::error('Request not Valid');
    }

    public function addPaymentModal($id)
    {
        $this->data['purchase'] = LockerReservation::findByUid($id);
        $this->data['payment_sources'] = listPaymentType();
        return view('gym-admin.lockers.payments.add_payment_modal', $this->data);
    }

    public function ajaxPaymentStore($id)
    {
        $validator = Validator::make(request()->all(), LockerPayment::rules('ajax_add'));

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $purchase  = LockerReservation::findByUid($id);
            $remain = $purchase->amount_to_be_paid - $purchase->paid_amount;
            if(request()->get('payment_amount') > $remain){
                return Reply::error("Remaining amount is ".$remain);
            }
            $inputData = request()->except('_token');
            $inputData['client_id']  = $purchase->client_id;
            $inputData['reservation_id']  = $purchase->id;
            $inputData['detail_id']  = $this->data['user']->detail_id;
            $inputData['payment_date']  = Carbon::createFromFormat('m/d/Y', request()->get('payment_date'))->format('Y-m-d');
            // Update the details of next payment in gym_client_purchases
           $this->updateReservation($purchase->uuid);

           LockerPayment::create($inputData);
            return Reply::redirect(route('gym-admin.reservation-payments.index'), 'Locker Payment Added Successfully');
        }
    }

    public function updateReservation($reservationID,$action = null){
        $reservation  = LockerReservation::findByUid($reservationID);
        $paid_amount = $reservation->paid_amount ;
        if($action != 'edit'){
            $paid_amount      += request()->get('payment_amount');
        }else{
            $paid_amount      = request()->get('payment_amount');
        }
        $reservation->paid_amount      = $paid_amount;
        if($reservation->amount_to_be_paid == $paid_amount){
            $reservation->payment_required = 'no';
        }else{
            $reservation->payment_required = 'yes';
        }
        $reservation->next_payment_date = request()->get('next_payment_date') ?? today()->addDays(2);
        $reservation->save();
    }

    public function clientPurchases($id)
    {
        $this->data['purchases'] = LockerReservation::select('*')->selectRaw('amount_to_be_paid - paid_amount as diff')->where('payment_required','yes')
            ->where('status','active')->where('client_id',$id)->get();
        $view                    = view('gym-admin.lockers.payments.client_locker_ajax', $this->data)->render();
        return Reply::successWithData('Client Locker fetched', ['data' => $view]);
    }

    public function clientPayment($id)
    {
        $payAmount             = request()->get('amount');
        $this->data['payment'] = LockerReservation::select(DB::raw("(amount_to_be_paid - paid_amount)-$payAmount as 'diff' "))
            ->where('client_id', $id)->first();
        return $this->data;
    }

    public function remainingPayment($id)
    {
        $purchase   = LockerReservation::find($id);
        return ($purchase->amount_to_be_paid - $purchase->paid_amount);
    }
}
