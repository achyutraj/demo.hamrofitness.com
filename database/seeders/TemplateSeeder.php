<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = [
            [
                'detail_id'    => 1,
                'name'          => 'Customer Registration',
                'type'          => 'registration',
                'message'       => 'Hi {first_name} {middle_name} {last_name},
                                  Welcome to {company}! This message is an automated reply to your User Access request. Login to your User panel by using the details below:
                                  {login_url}
                                  Email: {email}
                                  Password: {password}',
                'status'        => true,
            ],[
                'detail_id'    => 1,
                'name'          => 'Customer Payment',
                'type'          => 'payment',
                'message'       => 'Hi {first_name} {last_name},
                                    Your payment on {membership} NPR {amount} has been done successfully.
                                    Thank You!
                                  Regards: {company}',
                'status'        => true,
            ],[
                'detail_id'    => 1,
                'name'          => 'Membership Due',
                'type'          => 'due',
                'message'       => 'Hi {first_name} {last_name},
                                    Your {membership} will be expired on {expired_date}.
                                  Thank You!
                                  Regards: {company}',
                'status'        => true,
            ],[
                'detail_id'    => 1,
                'name'          => 'Membership Due Payment Date',
                'type'          => 'due_payment',
                'message'       => 'Hi {first_name} {last_name},
                                    Your {membership} due date is upto {due_date}.
                                  Regards: {company}',
                'status'        => true,
            ],
            [
                'detail_id'    => 1,
                'name'          => 'Membership Extend',
                'type'          => 'extend',
                'message'       => 'Hi {first_name},
                                    Your {membership} has been extended upto {day} days.
                                    Thank You!
                                  Regards: {company}',
                'status'        => true,
            ],[
                'detail_id'    => 1,
                'name'          => 'Membership Renew',
                'type'          => 'renew',
                'message'       => 'Hi {first_name},
                                    Your {membership} has been renew.
                                    Thank You!
                                  Regards: {company}',
                'status'        => true,
            ],[
                'detail_id'    => 1,
                'name'          => 'Membership Expire',
                'type'          => 'expire',
                'message'       => 'Hi {first_name} ,
                                  Your {membership} has been expired. Please Renew .
                                  Regards: {company}',
                'status'        => true,
            ],
            [
                'detail_id'    => 1,
                'name'          => 'Customer Birthday',
                'type'          => 'birthday',
                'message'       => 'Happy Birthday to {first_name},
                                    Regards: {company}',
                'status'        => true,
            ],[
                'detail_id'    => 1,
                'name'          => 'Customer Anniversary',
                'type'          => 'anniversary',
                'message'       => 'Happy Anniversary to {first_name},
                                    Regards: {company}',
                'status'        => true,
            ],
        ];
        foreach ($templates as $template) {
            Template::create($template);
        }
    }
}
