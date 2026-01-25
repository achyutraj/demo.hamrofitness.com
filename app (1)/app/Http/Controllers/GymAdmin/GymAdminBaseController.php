<?php

namespace App\Http\Controllers\GymAdmin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Mail\Notification;
use App\Models\BusinessBranch;
use App\Models\Common;
use App\Models\GymClient;
use App\Models\GymMembership;
use App\Models\GymMembershipPayment;
use App\Models\GymMerchantTask;
use App\Models\GymPurchase;
use App\Models\GymSetting;
use App\Models\MerchantBusiness;
use App\Models\MerchantNotification;
use App\Models\MerchantPromotionDatabase;
use App\Models\SoftwareUpdate;
use App\Traits\FileSystemSettingTrait;
use App\Traits\SmsSettingsTrait;
use App\Traits\SmtpSettingsTrait;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class GymAdminBaseController extends Controller
{
    use SmtpSettingsTrait, FileSystemSettingTrait, SmsSettingsTrait;
    public $data = [];
    public $email = [];

    public function __construct()
    {
        $this->data['user'] = null;
        $this->middleware(function ($request, $next) {
            if (Auth::guard('merchant')->check()) {
                $this->data['user'] = Auth::guard('merchant')->user();
            }

            if (Session::has('business_id')) {
                $businessID                     = Session::get('business_id');
                $this->data['user']->detail_id  = $businessID;
                $this->data['businessBranch']   = BusinessBranch::businessBranches($businessID);
                $this->data['merchantBusiness'] = MerchantBusiness::merchantBusinessDetails($businessID);
            } else {
                // Assign default business
                if ($this->data['user']) {
                    $business                       = MerchantBusiness::where('merchant_id', '=', $this->data['user']->id)->first();
                    $this->data['user']->detail_id  = $business->detail_id;
                    $this->data['businessBranch']   = BusinessBranch::businessBranches($business->detail_id);
                    $this->data['merchantBusiness'] = MerchantBusiness::findByMerchant($this->data['user']->id);
                }
            }

            $this->data['common_details'] = Common::where('id', '=', $this->data['user']->detail_id)->first();

            $this->data['gymSettings'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);
            $this->data['options'] = json_decode($this->data['gymSettings']->options,true);

            //Mail credentials append to env
            $this->setMailConfigs();
            // File storage credentials append to env
            $this->setFileSystemConfigs();

            $this->data['gymSwUpdates'] = SoftwareUpdate::orderBy('id', 'desc')->first();

            /*get notifications*/
            $this->data['notifications'] = MerchantNotification::where('detail_id', '=', $this->data['user']->detail_id)
                ->where('read_status', 'unread')
                ->orderBy('id', 'desc')
                ->get();

            $this->data['unreadNotifications'] = MerchantNotification::where('detail_id', '=', $this->data['user']->detail_id)
                ->where('read_status', '=', 'unread')
                ->count();

            $this->data['taskReminder'] = GymMerchantTask::where('merchant_id', $this->data['user']->id)
                ->where('status', '=', 'pending')
                ->count();

            $this->data['tasks'] = GymMerchantTask::where('merchant_id', $this->data['user']->id)
                ->where('status', '=', 'pending')->orderBy('id', 'desc')->get();

            $this->data['todayTasks'] = GymMerchantTask::where('merchant_id', $this->data['user']->id)
                ->where('status', '=', 'pending')->where('reminder_date',Carbon::today())->orderBy('id', 'desc')->get();
            /*account setup progress*/
            $this->data['completedItemsRequired'] = 6;
            $this->data['completedItems']         = 0;

            if (trim($this->data['user']->username) != "") {
                $this->data['completedItems'] = $this->data['completedItems'] + 1;
            }

            if (trim($this->data['user']->first_name) != "" && trim($this->data['user']->last_name) != "" && trim($this->data['user']->mobile) != "") {
                $this->data['completedItems'] = $this->data['completedItems'] + 1;
            }


            $this->data['memberships'] = GymMembership::membershipByBusiness($this->data['user']->detail_id);

            if (count($this->data['memberships']) > 0) {
                $this->data['completedItems'] = $this->data['completedItems'] + 1;
            }

            $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->count();

            if ($this->data['clients'] > 0) {
                $this->data['completedItems'] = $this->data['completedItems'] + 1;
            }

            $this->data['subscriptions'] = GymPurchase::purchaseByBusiness($this->data['user']->detail_id);

            if (count($this->data['subscriptions']) > 0) {
                $this->data['completedItems'] = $this->data['completedItems'] + 1;
            }

            $this->data['payments'] = GymMembershipPayment::paymentByBusiness($this->data['user']->detail_id);

            if (count($this->data['payments']) > 0) {
                $this->data['completedItems'] = $this->data['completedItems'] + 1;
            }

            $this->data['branches'] = BusinessBranch::select('common_details.id', 'common_details.title', 'business_branches.owner_incharge_name')
                ->leftJoin('common_details', 'common_details.id', '=', 'business_branches.detail_id')
                ->orderBy('common_details.title','asc')->get();

            $this->data['todayBirthDay'] = GymClient::todayClientBirthday($this->data['user']->detail_id);
            $this->data['todayExpireSubscription'] = GymPurchase::where('detail_id',$this->data['user']->detail_id)->whereDate('expires_on',today())->get();
            $this->data['todayAnniversary'] = GymClient::todayClientAnniversary($this->data['user']->detail_id);

            return $next($request);
        });

        // Default title
        $this->data['title'] = "Dashboard";

        /*detect device type*/
        $agent                   = new Agent();
        $this->data['isPhone']   = $agent->isPhone();
        $this->data['isDesktop'] = $agent->isDesktop();
        $bucket                  = '';

        $s3Urls                    = BaseController::getS3Urls($bucket);
        $this->data['profilePath']         = $s3Urls['profilePath'];
        $this->data['profileHeaderPath']   = asset('/uploads/profile_pic/master/').'/';
        $this->data['gymSettingPathThumb'] = asset('/uploads/gym_setting/thumb/').'/';
        $this->data['gymSettingPath']      = asset('/uploads/gym_setting/master/').'/';
        $this->data['gymOffersPath']       = '';
        $this->data['expenseUrl'] = url('uploads/bill/').'/';
        $this->data['incomeUrl'] = url('uploads/income_bill/').'/';

        $this->data['logo']       = $s3Urls['logo'];

    }

    public function smsNotification($numbers, $message)
    {
        $this->getSmsSettings();
        $mobileNumber = implode(',', $numbers);

        $client = new Client(); // initialize guzzle client
        //for old sms client
        if($this->SMS_METHOD == true){
            $client->request('POST', $this->URL, [
                'form_params' => [
                    'sender'      => $this->SENDER_ID,
                    'username'    => $this->SMS_USERNAME,
                    'password'    => $this->SMS_PASSWORD,
                    'message'     => $message,
                    'destination' => $mobileNumber,
                ]
            ]);
        }else{
            //for new sms client
            $client->request('POST', $this->URL, [
                'form_params' => [
                    'senderid'      => $this->SENDER_ID,
                    'key'    => $this->API_KEY,
                    'responsetype'        => 'json',
                    'msg'     => $message,
                    'contacts' => $mobileNumber,
                ]
            ]);
        }
    }

    public function emailNotification($email, $eText, $eTitle, $eHeading, $url = null)
    {
        $this->email['emailText']  = $eText;
        $this->email['emailTitle'] = $eTitle;
        $this->email['url']        = $url;
        $this->email['email']      = $email;

        if ($this->data['gymSettings']->image != '') {
            $this->email['logo'] = '<img src="' . asset('/uploads/gym_setting/master/') . '/' . $this->data['gymSettings']->image . '" height="50" alt="Logo" style="border:none">';
        } else {
            $this->email['logo'] = '<img src="' . asset('/fitsigma/images') . '/' . 'fitness-plus.png' . '" height="50" alt="Logo" style="border:none">';
        }

        $this->email['businessName'] = ucwords($this->data['common_details']->title);
        $data                        = $this->email;

        Mail::to($data['email'])->send(new Notification($data));

    }

    public function emailNotificationAttachment($email, $eText, $eTitle, $eHeading, $url = null, $attachment)
    {
        $this->email['email']      = $email;
        $this->email['emailTitle'] = $eTitle;
        $this->email['emailText']  = $eText;
        $this->email['url']        = null;
        $this->email['attachment'] = $attachment;

        if ($this->data['gymSettings']->image != '') {
            $this->email['logo'] = '<img src="' . asset('/uploads/gym_setting/master/') . '/' . $this->data['gymSettings']->image . '" height="50" alt="Business Logo" style="border:none">';
        } else {
            $this->email['logo'] = '<img src="' . asset('/fitsigma/images') . '/' . 'fitness-plus.png' . '" height="50" alt="Business Logo" style="border:none">';
        }

        $this->email['businessName'] = ucwords($this->data['common_details']->title);
        $data                        = $this->email;

        try {
            Mail::to($data['email'])->send(new EmailVerification($data));
        } catch (\Exception $e) {
            $response['errorEmailMessage'] = 'error';
        }

    }

    public function addPromotionDatabase($data)
    {
        if ($data['number'][0] === "0") {
            $number = substr($data['number'], 1);
        } elseif (substr($data['number'], 0, 3) == "+91") {
            $number = substr($data['number'], 3);
        } else {
            $number = $data['number'];
        }

        $user = MerchantPromotionDatabase::where('mobile', '=', $number)->where('detail_id', '=', $this->data['user']->detail_id)->first();

        if (!is_null($user)) {
            $user->name   = $data['name'];
            $user->email  = $data['email'];
            $user->age    = $data['age'];
            $user->gender = $data['gender'];
            $user->save();
        } else {
            $user            = new MerchantPromotionDatabase();
            $user->name      = $data['name'];
            $user->email     = $data['email'];
            $user->age       = $data['age'];
            $user->gender    = $data['gender'];
            $user->mobile    = $number;
            $user->detail_id = $this->data['user']->detail_id;
            $user->save();
        }
    }

}
