<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\AssetService;
use App\Models\GymClient;
use App\Models\GymClientAttendance;
use App\Models\GymEnquiries;
use App\Models\GymExpense;
use App\Models\GymMembership;
use App\Models\GymMembershipPayment;
use App\Models\GymPurchase;
use App\Models\Locker;
use App\Models\LockerPayment;
use App\Models\LockerReservation;
use App\Models\MerchantNotification;
use App\Models\SetTarget;
use App\Models\Product;
use App\Models\ProductPayment;
use App\Models\ProductSales;
use App\Models\Income;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use DataTables;
use Spatie\Activitylog\Models\Activity;

class AdminGymDashboardController extends GymAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->data['dashboardMenu'] = 'active';
    }

    public function index() {

        if(!$this->data['user']->can("view_dashboard"))
        {
            return App::abort(401);
        }

        $this->data['title'] = "Dashboard";

        $now = Carbon::now('Asia/Kathmandu');
        $month = $now->month;
        $year = $now->year;
        $weekStartDate = $now->startOfWeek()->format('Y-m-d');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d');
        //total earning
        $currentLockerBalance = LockerPayment::getCurrentBalance($this->data['user']->detail_id);
        $currentMembershipBalance = GymMembershipPayment::getCurrentBalance($this->data['user']->detail_id);
        $currentProductBalance = ProductPayment::getCurrentBalance($this->data['user']->detail_id);
        $currentIncomeBalance = Income::getCurrentBalance($this->data['user']->detail_id);
        $this->data['currentBalance'] = $currentMembershipBalance + $currentProductBalance + $currentLockerBalance + $currentIncomeBalance;
        $this->data['totalExpenses'] = GymExpense::getCurrentBalance($this->data['user']->detail_id);

        //daily earning
        $dailyLockerBalance = LockerPayment::getDailySales($this->data['user']->detail_id);;
        $dailyMembershipSales = GymMembershipPayment::getDailySales($this->data['user']->detail_id);
        $dailyProductSales = ProductPayment::getDailySales($this->data['user']->detail_id);
        $dailyIncomes = Income::getDailySales($this->data['user']->detail_id);

        $this->data['dailyEarn'] = $dailyMembershipSales + $dailyProductSales + $dailyLockerBalance + $dailyIncomes;
        $this->data['dailyExpenses'] = GymExpense::getDailySales($this->data['user']->detail_id);

        //weekly earning
        $weeklyLockerBalance = LockerPayment::getWeeklySales($weekStartDate, $weekEndDate, $this->data['user']->detail_id);
        $weeklyMembershipSales = GymMembershipPayment::getWeeklySales($weekStartDate, $weekEndDate, $this->data['user']->detail_id);
        $weeklyProductSales = ProductPayment::getWeeklySales($weekStartDate, $weekEndDate, $this->data['user']->detail_id);
        $weeklyIncomes = Income::getWeeklySales($weekStartDate, $weekEndDate, $this->data['user']->detail_id);

        $this->data['weeklySales'] = $weeklyMembershipSales + $weeklyProductSales + $weeklyLockerBalance + $weeklyIncomes;
        $this->data['weeklyExpenses'] = GymExpense::getWeeklySales($weekStartDate, $weekEndDate, $this->data['user']->detail_id);

        //average monthly
        $lockerAverageMonthly = LockerPayment::getAverageMonthlySales($month, $year, $this->data['user']->detail_id);
        $membershipsAverageMonthly = GymMembershipPayment::getAverageMonthlySales($month, $year, $this->data['user']->detail_id);
        $productAverageMonthly = ProductPayment::getAverageMonthlySales($month, $year, $this->data['user']->detail_id);
        $incomeAverageMonthly = Income::getAverageMonthlySales($month, $year, $this->data['user']->detail_id);

        $this->data['averageMonthly'] = $membershipsAverageMonthly + $productAverageMonthly + $lockerAverageMonthly + $incomeAverageMonthly;
        $this->data['monthlyExpenses'] = GymExpense::getAverageMonthlySales($month, $year, $this->data['user']->detail_id);

        $targets = SetTarget::allBusinessTargets($this->data['user']->detail_id)->take(3);
        $targetStats = array();

        foreach($targets as $key => $target) {
            if($target->targetType->type == 'membership'){

                $date = Carbon::createFromFormat('Y-m-d', $target->date);
                $start_date = Carbon::createFromFormat('Y-m-d', $target->start_date);

                $users = GymPurchase::whereBetween('purchase_date', [$start_date->format('Y-m-d'), $date->format('Y-m-d')])
                    ->where('detail_id', '=', $this->data['user']->detail_id)->count();

                $target_achieve_percent  =  ($users/$target->value)*100;
                $targetStats[$key]['name'] = $target->title;
                $targetStats[$key]['percent'] = ($target_achieve_percent > 100) ? 100 : $target_achieve_percent;
            }

            if ($target->targetType->type == 'revenue') {
                $date = Carbon::createFromFormat('Y-m-d', $target->date);
                $start_date = Carbon::createFromFormat('Y-m-d', $target->start_date);

                $sales = GymMembershipPayment::leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'purchase_id')
                    ->whereBetween('payment_date', [$start_date->format('Y-m-d'),$date->format('Y-m-d')])
                    ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id)->sum('payment_amount');
                $target_achieve_percent  =  ($sales/$target->value)*100;
                $targetStats[$key]['name'] = $target->title;
                $targetStats[$key]['percent'] = ($target_achieve_percent > 100) ? 100 : $target_achieve_percent;
            }
        }

        $this->data['targets'] = $targetStats;
        $this->data['financeCharts'] = GymMembershipPayment::Select(DB::raw('SUM(payment_amount)as S, MONTH(payment_date) as M'))
            ->where('gym_membership_payments.detail_id', '=', $this->data['user']->detail_id)
            ->where(DB::raw('YEAR(payment_date)'), $year)
            ->groupBy(DB::raw('MONTH(payment_date)'))
            ->get();

        $this->data['membershipsStats'] = $this->getMembershipStats();
        $this->data['activeMembershipsStats'] = $this->getActiveMembershipStats();

        $this->data['recentClients'] = GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
            ->where('gym_clients.is_client','yes')
            ->orderBy('gym_clients.created_at', 'desc')
            ->take(3)
            ->get();

        $dt = Carbon::now('Asia/Kathmandu');
        $date = $dt->format('Y-m-d');

        $this->data['duePayments'] = GymPurchase::expiredSubscription($this->data['user']->detail_id,5);
        $this->data['dueProductPayments'] = ProductSales::dueProductPayment($this->data['user']->detail_id,5);
        $this->data['dueLockerPayments'] = LockerReservation::dueLockerPayment($this->data['user']->detail_id,5);

        $options = json_decode($this->data['gymSettings']->options, true);
        $this->data['expireSubscriptionDays'] = $options['subscription_expire_days'] ?? $this->data['gymSettings']->getOption('subscription_expire_days');
        $this->data['expiringSubscriptions'] = GymPurchase::expireSubscriptionInDays($this->data['user']->detail_id,$this->data['expireSubscriptionDays'],5);

        $this->data['expiringLockerSubscriptions'] = LockerReservation::expireReservationInDays($this->data['user']->detail_id,$this->data['expireSubscriptionDays'],5);

        $this->data['expireProductDays'] = $options['product_expire_days'] ?? $this->data['gymSettings']->getOption('product_expire_days');
        $this->data['expiringProducts'] = Product::expireProductInDays($this->data['user']->detail_id,$this->data['expireProductDays'],5);

        $this->data['totalCustomers'] = GymClient::GetClients($this->data['user']->detail_id)->count();
        $this->data['totalActiveCustomers'] = GymClient::countActiveClient($this->data['user']->detail_id);
        $this->data['totalInactiveCustomers'] = $this->data['totalCustomers'] - $this->data['totalActiveCustomers'];
        $this->data['totalLockerAvailable'] = Locker::businessLockers($this->data['user']->detail_id)->where('status','available')
            ->count();
        $this->data['totalTemplate'] = Template::businessTotalTemplate($this->data['user']->detail_id);
        $this->data['totalSMSCredit'] = getSMSCreditBalance($this->data['user']->detail_id);

        $this->data['monthlyVisitors'] = GymEnquiries::monthlyEnquiries($this->data['user']->detail_id, $dt->month);
        $this->data['monthlyCustomers'] = GymClient::monthlyClients($this->data['user']->detail_id, $dt->month);
        $this->data['todayAttendance'] = GymClientAttendance::attendanceByDateCount($date, $this->data['user']->detail_id);
        $this->data['asset_services'] = AssetService::whereBetween('next_service_date',
                        [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                    )->where('detail_id',$this->data['user']->detail_id)->get();
        return View::make('gym-admin.dashboard.index', $this->data);
    }

    /*
	 * mark all notification as read
	 * */

    public function markRead() {
        if (request()->ajax()) {
            MerchantNotification::where('detail_id', '=', $this->data['user']->detail_id)->update(["read_status" => "read"]);
            return Reply::success('Notifications marked as read.');
        }

    }

    public function getMembershipStats() {
        $purchases = GymPurchase::where('detail_id', '=', $this->data['user']->detail_id)->get();
        $memberships = array();

        foreach ($purchases as $purchase) {
            if($purchase->membership_id != null)
            {
                array_push($memberships, $purchase->membership_id);
            }
        }

        $memberships = array_count_values($memberships);

        $memberships_id = array_keys($memberships);
        $data = GymMembership::whereIn('id', $memberships_id)->get()->toArray();

        foreach ($data as $key => $membership) {
            $data[$key]['total'] = $memberships[$membership['id']];
        }

        return $data;
    }

    public function getActiveMembershipStats() {
        $purchases = GymPurchase::where('detail_id', '=', $this->data['user']->detail_id)
                ->where('expires_on','>=',now())->get();
        $memberships = array();

        foreach ($purchases as $purchase) {
            if($purchase->membership_id != null)
            {
                array_push($memberships, $purchase->membership_id);
            }
        }

        $memberships = array_count_values($memberships);

        $memberships_id = array_keys($memberships);
        $data = GymMembership::whereIn('id', $memberships_id)->get()->toArray();

        foreach ($data as $key => $membership) {
            $data[$key]['total'] = $memberships[$membership['id']];
        }

        return $data;
    }


    public function activityLog(){
        if(!$this->data['user']->can("view_activity_log"))
        {
            return App::abort(401);
        }
        $this->data['title'] = "Activity Log";
        $this->data['indexActivityLog'] = "active";
        $startDate = Carbon::now()->subDays(1);
        $endDate = Carbon::now();
        $this->data['logs'] = Activity::whereBetween('created_at',[$startDate,$endDate])
                                    ->where('detail_id',$this->data['user']->detail_id)->latest()->get();
        return view('gym-admin.dashboard.activity_log',$this->data);
    }
}
