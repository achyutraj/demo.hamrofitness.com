<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
//            "view_admin_dashboard",
//            "view_dashboard",
//            "view_monthly_visits",
//            "send_promotion",
//            "manage_roles_permissions",
//            "view_gympromotion",
//            "view_gymtarget",
//            "send_notification",
//            "diet_plan",
//            "training_plan",
//            "class_schedule",
//            "task",
//            "add_product_payment",
//            "edit_product_payment",
//            "delete_product_payment",
//            "view_product_payment",
//            "bank_report",
//            "product_report",
//            "view_attendance",
//            "employee_attendance",
//            "view_enquiry",
//            "add_enquiry",
//            "edit_enquiry",
//            "delete_enquiry",
//
//            "view_customers",
//            "add_customers",
//            "edit_customers",
//            "delete_customers",
//
//            "view_subscriptions",
//            "add_subscriptions",
//            "edit_subscriptions",
//            "delete_subscriptions",
//
//            "view_payments",
//            "add_payments",
//            "edit_payments",
//            "delete_payments",
//
//            "view_due_payments",
//            "add_due_payments",
//            "edit_due_payments",
//            "delete_due_payments",
//
//            "view_suppliers",
//            "add_suppliers",
//            "edit_suppliers",
//            "delete_suppliers",
//
//            "expense",
//            "view_bank_ledger",
//            "view_invoice",
//            "create_invoice",
//            "delete_invoice",
//            "view_employs",
//            "add_employ",
//            "edit_employ",
//            "delete_employ",
//            "add_attendance",
//            "view_payrolls",
//            "add_payroll",
//            "edit_payroll",
//            "delete_payroll",
//            "view_leaves",
//            "add_leave",
//            "edit_leave",
//            "delete_leave",
//            "view_targets",
//            "add_target",
//            "edit_target",
//            "delete_target",
//
//            "view_target_report",
//            "view_client_report",
//            "view_booking_report",
//            "view_finance_report",
//            "view_attendance_report",
//            "view_enquiry_report",
//            "balance_report",
//            "view_previous_promotions",
//            "message",
//
//            "view_sells",
//            "add_sells",
//            "edit_sells",
//            "delete_sells",
//
//            "view_products",
//            "add_products",
//            "edit_products",
//            "delete_products",
//
//            "view_membership",
//            "add_membership",
//            "edit_membership",
//            "delete_membership",
//
//            "view_bank_account",
//            "add_bank_account",
//            "edit_bank_account",
//            "delete_bank_account",
//            "my_gym",
//            "view_banks_and_branches",
//
//            "view_bank",
//            "add_bank",
//            "edit_bank",
//            "delete_bank",
//
//            "view_bank_branch",
//            "add_bank_branch",
//            "edit_bank_branch",
//            "delete_bank_branch",
//
//            "update_profile",
//            "update_settings","view_settings",
//            "manage_permissions",
//            "download_backup",
//            "view_dashboard_data","view_customer_attendance",
//            "view_assets","add_assets","edit_assets","delete_assets","generate_i_cards","mobile_app",

            //new permission
//            "view_lockers", "add_lockers", "edit_lockers", "delete_lockers",
//            "view_reservations", "add_reservations", "edit_reservations", "delete_reservations",
//            "body_measurement","body_progress_tracker","subscription_extend_features",
//            "templates",
//            "show_total_earning","show_weekly_earning","show_purchase_earning","show_monthly_earning",
//            "show_finance_bar","show_membership_chart","view_software_updates",
//            "view_feedback","dashboard_subscription_expire","dashboard_product_expire",

        //version 2.2
            // "reservation_report", "income","expense_report","income_report",
            // "manage_device","add_biometrics","profit_loss_report",
            // "view_redeems","add_redeems","edit_redeems","delete_redeems",
            // "view_tutorials","add_tutorials","edit_tutorials", "delete_tutorials",
            //  "show_total_expenses","show_weekly_expenses","show_purchase_expenses","show_monthly_expenses",
            "view_activity_log"
        ];

        foreach($permissions as $perm):
            $displayName = ucwords(str_replace('_',' ',$perm));
            $insert = [
                "name" => $perm,
                "display_name" => $displayName,
                "guard_name" => 'web'
            ];
            Permission::create($insert);
        endforeach;

//        $admin = new Role;
//        $admin->name = 'Super Admin';
//        $admin->guard_name = 'web';
//        $admin->save();
//        $admin->givePermissionTo(Permission::all());
//
//        $branch = new Role;
//        $branch->name = 'Branch Manager';
//        $branch->guard_name = 'web';
//        $branch->save();
//        $admin->givePermissionTo(Permission::all());

    }
}
