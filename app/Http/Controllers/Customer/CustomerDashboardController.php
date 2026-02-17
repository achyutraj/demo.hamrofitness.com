<?php

namespace App\Http\Controllers\Customer;

use App\Classes\Reply;
use App\Models\GymClient;
use App\Models\DietPlan;
use App\Models\ClassSchedule;
use App\Models\GymMembership;
use App\Models\LockerReservation;
use App\Models\TrainingPlan;
use App\Models\GymMembershipPayment;
use App\Models\GymPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerDashboardController extends CustomerBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = 'HamroFitness | Customer Dashboard';
        $this->data['dashboardMenu'] = 'active';
        $this->data['totalAmountPaid'] = GymMembershipPayment::leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'purchase_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_membership_payments.user_id')
            ->where('gym_clients.id', '=', $this->data['customerValues']->id)
            ->sum('payment_amount');
        $this->data['totalSubscriptions'] = $this->data['customerValues']->subscriptions()->count();
        $this->data['expiringSubscriptions'] = GymPurchase::select('first_name', 'middle_name','last_name','gym_client_purchases.start_date','gym_client_purchases.expires_on', 'gym_memberships.title as membership', 'gym_client_purchases.id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.detail_id', '=', $this->data['customerValues']->detail_id)
            ->where('gym_clients.id', '=', $this->data['customerValues']->id)
            ->where('gym_client_purchases.expires_on', '<=', Carbon::today()->addDays(45))
            ->where('gym_client_purchases.expires_on', '>=', Carbon::today())
            ->orderBy('gym_client_purchases.expires_on', 'asc')
            ->get();
        $this->data['duePayments'] = GymPurchase::select('first_name', 'middle_name','last_name','gym_client_purchases.amount_to_be_paid as amount_to_be_paid','gym_client_purchases.paid_amount as paid', 'next_payment_date as due_date', 'gym_memberships.title as membership', 'gym_client_purchases.id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.detail_id', '=', $this->data['customerValues']->detail_id)
            ->where('gym_clients.id', '=', $this->data['customerValues']->id)
            ->where('gym_client_purchases.status', '!=', 'pending')
            ->where('gym_client_purchases.payment_required','yes')
            ->get();
        $this->data['totalDueAmount'] = GymPurchase::select(DB::raw('SUM(amount_to_be_paid) - SUM(paid_amount) as totalDueAmount'))
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.detail_id', '=', $this->data['customerValues']->detail_id)
            ->where('gym_clients.id', '=', $this->data['customerValues']->id)
            ->where('gym_client_purchases.status', '!=', 'pending')
            ->where('gym_client_purchases.payment_required', 'yes')
            ->value('totalDueAmount');
        $this->data['paymentCharts'] = GymMembershipPayment::Select(DB::raw('SUM(payment_amount)as S, MONTH(payment_date) as M'))
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'user_id')
            ->where('gym_clients.id', '=', $this->data['customerValues']->id)
            ->where('gym_membership_payments.detail_id', '=', $this->data['customerValues']->detail_id)
            ->where(DB::raw('YEAR(payment_date)'), Carbon::today()->year)
            ->groupBy(DB::raw('MONTH(payment_date)'))
            ->get();

        $options = json_decode($this->data['gymSettings']->options, true);
        $this->data['expireSubscriptionDays'] = $options['subscription_expire_days'] ?? $this->data['gymSettings']->getOption('subscription_expire_days');
        $this->data['totalReservations'] = $this->data['customerValues']->reservations()->count();
        $this->data['expiringReservations'] = LockerReservation::where('client_id', '=', $this->data['customerValues']->id)
                ->where(['status'=>'active','payment_required'=>'yes'])
                ->expireReservationInDays($this->data['customerValues']->detail_id,$this->data['expireSubscriptionDays'],5);

        $this->data['dueReservationPayments'] = LockerReservation::where('client_id', $this->data['customerValues']->id)
            ->where(['status'=>'active','payment_required'=>'yes'])->get();

        $this->data['default_diet_plan'] = DietPlan::where('branch_id',$this->data['customerValues']->detail_id)->where('client_id',null)->get();
        $this->data['client_diet_plan'] = DietPlan::where('client_id',$this->data['customerValues']->id)->get();

        $this->data['default_training_plan'] = TrainingPlan::where('branch_id',$this->data['customerValues']->detail_id)->where('client_id',null)->get();
        $this->data['client_training_plan'] = TrainingPlan::where('client_id',$this->data['customerValues']->id)->get();

        $this->data['class_schedule'] = ClassSchedule::where('has_client',0)->where('detail_id',$this->data['customerValues']->detail_id)->get();
        $this->data['client_class_schedule'] = ClassSchedule::where('has_client',1)->where('detail_id',$this->data['customerValues']->detail_id)
                                                ->whereHas('clients',function ($query){
                                                    $query->where('client_id',$this->data['customerValues']->id);
                                                })
                                                ->get();

        return view('customer-app.dashboard.index', $this->data);
    }

    public function markRead()
    {
        $user = GymClient::find($this->data['customerValues']->id);
        $user->unreadNotifications()->update(['read_at' => Carbon::now()]);

        return Reply::success('Notifications marked as read.');
    }
}
