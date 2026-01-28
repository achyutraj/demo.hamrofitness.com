<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Requests\GymAdmin\Subscriptions\AmountRequest;
use App\Models\CustomerSms;
use App\Models\GymClient;
use App\Models\GymSetting;
use App\Models\Locker;
use App\Models\LockerCategory;
use App\Models\LockerPayment;
use App\Models\LockerReservation;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class LockerReservationController extends GymAdminBaseController
{
    public function index() {
        if (!$this->data['user']->can("view_reservations")) {
            return App::abort(401);
        }
        $this->data['lockerMenu']     = 'active';
        $this->data['reservationMenu']     = 'active';
        $this->data['title'] = 'Locker Reservations';
        $this->data['pendingCount']     = LockerReservation::countbusinessReservationStatus($this->data['user']->detail_id,'pending');
        $this->data['deletedCount']     = LockerReservation::onlyTrashed()->where('detail_id', $this->data['user']->detail_id)
            ->count();
        return view('gym-admin.lockers.reservations.index',$this->data);
    }

    public function ajax_create() {
        if (!$this->data['user']->can("view_reservations")) {
            return App::abort(401);
        }
        $reservations = LockerReservation::businessReservation($this->data['user']->detail_id);
        return Datatables::of($reservations)
            ->editColumn('client_id', function ($row) {
                return $row->client?->fullName;
            })
            ->editColumn('locker_id', function ($row) {
                return $row->locker?->lockerCategory->title .'('.$row->locker?->locker_num .')' ?? '';
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->amount_to_be_paid;
            })
            ->editColumn('paid_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->amount_to_be_paid - $row->paid_amount);
            })
            ->editColumn('start_date', function ($row) {
                $action = '';
                if($row->is_renew) {
                    $action = '<br><span class="label label-info">Renew</span>';
                }
                return  $row->start_date->toFormattedDateString() .$action;
            })
            ->editColumn('next_payment_date', function ($row) {
                if ((($row->amount_to_be_paid - $row->paid_amount) > 0)) {
                    if (isset($row->next_payment_date)) {
                        return $row->next_payment_date->toFormattedDateString() . ' <label class="label label-danger">Due</label>';
                    } else {
                        return '<label class="label label-warning">No Payment Received</label>';
                    }
                } else {
                    return '<label class="label label-success">Payment Complete</label>';
                }
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
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group"><button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs hidden-medium">Actions</span>
                        <i class="fa fa-angle-down"></i></button>
                    <ul class="dropdown-menu pull-right" role="menu">';
                if(($row->amount_to_be_paid - $row->paid_amount) > 0){
                    $action .= '<li><a href="' . route('gym-admin.reservations.edit', $row->uuid) . '"> <i class="fa fa-edit"></i>Edit</a>
                        </li><li><a href="javascript:;" data-id="' . $row->uuid . '" class="remove-reservation"> <i class="fa fa-trash"></i>Remove </a>
                        </li><li> <a class="add-payment" data-id="' . $row->uuid . '"  href="javascript:;"><i class="fa fa-plus"></i> Add Payment </a>
                        </li><li> <a  data-id="'.$row->id.'" class="show-locker-reminder"><i class="fa fa-send"></i> Send Reminder </a>
                    </li>';
                }
                if (($row->amount_to_be_paid - $row->paid_amount) == 0) {
                    $action .= '<li><a class="renew-reservation" data-id="' . $row->uuid . '"  href="javascript:;"><i class="icon-refresh"></i>  Renew Reservation</a>
                    </li>';
                }
                $action .= '</ul></div>';
                return $action;
            })
            ->rawColumns(['action','next_payment_date','end_date','locker_id','start_date'])
            ->make(true);
    }

    public function create() {
        if (!$this->data['user']->can("add_reservations")) {
            return App::abort(401);
        }
        $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->active()->get();
        $this->data['reservationMenu']     = 'active';
        $this->data['title']   = "New Reservations";
        $this->data['client_id'] = 0;
        $this->data['lockers'] = Locker::active($this->data['user']->detail_id)->get();
        $this->data['payment_sources'] = listPaymentType();
        return view('gym-admin.lockers.reservations.create',$this->data);

    }

    public function userCreate($id)
    {
        if (!$this->data['user']->can("add_reservations")) {
            return App::abort(401);
        }

        $this->data['title']   = "New Reservations";
        $this->data['reservationMenu']     = 'active';
        $this->data['clients'] = GymClient::findBusinessClient($this->data['user']->detail_id,$id);
        if (is_null($this->data['clients'])) {
            return App::abort(401);
        }
        $this->data['client_id'] = $id;
        $this->data['lockers'] = Locker::active($this->data['user']->detail_id)->get();
        $this->data['payment_sources'] = listPaymentType();
        return view('gym-admin.lockers.reservations.create', $this->data);
    }

    public function store(Request $request) {
        if (!$this->data['user']->can("add_reservations")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),LockerReservation::rules('add'));
        if($validator->fails() ) {
            return Reply::formErrors($validator);
        }
        if ($request->get('discount') >  $request->get('purchase_amount')) {
            return Reply::error("Discount is greater than Cost");
        }
        if ($request->get('payment_amount') >  $request->get('amount_to_be_paid')) {
            return Reply::error("Payment amount is greater than Cost");
        }
        $inputData = $request->all();

        $inputData['purchase_date']    = Carbon::createFromFormat('m/d/Y', $request->get('purchase_date'))->format('Y-m-d');
        $inputData['start_date']       = Carbon::createFromFormat('m/d/Y', $request->get('start_date'));
        $inputData['next_payment_date'] = Carbon::createFromFormat('m/d/Y', $request->get('next_payment_date'))->format('Y-m-d');
        $month = (int)$request->get('price_type');
        $inputData['end_date']    = $inputData['start_date']->addMonths($month);
        $inputData['start_date']       = Carbon::createFromFormat('m/d/Y', $request->get('start_date'))->format('Y-m-d');
        $inputData['payment_required'] = 'yes';
        $inputData['assign_by'] = $this->data['user']->id;
        $inputData['detail_id'] = $this->data['user']->detail_id;
        $reservation = LockerReservation::create($inputData);
        Locker::find($request->get('locker_id'))->update(['status'=>'reserved']);

        //add purchase payment
        if($request->get('payment_amount') > 0){
            if($request->get('payment_date') != null){
                $date = Carbon::createFromFormat('m/d/Y', $request->get('payment_date'));
            }else{
                $date = now()->format('Y-m-d');
            }
            $payData['payment_date']    = $date;
            $payData['client_id']       = $request->get('client_id');
            $payData['detail_id']    = $this->data['user']->detail_id;
            $payData['reservation_id']    = $reservation->id;
            $payData['payment_amount']    = $request->get('payment_amount');
            $payData['payment_source']    = $request->get('payment_source');
            $payData['remarks']    = $request->get('remarks');

            //Update the details of next payment in gym_client_reservation
            $reservation->paid_amount      += $request->get('payment_amount');
            if ($reservation->amount_to_be_paid == $request->get('payment_amount')) {
                $reservation->payment_required = "no";
                $reservation->next_payment_date = null;
            }else{
                $reservation->payment_required = "yes";
                if($request->get('next_payment_date') == null){
                    $date = now()->addDays(2)->format('Y-m-d');
                }else{
                    $date = Carbon::createFromFormat('m/d/Y', $request->get('next_payment_date'));
                }
                $reservation->next_payment_date = $date;
            }
            $reservation->save();
            LockerPayment::create($payData);
        }
        return Reply::redirect(
            route('gym-admin.reservations.index'),'Locker Reservation Added Successfully');
    }

    public function edit($id) {
        if (!$this->data['user']->can("edit_reservations")) {
            return App::abort(401);
        }

        $this->data['reservationMenu'] = 'active';
        $this->data['title']            = "Edit Reservation";
        $this->data['purchase']         = LockerReservation::findByUid($id);
        $category                           =  $this->data['purchase']->locker->lockerCategory;
        $this->data['purchaseTitle'] =   $this->data['purchase']->locker->locker_num .' ( '.$category->title.' ) ';
        $amount = $this->data['purchase']->purchase_amount;
        $month = 0;
        if($category->price == $amount){
            $month = 1;
        }else if($category->three_month_price == $amount){
            $month = 3;
        }else if($category->six_month_price == $amount){
            $month = 6;
        }else if($category->one_year_price == $amount){
            $month = 12;
        }
        $this->data['month'] = $month;
        return view('gym-admin.lockers.reservations.edit', $this->data);
    }

    public function update(Request $request,$id) {
        if (!$this->data['user']->can("edit_reservations")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),LockerReservation::rules('update'));
        if($validator->fails() ) {
            return Reply::formErrors($validator);
        }
        if ($request->get('discount') >  $request->get('purchase_amount')) {
            return Reply::error("Discount is greater than Cost");
        }
        $inputData = $request->all();
        $inputData['start_date']       = Carbon::createFromFormat('m/d/Y', $request->get('start_date'));
        $inputData['end_date']    = $inputData['start_date']->addMonths($request->get('month'));
        $inputData['next_payment_date'] = Carbon::createFromFormat('m/d/Y', $request->get('next_payment_date'))->format('Y-m-d');
        $inputData['start_date']       = Carbon::createFromFormat('m/d/Y', $request->get('start_date'))->format('Y-m-d');

        $inputData['payment_required'] = 'yes';
        if ($request->status == 'on') {
            $inputData['status'] = 'active';
        } else {
            $inputData['status'] = 'pending';
        }
        $purchase = LockerReservation::findByUid($id);
        $purchase->update($inputData);
        return Reply::redirect(route('gym-admin.reservations.index'), 'Locker Reservation Updated Successfully');
    }

    public function destroy($id ,Request $request) {
        if (!$this->data['user']->can("delete_reservations")) {
            return App::abort(401);
        }

        if ($request->ajax()) {
            $purchase = LockerReservation::findByUid($id);
            if($purchase->paid_amount > 0) {
                return Reply::error('Unable to remove.Reservation has some amount.');
            }
            Locker::find($purchase->locker_id)->update(['status'=>'available']);
            $purchase->delete();
            return Reply::success('Locker Reservation removed successfully');
        }

        return Reply::error('Request not Valid');
    }

    public function getLockerCategory($id)
    {
        $this->data['category'] = LockerCategory::find($id);
        $view                    = view('gym-admin.lockers.reservations.category', $this->data)->render();
        return Reply::successWithData('Locker fetched', ['data' => $view]);
    }

    //  Locker Dues
    public function dueIndex() {
        if (!$this->data['user']->can("view_reservations")) {
            return App::abort(401);
        }

        $this->data['dueReservationMenu']     = 'active';
        $this->data['title'] = 'Locker Due Payment';
        return view('gym-admin.lockers.reservations.due',$this->data);
    }

    public function due_ajax_create() {
        if (!$this->data['user']->can("view_reservations")) {
            return App::abort(401);
        }
        $reservations = LockerReservation::where([['payment_required','yes'],['status','active'],['detail_id',$this->data['user']->detail_id]])
            ->get();
        return Datatables::of($reservations)
            ->editColumn('client_id', function ($row) {
                return $row->client?->fullName;
            })
            ->editColumn('locker_id', function ($row) {
                return $row->locker?->lockerCategory->title .'<br>('.$row->locker?->locker_num.')' ?? '';
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->amount_to_be_paid;
            })
            ->editColumn('paid_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->paid_amount;
            })
            ->addColumn('remain_amt', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->amount_to_be_paid - $row->paid_amount);
            })
            ->editColumn('next_payment_date', function ($row) {
                if (isset($row->next_payment_date)) {
                    return $row->next_payment_date->toFormattedDateString() ;
                } else {
                    return 'No due - date';
                }

            })
            ->addColumn('action', function ($row) {
                return "<div class=\"btn-group\">
                            <button class=\"btn btn-xs blue dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"true\"><i class=\"fa fa-gears\"></i><span class=\"hidden-xs hidden-medium\">ACTION</span>
                                <i class=\"fa fa-angle-down\"></i>
                            </button>
                            <ul class=\"dropdown-menu  pull-right\" role=\"menu\">
                                <li>
                                    <a  data-id=\"$row->uuid\" class=\"show-locker-reminder\"><i class=\"fa fa-send\"></i> Send Reminder </a>
                                </li>
                                <li>
                                    <a class=\"add-payment\" data-id=\"$row->uuid\"  href=\"javascript:;\"><i class=\"fa fa-plus\"></i> Add Payment </a>
                                </li>
                            </ul>
                        </div>";
            })
            ->rawColumns(['action','next_payment_date','remain_amt','locker_id'])
            ->make(true);
    }

    //Pending Reservation
    public function pendingReservation()
    {
        if (!$this->data['user']->can("view_reservations")) {
            return App::abort(401);
        }
        $this->data['lockerMenu']       = 'active';
        $this->data['reservationMenu'] = 'active';
        $this->data['title']            = "Pending Locker Reservation";

        $this->data['deletedCount']     = LockerReservation::onlyTrashed()
            ->where('detail_id', $this->data['user']->detail_id)->count();
        return view('gym-admin.lockers.reservations.pending', $this->data);
    }

    public function ajaxPendingReservation()
    {
        $purchase = LockerReservation::where('detail_id', $this->data['user']->detail_id)
            ->where('status', '=', 'pending')->latest();

        return Datatables::of($purchase)
            ->editColumn('client_id', function ($row) {
                return $row->client?->fullName;
            })
            ->editColumn('locker_id', function ($row) {
                return ucwords($row->locker->locker_num);
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .$row->amount_to_be_paid;
            })
            ->addColumn('remaining', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .($row->amount_to_be_paid - $row->paid_amount);
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i><span class="hidden-xs">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a href="' . route('gym-admin.reservations.edit', $row->uuid) . '"> <i class="fa fa-edit"></i>Edit</a>
                        </li>
                        <li>
                            <a href="javascript:;" data-id="' . $row->uuid . '" class="remove-reservation"> <i class="fa fa-trash"></i>Remove </a>
                        </li>
                    </ul>
                </div>';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->toFormattedDateString();
            })
            ->editColumn('next_payment_date', function ($row) {
                if (!is_null($row->next_payment_date)) {
                    if (Carbon::today()->diffInDays($row->next_payment_date, false) <= 0) {
                        return $row->next_payment_date->toFormattedDateString() . ' <label class="label label-danger">Due</label>';
                    } else {
                        return $row->next_payment_date->toFormattedDateString();
                    }
                } else if ($row->amount_to_be_paid <= $row->paid_amount) {
                    return '<label class="label label-success">Payment Complete</label>';
                } else {
                    return '<label class="label label-warning">No Payment Received</label>';
                }
            })
            ->editColumn('end_date', function ($row) {
                if (!is_null($row->end_date)) {
                    return $row->end_date->toFormattedDateString();
                } else {
                    return '-';
                }

            })
            ->rawColumns(['amount_to_be_paid','remaining','next_payment_date', 'action','locker_id','client_id'])
            ->make(true);
    }

    //Deleted Reservation
    public function deletedReservation()
    {
        if (!$this->data['user']->can("delete_reservations")) {
            return App::abort(401);
        }
        $this->data['lockerMenu']       = 'active';
        $this->data['reservationMenu'] = 'active';
        $this->data['title']            = "Deleted Locker Reservations";

        $this->data['pendingCount']     = LockerReservation::where('detail_id', $this->data['user']->detail_id)
            ->where('status', 'pending')->count();

        return view('gym-admin.lockers.reservations.delete', $this->data);
    }

    public function ajaxDeletedReservation()
    {
        $purchase = LockerReservation::onlyTrashed()->where('detail_id',  $this->data['user']->detail_id);

        return Datatables::of($purchase)
            ->editColumn('client_id', function ($row) {
                return $row->client?->fullName;
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .$row->amount_to_be_paid;
            })
            ->addColumn('remaining', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .($row->amount_to_be_paid - $row->paid_amount);
            })
            ->editColumn('locker_id', function ($row) {
                return $row->locker?->locker_num;
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i><span class="hidden-xs hidden-medium">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a href="javascript:;" data-id="' . $row->id . '" class="restore-reservation"> <i class="fa fa-undo"></i>Restore</a>
                        </li>
                        <li>
                            <a href="javascript:;" data-id="' . $row->id . '" class="remove-reservation"> <i class="fa fa-trash"></i>Remove </a>
                        </li>
                    </ul>
                </div>';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->toFormattedDateString();
            })
            ->editColumn('deleted_at', function ($row) {
                return $row->deleted_at->toFormattedDateString();
            })
            ->editColumn('end_date', function ($row) {
                if (!is_null($row->end_date)) {
                    return $row->end_date->toFormattedDateString();
                } else {
                    return '-';
                }
            })
            ->rawColumns(['amount_to_be_paid','remaining', 'action','locker_id','client_id'])
            ->make(true);
    }

    public function restore($id ,Request $request) {
        if (!$this->data['user']->can("delete_reservations")) {
            return App::abort(401);
        }
        $purchase = LockerReservation::withTrashed()->find($id);
        $status = Locker::find($purchase->locker_id);
        if($status->status == 'reserved'){
            return Reply::error($status->locker_num.' has been assigned to other.');
        }
        $purchase->restore();
        $status->update(['status'=>'reserved']);
        return Reply::success('Reservation restore successfully');
    }

    public function delete($id, Request $request)
    {
        if (!$this->data['user']->can("delete_subscriptions")) {
            return App::abort(401);
        }

        if ($request->ajax()) {
            $purchase = LockerReservation::withTrashed()->find($id);
            $purchase->forceDelete();
            return Reply::success('Reservation removed permanently.');
        }

        return Reply::error('Request not Valid');
    }

    public function lockerReminderModal($id)
    {
        $payment_details = LockerReservation::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'lockers.locker_num as locker_number', 'locker_reservations.id as reservationId')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('lockers', 'lockers.id', '=', 'locker_id')
            ->where('locker_reservations.id', '=', $id)
            ->first();

        $this->data['smsSetting']   = GymSetting::select('detail_id', 'sms_status')->where('detail_id', $this->data['user']->detail_id)->get();

        $this->data['client_data'] = $payment_details;
        $this->data['id']          = $id;
        return view('gym-admin.lockers.reservations.sendreminder', $this->data);
    }

    public function sendReminder(Request $request)
    {
        $mobile     = $request->get('mobile');
        $locker = $request->get('locker');
        $reservationId = $request->get('reservationId');

        $purchase = LockerReservation::find($reservationId);

        $smsText  = 'Dear Customer, your reservation of locker number ' . $locker . ' is expiring on ' . ((isset($purchase->end_date)) ? $purchase->end_date->format('d M, Y') : '') . '. Please renew asap. '.$this->data['common_details']->title;

        if ($request->get('smsReminder') == 1) {
            $this->smsNotification([$mobile], $smsText);
        }

        $customerSmsData = [
            'message' => $smsText,
            'status' => 1,
            'phone' => $mobile,
            'recipient_id'     => $purchase->client_id,
            'sender_id'     => $this->data['user']->id,
        ];
        CustomerSms::create($customerSmsData);

        return Reply::success('Reminder sent successfully');
    }

    //Renew Reservations
    public function renewReservationModal($id)
    {
        if (!$this->data['user']->can("add_reservations")) {
            return App::abort(401);
        }
        $this->data['reservation'] = LockerReservation::findByUid($id);
        $category = $this->data['reservation']->locker->lockerCategory;
        $amount = $this->data['reservation']->purchase_amount;
        $month = 0;
        if($category->price == $amount){
            $month = 1;
        }else if($category->three_month_price == $amount){
            $month = 3;
        }else if($category->six_month_price == $amount){
            $month = 6;
        }else if($category->one_year_price == $amount){
            $month = 12;
        }
        $this->data['month'] = $month;
        return view('gym-admin.lockers.reservations.renew_reservation_modal', $this->data);
    }

    public function renewReservationStore(Request $request, $id)
    {
        if (!$this->data['user']->can("add_reservations")) {
            return App::abort(401);
        }
        $validator =  Validator::make($request->all(),LockerReservation::rules('update'));
        if($validator->fails() ) {
            return Reply::formErrors($validator);
        }

        $reservation = LockerReservation::find($id);
        $locker = Locker::find($reservation->locker_id);

        // Check if current reservation is expired
        $currentDate = Carbon::today();
        if ($reservation->end_date && $reservation->end_date->gt($currentDate)) {
            return Reply::error("Cannot renew. Current reservation is still active until " . $reservation->end_date->format('d M, Y'));
        }

        // Check if locker is currently reserved to someone else
        $currentActiveReservation = LockerReservation::where('locker_id', $reservation->locker_id)
            ->where('detail_id', $this->data['user']->detail_id)
            ->where('status', 'active')
            ->where('end_date', '>', $currentDate)
            ->where('id', '!=', $reservation->id) // Exclude the current reservation being renewed
            ->first();

        if ($currentActiveReservation) {
            // Check if the current active reservation belongs to the same client
            if ($currentActiveReservation->client_id != $reservation->client_id) {
                return Reply::error("Unable to renew. Locker " . $locker->locker_num . " is currently reserved to another person.");
            }
        }

        $inputData = $request->all();
        $inputData['purchase_date']    = Carbon::createFromFormat('m/d/Y', $request->get('purchase_date'));
        $inputData['start_date']       = Carbon::createFromFormat('m/d/Y', $request->get('start_date'));

        $date = Carbon::today()->format('Y-m-d');
        $inputData['end_date']    = $inputData['start_date']->addMonths($request->get('month'));
        if(!$reservation->end_date->lt($date)){
            $remain = $reservation->end_date->diffInDays($date);
            $inputData['end_date'] = $inputData['end_date']->addDays($remain);
        }

        $inputData['next_payment_date'] = Carbon::createFromFormat('m/d/Y', $request->get('next_payment_date'))->format('Y-m-d');
        $inputData['start_date']       = Carbon::createFromFormat('m/d/Y', $request->get('start_date'))->format('Y-m-d');
        $inputData['discount'] = $request->get('discount') ?? 0;
        $inputData['payment_required'] = 'yes';
        $inputData['is_renew'] = 1;
        $inputData['locker_id'] = $reservation->locker_id;
        $inputData['client_id'] = $reservation->client_id;
        $inputData['detail_id'] = $reservation->detail_id;
        $inputData['assign_by'] = $this->data['user']->id;
        LockerReservation::create($inputData);
        $locker->update(['status'=>'reserved']);
        return Reply::success('Reservation Renewed Successfully');
    }

}
