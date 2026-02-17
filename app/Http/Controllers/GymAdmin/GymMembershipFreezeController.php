<?php

namespace App\Http\Controllers\GymAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Reply;
use App\Http\Requests\GymAdmin\Subscriptions\FreezeSubscriptionRequest;
use Carbon\Carbon;
use App\Models\GymMembershipFreeze;
use App\Models\GymPurchase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Yajra\Datatables\Datatables;

class GymMembershipFreezeController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("subscription_extend_features")) {
            return App::abort(401);
        }
        $this->data['freezesubscriptionMenu'] = 'active';
        $this->data['account']            = 'active';
        $this->data['title']              = 'Freeze Subscription';
        return View::make('gym-admin.purchase.freeze', $this->data);
    }

    public function ajax_create()
    {
        if (!$this->data['user']->can("subscription_extend_features")) {
            return App::abort(401);
        }

        $purchase = GymMembershipFreeze::where('detail_id', $this->data['user']->detail_id)->get();

        return Datatables::of($purchase)
            ->editColumn('client_id', function ($row) {
                $name = $row->client->fullName ?? '' ;
                return '<a href="'. route('gym-admin.client.show', $row->client_id).'">'.
                $name .'</a>' ;
            })
            ->editColumn('purchase_id', function ($row) {
                return $row->purchase->membership->title ?? '' ;
            })
            ->editColumn('days', function ($row) {
                return $row->days ?? 0 ;
            })
            ->editColumn('added_by', function ($row) {
                return $row->addedBy->first_name ?? '';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->toFormattedDateString();
            })
            ->editColumn('end_date', function ($row) {
                return $row->end_date != null ? $row->end_date->toFormattedDateString() : '';
            })
            ->addColumn('actions', function ($row) {
                $action = '';
                if($row->is_frozen == 1 && $row->start_date < today()){
                    $action =  '<div class="btn-group">
                    <button class="btn blue btn-xs unfreeze" data-uuid="'.$row->uuid.'" type="button"><i class="fa fa-pause"></i>Unfreeze</span>
                    </button>
                    </div>';
                }
                return $action;
            })
            ->rawColumns(['actions','client_id','purchase_id','added_by','start_date','end_date'])
            ->make(true);
    }

    public function showModal($id)
    {
        if (!$this->data['user']->can("subscription_extend_features")) {
            return App::abort(401);
        }
        $this->data['purchase'] = GymPurchase::find($id);
        return view('gym-admin.purchase.freeze-modal', $this->data);
    }

    public function store(FreezeSubscriptionRequest $request, $id)
    {
        if (!$this->data['user']->can("subscription_extend_features")) {
            return App::abort(401);
        }
        $purchase = GymPurchase::find($id);
        $freeze                    = new GymMembershipFreeze();
        $freeze->client_id         = $purchase->client_id;
        $freeze->purchase_id       = $purchase->id;
        $freeze->added_by          = $this->data['user']->id;
        $freeze->detail_id         = $this->data['user']->detail_id;
        $freeze->start_date        = Carbon::createFromFormat('m/d/Y', $request->get('start_date'));
        $freeze->reasons           = $request->get('reasons');

        //change purchase status;
        if($request->get('start_date') == now()->format('m/d/Y')){
            $purchase->status = 'freeze';
        }else{
            $purchase->status = 'freeze_pending';
        }
        $purchase->save();
        $freeze->save();
        return Reply::success('Subscription Freeze Successfully');
    }

    public function update(Request $request,$id){
        $freeze                = GymMembershipFreeze::findByUid($id);
        $freeze->is_frozen     = 0;
        $freeze->end_date      = now()->format('Y-m-d');
        $freeze->days          = calculateTotalDays($freeze->start_date,now()->format('Y-m-d'));

        //change purchase status;
        $days                  = calculateTotalDays($freeze->start_date,now()->format('Y-m-d'));
        $purchase = GymPurchase::find($freeze->purchase_id);
        $purchase->status = 'active';
        $purchase->expires_on = $purchase->expires_on->addDays($days);
        $purchase->save();
        $freeze->save();
        return Reply::success('Subscription UnFreeze Successfully');
    }
}
