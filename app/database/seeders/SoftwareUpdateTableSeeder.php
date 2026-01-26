<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoftwareUpdate;
use App\Models\Category;

class SoftwareUpdateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $softwareUpdates = [
//            'Major functionality Updates' => [
//                'We have done major changes to the core functionality. Here are the updates:-<br><ul><li>Customer height will now be in feet and inches. Earlier it was in cm.</li><li>Age of customer will be calculated automatically.</li><li>Discount will be calculated automatically when you add subscription for customer.</li><li>Track who generated the invoice.</li></ul>',
//                '2016-07-23',
//            ],
//            'Renew Customer Subscriptions' => [
//                'You can now renew your customer subscriptions directly from the subscription table. The major advantage of this feature is that you will not have to re-enter the customer details.',
//                '2016-07-24',
//            ],
//            'New Enquiry Form' => [
//                'We have completely revamped the enquiry form for your business. New enquiry form will allow you to manage every new&nbsp;follow up of a customer separately. You can also track who talked to your customer in &nbsp;previous visit.<br><br>New enquiry form allows you to register your customer directly from the enquiry table. You will not have the enter the customer data again to register.',
//                '2016-07-26',
//            ],
//            'Faster payments' => [
//                'You can now add payments more faster for your customers. You can now go to due payments or customer subscriptions table and add payment directly from there.',
//                '2016-07-22'
//            ],
//            'New Quick Links Menu' => [
//                'We have created a quick links menu in top header with links which you may frequently use. Let us know if you need other links in that menu.',
//                '2016-08-05',
//            ],
//            'Faster Customer Registration' => [
//                'To improve your experience of registering the customer we have made few fields optional like height and weight.<br><br>This will make your customer registration process faster.',
//                '2016-08-08',
//            ],
//            'Create users with limited permissions' => [
//                'With this feature you can create new users with limited permissions to use the software. Click on User permissions under your profile icon dropdown to start.',
//                '2016-08-10',
//            ],
//            'Email Promotions' => [
//                'You can now send the email promotions/offers&nbsp;to your customers. Go to <b>Promotions -&gt; Email Promotion</b> in menu to try it now.',
//                '2016-10-10',
//            ],
//            'Send Subscription Reminder' => [
//                'We have introduced a new feature by which you can see the list of clients whose subscription is expiring in next 45 days on the dashboard. You can send email &amp; SMS&nbsp;reminder to these clients.<br><br><span>You can also do the same from the manage subscription section.</span>',
//                '2017-05-30',
//            ],
//            'Added mobile and email field in invoice form' => [
//                'We have added email and invoice fields in invoice generation form. By this, you will not have to re-enter the email again when sending the invoice via email.',
//                '2017-06-10',
//            ],
//            '3 New Features Added' => [
//                'We are here with set of new features for you<br><ul><li><b>Expense Management -&nbsp;</b>You can now add all your gym expenses in manage expenses section.</li><li><b>Task Management -&nbsp;</b>You can now add the tasks for you, set deadlines and get a reminder for the task. Check manage tasks section.</li><li><b>Expense vs Income Report -&nbsp;</b>We have added a new report section to analyze the income and expenses for your business. Check Balance reports in the Reports section.</li></ul>',
//                '2017-09-01',
//            ],
            //our customization
            'Version Update' => [
                'New Version Update',
                '2021-06-09',
            ],
            '2 New Features Added' => [
                'We are here with set of new features for you<br><ul><li><b>Payment report add in Balance Report -&nbsp;</b>You can now add all your gym expenses in manage expenses section.</li><li><b>Generate Customer BarCode and QRcode -&nbsp;</b>You can now generate customer barcode and qr code. Check RightSide Admin section (above User Permissions).</li><li></li></ul><b>Error Fix -&nbsp;</b>We have fixed error in customer subscription and level activity.',
                '2022-05-30',
            ],
            'New Payment Gateway Integration' => [
                'We are excited to announce that our software now supports online payments through <b>Esewa</b> and <b>Khalti</b>. This will allow your customers to pay for their purchases or services online, making the payment process faster and more convenient.',
                '2022-06-23',
            ],
            'Mobile Apps Develop' => [
                'We are pleased to announce that our mobile app has been lunched.',
                '2018-07-26',
            ],
            'Report Update' => [
                'Now you can generate client or employee wise report in attendance.',
                '2022-10-31',
            ],
            'New Year Updates' => [
                'We are here with set of new features for you<br><ul><li><b>SMS Notification -&nbsp;</b>This feature allow you to create and save templates for SMS messages, making it easier and faster to send consistent, professional notifications to your customers</li><li><b>Display Deleted Subscription -&nbsp;</b>You can view remove subscription list and restore it back.</li></ul><br><b>Updated -&nbsp;</b><br>Customer Register form has been updated by adding Blood Group and Emergency Contact.',
                '2023-01-01',
            ],
            'Upcoming Features' => [
                'We are going to add a set of new features for you.<br><ul><li><b>Locker Module -&nbsp;</b>You can now manage and track the usage of lockers at your gym.</li><li><b>Body Measurement History -&nbsp;</b>You can keep customer measurement history and track down customer goal for fitness.</li><li><b>Customer Feedback -&nbsp;</b>Customer are able to provide their own review towards gym fitness.</li></ul>',
                '2023-02-01',
            ],
        ];

        $category = Category::first();
        foreach($softwareUpdates as $softwareUpdate => $data) {
            SoftwareUpdate::create([
                'category_id' => $category->id,
                'title' => $softwareUpdate,
                'details' => $data[0],
                'date' => $data[1],
            ]);
        }
    }
}
