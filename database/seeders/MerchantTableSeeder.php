<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merchant;
use App\Models\Common;
use App\Models\MerchantBusiness;
use App\Models\BusinessBranch;
use App\Models\BusinessCategory;
use App\Models\Category;
use App\Models\GymSetting;
use App\Models\Currency;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class MerchantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Merchant::create([
            'username'   => 'admin',
            'password'   => \Illuminate\Support\Facades\Hash::make('123456'),
            'first_name' => 'Admin',
            'mobile'     => '9843650510',
            'email'      => 'admin@admin.com',
            'is_admin'   => 1,
        ]);

        // branch 1
        Common::create([
            'title'               => 'Demo1 Fitness - Lalitpur',
            'address'             => 'Pulchowk, Lalitpur',
            'owner_incharge_name' => 'Milan Shrestha',
            'phone'               => '1234567890',
            'email'               => 'milan@gmail.com',

        ]);

        $merchant             = Merchant::where('email', '=', 'admin@admin.com')->first();
        $lalitpur_main_branch = Common::where('email', '=', 'milan@gmail.com')->first();
        $category             = Category::first();
        $currency             = Currency::where('id', 11)->first();
        $permissions          = Permission::all();

        MerchantBusiness::create([
            'merchant_id' => $merchant->id,
            'detail_id'   => $lalitpur_main_branch->id,
        ]);
        $merchant->assignRole('Super Admin');

        // branch 1
        BusinessBranch::create([
            'detail_id'           => $lalitpur_main_branch->id,
            'owner_incharge_name' => $lalitpur_main_branch->owner_incharge_name,
            'address'             => $lalitpur_main_branch->address,
            'phone'               => $lalitpur_main_branch->phone,
        ]);

        BusinessCategory::create([
            'category_id' => $category->id,
            'detail_id'   => $lalitpur_main_branch->id,
        ]);

        GymSetting::create([
            'detail_id'       => $lalitpur_main_branch->id,
            'currency_id'     => $currency->id,
            'email_status'    => 'enabled',
            'mail_driver'     => 'smtp',
            'mail_port'       => '465',
            'mail_host'       => 'smtp.mailgun.org',
            'mail_username'   => 'postmaster@fitness.hamrosoftware.com',
            'mail_password'   => '7cfbc581c354999467df19e194f5f50f-c322068c-2517186d',
            'mail_name'       => 'HamroFitness',
            'mail_email'      => 'admin@hamrosoftware.com',
            'mail_encryption' => 'ssl',
            'sms_status'      => 'enabled',
            'sms_sender_id'   => 'SMS',
            'sms_username'    => 'iniclient',
            'sms_password'    => 'nepal123',
            'about'           => 'HamroFitness: Gym Management System',
            'contact_mail'    => 'admin@fitnessplus.com',
        ]);

        Merchant::create([
            'username'   => 'branchadmin',
            'password'   => Hash::make('123456'),
            'first_name' => 'Milan',
            'last_name'  => 'Shrestha',
            'gender'     => 'Male',
            'mobile'     => '9843650517',
            'email'      => 'ram@gmail.com',
            'detail_id'       => $lalitpur_main_branch->id,
        ]);

        $lalitpur_main_branch_merchant = Merchant::where('username', '=', 'branchadmin')->first();

        MerchantBusiness::create([
            'merchant_id' => $lalitpur_main_branch_merchant->id,
            'detail_id'   => $lalitpur_main_branch->id,
        ]);

        $lalitpur_main_branch_merchant->assignRole('Branch Manager');
    }
}
