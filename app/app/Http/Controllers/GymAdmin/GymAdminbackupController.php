<?php

namespace App\Http\Controllers\GymAdmin;

use App\Exports\Backups\AttendanceExport;
use App\Exports\Backups\DueExport;
use App\Exports\Backups\EnquiryExport;
use App\Exports\Backups\GymClientsExport;
use App\Exports\Backups\MembershipExport;
use App\Exports\Backups\PaymentExport;
use App\Exports\Backups\SubscriptionExport;
use App\Exports\Backups\ProductExport;
use App\Exports\Backups\LockerExport;
use App\Exports\Backups\ReservationExport;
use App\Exports\Backups\LockerDueExport;
use App\Exports\Backups\LockerPaymentExport;
use App\Exports\Backups\ProductPaymentExport;
use App\Exports\Backups\ProductSaleExport;
use App\Exports\Backups\ProductDueExport;
use App\Exports\Backups\ActivityLogExport;
use App\Exports\Backups\ExpenseExport;
use App\Exports\Backups\OtherIncomeExport;

use App\Models\GymClient;
use App\Models\GymClientAttendance;
use App\Models\EmployAttendance;
use App\Models\GymEnquiries;
use App\Models\GymMembership;
use App\Models\GymMembershipPayment;
use App\Models\GymPurchase;
use App\Models\Product;
use App\Models\Locker;
use App\Models\LockerReservation;
use App\Models\LockerPayment;
use App\Models\ProductPayment;
use App\Models\ProductSales;
use App\Models\GymExpense;
use App\Models\Income;

use Illuminate\Support\Facades\App;
use Excel;

class GymAdminbackupController extends GymAdminBaseController
{
    public function index()
    {
        if (!$this->data['user']->can("download_backup")) {
            return App::abort(401);
        }

        $this->data['title'] = "Take Backup";
        return view('gym-admin.backup.backup', $this->data);
    }

