<?php

namespace App\Exports\Reports;

use App\Models\GymMembershipPayment;
use App\Models\ProductPayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class TargetReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($id = null,$type= null,$user = null) {
        $this->id = $id;
        $this->type = $type;
        $this->user = $user;
    }

    public function collection()
    {
        //
    }

    public function view() : View
    {
        if ($this->type == 'membership') {
            $data = GymMembershipPayment::leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')
                ->leftJoin('set_targets', 'set_targets.detail_id', '=', 'gym_client_purchases.detail_id')
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
                ->where('set_targets.id', $this->id)
                ->where('gym_client_purchases.detail_id', '=', $this->user)->get();
        } else {
            $data = ProductPayment::leftJoin('product_sales', 'product_sales.id', '=', 'product_payments.product_sale_id')
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_payments.user_id')
                ->leftJoin('set_targets', 'set_targets.detail_id', '=', 'product_payments.branch_id')
                ->where('set_targets.id', $this->id)
                ->where('product_payments.branch_id', '=', $this->user)->get();
        }
        return view('gym-admin.reports.target.excel',['data'=>$data,'type'=>$this->type]);
    }
}
