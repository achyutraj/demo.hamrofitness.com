<?php

namespace App\Exports\Reports;

use App\Models\GymClient;
use App\Models\GymPurchase;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ClientReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($id = null,$sDate = null,$eDate = null,$user = null) {
        $this->id = $id;
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->user = $user;
    }
    public function collection()
    {
        //
    }

    public function view() : View
    {
        $clients = null;
        switch ($this->id)
        {
            case 'new':
                $clients = GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                    ->whereBetween('gym_clients.joining_date', [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                    ->where('gym_clients.is_client',  'yes')
                    ->where('business_customers.detail_id', '=', $this->user)->get();
                break;
            case 'expire':
                $clients = GymPurchase::whereBetween('expires_on', [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                    ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
                    ->where('gym_client_purchases.detail_id', '=', $this->user)
                    ->get();
                break;
            case 'big_spenders':
                $clients = GymPurchase::leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                    ->whereBetween('purchase_date', [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                    ->where('purchase_amount', '>=', 10000)
                    ->where('business_customers.detail_id', '=', $this->user)->get();

                break;
            case 'birthday':
                $clients = GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                    ->whereMonth('gym_clients.dob', '>=', $this->sDate->format('m'))
                    ->whereMonth('gym_clients.dob', '<=', $this->eDate->format('m'))
                    ->where('gym_clients.is_client',  'yes')
                    ->where('business_customers.detail_id', '=', $this->user)->get();
                break;
            case 'lost':
                $purchases = GymPurchase::whereBetween('purchase_date', [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                    ->where('detail_id', '=', $this->user)->get();
                $users     = [];
                foreach ($purchases as $purchase) {
                    if ($purchase->membership_id != null) {
                        $days = $purchase->start_date->diffInDays(Carbon::now('Asia/Kathmandu'));
                        if (($purchase->membership->duration * 30) < $days) {
                            array_push($users, $purchase->client_id);
                        }
                    }
                }
                $clients = GymClient::whereIn('id', $users)->get();

                break;
            case 'active':
                $clientIds = GymPurchase::where('expires_on','>=',now())
                    ->where('detail_id', '=', $this->user)
                    ->groupBy('client_id','membership_id')
                    ->orderBy('expires_on','desc')
                    ->pluck('client_id');

                $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.id as clientID')
                    ->whereIn('id', $clientIds)->get();
                break;
            case 'inactive':
                $activeIds = GymPurchase::where('expires_on','>=',now())
                    ->where('detail_id', '=', $this->user)
                    ->groupBy('client_id','membership_id')
                    ->pluck('client_id');

                $clientIds = GymPurchase::where('detail_id', $this->user)
                    ->whereNotIn('client_id',$activeIds)
                    ->groupBy('client_id','membership_id')
                    ->pluck('client_id');

                $clients = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender','joining_date','address', 'gym_clients.id as clientID')
                        ->whereIn('id', $clientIds)->get();
        }
        return view('gym-admin.reports.client.excel',['clients'=>$clients,'id'=>$this->id]);
    }
}