    public function getbackup($type)
    {
        $fileName = $type.'-backup.xls';
        if ($type == 'customer') {
            $customers = GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                ->where('gym_clients.is_client','yes')
                ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                ->get();
            if ($customers->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new GymClientsExport($this->data['user']->detail_id), $fileName);
            }
        } elseif ($type == 'subscriptions') {
            $subscription = GymPurchase::join('gym_clients', 'gym_clients.id', '=', 'client_id')
                ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
                ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id)
                ->get();
            if ($subscription->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new SubscriptionExport($this->data['user']->detail_id), $fileName);
            }
        } elseif ($type == 'membership') {
            $membership = GymMembership::where('detail_id', '=', $this->data['user']->detail_id)
                ->get();
            if ($membership->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new MembershipExport($this->data['user']->detail_id), $fileName);
            }
        } elseif ($type == 'attendance') {
            $attendance = GymClientAttendance::select('gym_client_attendances.status','gym_clients.first_name','gym_clients.middle_name','gym_clients.last_name','gym_clients.gender','gym_clients.mobile', 'gym_clients.email')
                ->selectRaw("DATE_FORMAT(check_in, '%d-%M-%y %h:%i %a ') as check_in,DATE_FORMAT(check_out, '%d-%M-%y %h:%i %a ') as check_out ")
                ->join('gym_clients', 'gym_clients.id', '=', 'gym_client_attendances.client_id')
                ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                ->where('gym_clients.is_client','yes')
                ->where('business_customers.detail_id', $this->data['user']->detail_id)
                ->orderBy('check_in', 'desc')
                ->get();
            if ($attendance->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new AttendanceExport($this->data['user']->detail_id,'client'), $fileName);
            }
        } elseif ($type == 'employeeAttendance') {
            if($this->data['common_details']->has_device == 1){
                $attendanceType = 'bio_employee';
                $attendance = GymClientAttendance::select('gym_client_attendances.status','gym_clients.first_name','gym_clients.middle_name','gym_clients.last_name','gym_clients.gender','gym_clients.mobile', 'gym_clients.email')
                    ->selectRaw("DATE_FORMAT(check_in, '%d-%M-%y %h:%i %a ') as check_in,DATE_FORMAT(check_out, '%d-%M-%y %h:%i %a ') as check_out ")
                    ->join('gym_clients', 'gym_clients.id', '=', 'gym_client_attendances.client_id')
                    ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                    ->where('gym_clients.is_client','no')
                    ->where('business_customers.detail_id', $this->data['user']->detail_id)
                    ->orderBy('check_in', 'desc')
                    ->get();
            }else{
                $attendanceType = 'employee';
                $attendance = EmployAttendance::select('employ_attendances.status','employes.first_name','employes.middle_name','employes.last_name','employes.gender','employes.mobile', 'employes.email')
                    ->selectRaw("DATE_FORMAT(check_in, '%d-%M-%y %h:%i %a ') as check_in,DATE_FORMAT(check_out, '%d-%M-%y %h:%i %a ') as check_out ")
                    ->join('employes', 'employes.id', '=', 'employ_attendances.client_id')
                    ->where('employes.detail_id', $this->data['user']->detail_id)
                    ->orderBy('check_in', 'desc')
                    ->get();
            }
            if ($attendance->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new AttendanceExport($this->data['user']->detail_id,$attendanceType), $fileName);
            }
        } elseif ($type == 'enquiries') {
            $enquiry = GymEnquiries::where('detail_id', '=', $this->data['user']->detail_id)
                ->get();
            if ($enquiry->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new EnquiryExport($this->data['user']->detail_id), $fileName);
            }

        } elseif ($type == 'payments') {
            $payments = GymMembershipPayment::with('purchase','client')
                ->where('gym_membership_payments.detail_id', '=', $this->data['user']->detail_id)
                ->get();
            if ($payments->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new PaymentExport($this->data['user']->detail_id), $fileName);
            }
        }elseif ($type == 'dues') {
            $dues = GymPurchase::leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.detail_id', $this->data['user']->detail_id)
            ->where('gym_client_purchases.payment_required','yes')
            ->get();
            if ($dues->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new DueExport($this->data['user']->detail_id), $fileName);
            }
        }else if ($type == 'lockers') {
            $lockers = Locker::where('detail_id', '=', $this->data['user']->detail_id)
                ->get();
            if ($lockers->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new LockerExport($this->data['user']->detail_id), $fileName);
            }
        }else if ($type == 'products') {
            $products = Product::where('branch_id', '=', $this->data['user']->detail_id)
                ->get();
            if ($products->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new ProductExport($this->data['user']->detail_id), $fileName);
            }
        }elseif ($type == 'reservations') {
            $reservation = LockerReservation::leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
                            ->leftJoin('lockers', 'lockers.id', '=', 'locker_id')
                            ->where('locker_reservations.detail_id', '=', $this->data['user']->detail_id)
                            ->get();
            if ($reservation->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new ReservationExport($this->data['user']->detail_id), $fileName);
            }
        }elseif ($type == 'lockerPayments') {
            $payments = LockerPayment::with('reservation','client')
                        ->where('locker_payments.detail_id', '=',$this->data['user']->detail_id)
                        ->get();
            if ($payments->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new LockerPaymentExport($this->data['user']->detail_id), $fileName);
            }
        }elseif ($type == 'productPayments') {
            $payments = ProductPayment::with('product_sale','client')
                        ->where('product_payments.branch_id', '=',$this->data['user']->detail_id)
                        ->get();
            if ($payments->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new ProductPaymentExport($this->data['user']->detail_id), $fileName);
            }
        }elseif ($type == 'productDues') {
            $dues = ProductSales::leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
                    ->where('product_sales.branch_id', $this->data['user']->detail_id)
                    ->where('product_sales.payment_required','yes')
                    ->get();
            if ($dues->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new ProductDueExport($this->data['user']->detail_id), $fileName);
            }
        }elseif ($type == 'productSales') {
            $subscription = ProductSales::join('gym_clients', 'gym_clients.id', '=', 'client_id')
                ->where('product_sales.branch_id', '=', $this->data['user']->detail_id)
                ->get();
            if ($subscription->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new ProductSaleExport($this->data['user']->detail_id), $fileName);
            }
        }elseif ($type == 'lockerDues') {
            $dues = LockerReservation::leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('lockers', 'lockers.id', '=', 'locker_id')
            ->where('locker_reservations.detail_id', $this->data['user']->detail_id)
            ->where('locker_reservations.payment_required','yes')
            ->get();
            if ($dues->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new LockerDueExport($this->data['user']->detail_id), $fileName);
            }
        }elseif ($type == 'activity-log') {
            return Excel::download(new ActivityLogExport($this->data['user']->detail_id), $fileName);
        }elseif ($type == 'expenses') {
            $expenses = GymExpense::where('detail_id', '=',$this->data['user']->detail_id)
                        ->get();
            if ($expenses->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new ExpenseExport($this->data['user']->detail_id), $fileName);
            }
        }elseif ($type == 'other_incomes') {
            $incomes = Income::where('detail_id', '=',$this->data['user']->detail_id)
                        ->get();
            if ($incomes->isEmpty()) {
                return redirect()->back()->with('message', 'No Data Available');
            } else {
                return Excel::download(new OtherIncomeExport($this->data['user']->detail_id), $fileName);
            }
        }

    }


}
