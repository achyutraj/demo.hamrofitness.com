<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Requests\CustomerApp\Profile\StoreProfileRequest;
use App\Http\Resources\Swagger\GymClientResource;
use App\Http\Resources\Swagger\GymPlan\ClassScheduleResource;
use App\Http\Resources\Swagger\GymPlan\DietPlanResource;
use App\Http\Resources\Swagger\GymPlan\TrainingPlanResource;
use App\Models\ClassSchedule;
use App\Models\DietPlan;
use App\Models\GymClient;
use App\Models\GymMembershipPayment;
use App\Models\LockerPayment;
use App\Models\ProductPayment;

use App\Models\GymPurchase;
use App\Models\LockerReservation;
use App\Models\TrainingPlan;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\GymSetting;

class CustomerController extends CustomerBaseController
{
    
    /**
     * @OA\Get(
     *      path="/api/customer/dashboard",
     *      operationId="customerDashboard",
     *      tags={"Customer"},
     *      security={{"passport": {}}},
     *      summary="Customer dashboard",
     *      description="Returns data for customer dashboard",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *         )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */

    public function dashboard()
    {
        $start = Carbon::today()->format('Y-m-d');
        $gymSettings = GymSetting::where('detail_id',$this->getCustomerData()->detail_id)->first();
        $options = json_decode($gymSettings->options, true);
        $days = $options['subscription_expire_days'] ?? '7';
        $end =  Carbon::today()->addDays($days)->format('Y-m-d');
        $this->data['totalAmountPaid'] = GymMembershipPayment::leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'purchase_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_membership_payments.user_id')
            ->where('gym_clients.id', '=', $this->getCustomerData()->id)
            ->sum('payment_amount');
        $this->data['totalSubscriptions'] = $this->getCustomerData()->subscriptions()->count();
        $this->data['expiringSubscriptions'] = GymPurchase::select('first_name', 'middle_name','last_name','gym_client_purchases.start_date','gym_client_purchases.expires_on', 'gym_memberships.title as membership', 'gym_client_purchases.id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.detail_id', '=', $this->getCustomerData()->detail_id)
            ->where('gym_clients.id', '=', $this->getCustomerData()->id)
            ->where(['gym_client_purchases.status'=>'active','gym_client_purchases.payment_required'=>'yes'])
            ->whereBetween('gym_client_purchases.expires_on',[$start,$end])
            ->orderBy('gym_client_purchases.expires_on', 'asc')
            ->get();
        $this->data['duePayments'] = GymPurchase::select('first_name','middle_name', 'last_name','gym_client_purchases.amount_to_be_paid as amount_to_be_paid','gym_client_purchases.paid_amount as paid', 'next_payment_date as due_date', 'gym_memberships.title as membership', 'gym_client_purchases.id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.detail_id', '=', $this->getCustomerData()->detail_id)
            ->where('gym_client_purchases.payment_required', 'yes')
            ->where('gym_client_purchases.status', '=', 'active')
            ->where('gym_clients.id', '=', $this->getCustomerData()->id)
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
            ->where('gym_clients.id', '=', $this->getCustomerData()->id)
            ->where('gym_membership_payments.detail_id', '=', $this->getCustomerData()->detail_id)
            ->where(DB::raw('YEAR(payment_date)'), Carbon::today()->year)
            ->groupBy(DB::raw('MONTH(payment_date)'))
            ->get();
       
        $this->data['totalLockerAmountPaid'] = LockerPayment::leftJoin('locker_reservations', 'locker_reservations.id', '=', 'reservation_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'locker_payments.client_id')
            ->where('gym_clients.id', '=', $this->getCustomerData()->id)
            ->sum('payment_amount');
        $this->data['totalReservations'] = $this->data['customerValues']->reservations()->count();
        $this->data['expiringReservations'] = LockerReservation::select('first_name', 'middle_name','last_name','locker_reservations.start_date','locker_reservations.end_date', 'lockers.locker_num as locker', 'locker_reservations.id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('lockers', 'lockers.id', '=', 'locker_id')
            ->where('locker_reservations.detail_id', '=', $this->getCustomerData()->detail_id)
            ->where('gym_clients.id', '=', $this->getCustomerData()->id)
            ->where(['locker_reservations.status'=>'active','locker_reservations.payment_required'=>'yes'])
            ->whereBetween('locker_reservations.end_date',[$start,$end])
            ->orderBy('locker_reservations.end_date', 'asc')
            ->get();

        $this->data['dueReservationPayments'] = LockerReservation::select('first_name','middle_name', 'last_name','locker_reservations.amount_to_be_paid as amount_to_be_paid','locker_reservations.paid_amount as paid', 'next_payment_date as due_date', 'lockers.locker_num as locker', 'locker_reservations.id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('lockers', 'lockers.id', '=', 'locker_id')
            ->where('locker_reservations.detail_id', '=', $this->getCustomerData()->detail_id)
            ->where('locker_reservations.payment_required', 'yes')
            ->where('locker_reservations.status', '=', 'active')
            ->where('locker_reservations.client_id', '=', $this->getCustomerData()->id)
            ->get();
        
        $this->data['totalProductPurchase'] = $this->data['customerValues']->reservations()->count();
        $this->data['totalProductAmountPaid'] = ProductPayment::leftJoin('product_sales', 'product_sales.id', '=', 'product_sale_id')
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_payments.user_id')
                ->where('gym_clients.id', '=', $this->getCustomerData()->id)
                ->sum('payment_amount');
        return $this->sendResponse($this->data,'Customer Dashboard');
    }

     /**
     * @OA\Get(
     *      path="/api/customer/diet-plans",
     *      operationId="dietPlanList",
     *      tags={"Customer"},
     *      security={{"passport": {}}},
     *      summary="Customer diet plan. Add type:default for default diet plan list, and type:client for client specific diet plan list",
     *      description="Returns data for customer diet plan. Add type:default for default diet plan list, and type:client for client specific diet plan list",
     *      @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of diet plan to retrieve",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="default"
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DietPlanResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */

    public function dietPlanList(Request $request)
    {
        $type = $request->query('type', 'default');
        if ($type === 'default') {
            $diet_plan_query = DietPlan::where('branch_id', $this->getCustomerData()->detail_id)
                ->where('client_id', null)
                ->latest();
        } else {
            $diet_plan_query = DietPlan::where('client_id', $this->getCustomerData()->id)
                ->latest();
        }

        // Paginate the results
        $diet_plan_paginated = $diet_plan_query->paginate(10);

        // Map the paginated data
        $diet_plan_paginated->getCollection()->transform(function ($dietPlan) {
            return $this->mapDietPlanData($dietPlan);
    });

    return DietPlanResource::collection($diet_plan_paginated);
    }

    private function mapDietPlanData($dietPlan)
    {
        if (!$dietPlan) {
            return null;
        }
        // Decode JSON fields
        $dietPlan->breakfast = decodeJsonField($dietPlan->breakfast);
        $dietPlan->lunch = decodeJsonField($dietPlan->lunch);
        $dietPlan->dinner = decodeJsonField($dietPlan->dinner);
        $dietPlan->meal_4 = decodeJsonField($dietPlan->meal_4);
        $dietPlan->meal_5 = decodeJsonField($dietPlan->meal_5);
        $dietPlan->days = $this->unserializeField($dietPlan->days);

        return $dietPlan;
    }
    
    private function unserializeField($field)
    {
        return $field ? unserialize($field) : [];
    }

    /**
     * @OA\Get(
     *      path="/api/customer/training-plans",
     *      operationId="trainingPlanList",
     *      tags={"Customer"},
     *      security={{"passport": {}}},
     *      summary="Customer training plan. Add type:default for default training list, and type:client for client specific training list",
     *      description="Returns data for customer training plan. Add type:default for default training list, and type:client for client specific training list",
     *       @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of training plan to retrieve",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="default"
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TrainingPlanResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function trainingPlanList(Request $request)
    {
        $type = $request->query('type', 'default');
        if($type === 'default'){
            $training_plan = TrainingPlan::where('branch_id', $this->getCustomerData()->detail_id)
                ->where('client_id', null)->latest();
        }else{
            $training_plan = TrainingPlan::where('client_id', $this->getCustomerData()->id)
                ->latest();
        }

        $jsonFields = ['days', 'activity', 'sets', 'repetition', 'weights', 'restTime'];

        $training_plan = decodeJsonFields($training_plan, $jsonFields);

        if (!$training_plan) {
            $training_plan = new TrainingPlan(); // Empty instance if not found
        }

        return  TrainingPlanResource::collection($training_plan->paginate(10));
    }


     /**
     * @OA\Get(
     *      path="/api/customer/class-schedules",
     *      operationId="classPlanList",
     *      tags={"Customer"},
     *      security={{"passport": {}}},
     *      summary="Customer class plan. Type: default , client. Add type:default for default class schedule list, and type:client for client specific schedule list",
     *      description="Returns data for customer class plan. Add type:default for default class schedule list, and type:client for client specific schedule list",
     *      @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of schedule to retrieve",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="default"
     *         )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ClassScheduleResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function classSchedulePlanList(Request $request){

        $type = $request->query('type', 'default');

        if($type === 'default'){
            $class_schedule = ClassSchedule::where('has_client',0)->where('detail_id',$this->getCustomerData()->detail_id)
            ->orderBy('id','desc')->paginate(10);
        }else{
            $class_schedule = ClassSchedule::where('has_client',1)->where('detail_id',$this->getCustomerData()->detail_id)
            ->whereHas('clients',function ($query){
                $query->where('client_id',$this->getCustomerData()->id);
            })
            ->orderBy('id','desc')->paginate(10);
        }
        
        if (!$class_schedule) {
            $class_schedule = new ClassSchedule(); // Empty instance if not found
        }
        return ClassScheduleResource::collection($class_schedule);

    }

    
    /**
     * @OA\Get(
     *      path="/api/customer/unread-notification",
     *      operationId="unReadNotification",
     *      tags={"Customer"},
     *      security={{"passport": {}}},
     *      summary="Total unread notifications",
     *      description="Return list of unread notifications",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="customer_id", type="int", example="12345"),
     *              @OA\Property(property="notification_type", type="string", example="Subscription"),
     *              @OA\Property(property="title", type="string", example="Subscription Added "),
     *         )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
   
    public function getTotalUnReadNotification() : JsonResponse
    {
        $notifications = [];
        $customer = GymClient::find($this->getCustomerData()->id);

        foreach ($customer->unreadNotifications as $notification) {
            if($notification->data['customer_id'] == $customer->id) {
                array_push($notifications, $notification->data);
            }
        }
        return response()->json([
            'data' => $notifications,
        ]);
    }


    /**
     * @OA\Get(
     *      path="/api/customer/count-unread-notification",
     *      operationId="countUnReadNotification",
     *      tags={"Customer"},
     *      security={ {"passport": {} }},
     *      summary="Total number of unread notifications",
     *      description="Return total count of unread notifications",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="unreadNotifications", type="int", example="50"),
     *         )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function getTotalCountUnReadNotification() : JsonResponse
    {
        $count = 0;
        $customer = GymClient::find($this->getCustomerData()->id);
        foreach ($customer->unreadNotifications as $notification) {
            if($notification->data['customer_id'] == $customer->id) {
                $count++;
            }
        }
        return response()->json([
            'unreadNotifications' => $count,
        ]);
    }


    /**
     * @OA\Post(
     *      path="/api/customer/markRead",
     *      operationId="markReadNotifications",
     *      tags={"Customer"},
     *      security={ {"passport": {} }},
     *      summary="Customer notification mark read",
     *      description="Customer notification mark read",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *         )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
   
    public function markRead()
    {
        $user = GymClient::find($this->getCustomerData()->id);
        $user->unreadNotifications()->update(['read_at' => Carbon::now()]);
        return $this->sendResponse([],'Notification Mark as Read');
    }

    /**
     * @OA\Get(
     *      path="/api/customer/profile",
     *      operationId="customerProfile",
     *      tags={"Customer"},
     *       security={ {"passport": {} }},
     *      summary="Customer profile",
     *      description="Returns data of customer profile",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/GymClientResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function profileIndex()
    {
        $user = GymClient::find($this->getCustomerData()->id);
        $destinationPathMaster = "/uploads/profile_pic/master/".$user->image;
        $destinationPathThumb = "/uploads/profile_pic/thumb/".$user->image;
        $user->image = $destinationPathMaster ?? $destinationPathThumb;
        if($user){
            return new GymClientResource($user);
        }else{
            return $this->sendError('No user found');
        }
    }

     /**
     * @OA\Get(
     *      path="/api/customer/qr-info",
     *      operationId="customerQRInfo",
     *      tags={"Customer"},
     *       security={ {"passport": {} }},
     *      summary="Customer profile",
     *      description="Returns data of customer profile",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function getQRInfo()
    {
        $user = GymClient::find($this->getCustomerData()->id);
        if($user){
            $qrData = $user->subscriptionData();
            return $qrData;
        }else{
            return $this->sendError('No user found');
        }
    }


    /**
     * @OA\Post(
     *      path="/api/customer/profile/store",
     *      operationId="customerProfileStore",
     *      tags={"Customer"},
     *      security={ {"passport": {} }},
     *      summary="Customer profile store",
     *      description="Update customer profile data",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
    *              mediaType="multipart/form-data",
    *              @OA\Schema(
    *                  ref="#/components/schemas/StoreProfileRequest"
    *              )
    *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/GymClientResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function profileStore(StoreProfileRequest $request)
    {
      
        $client = GymClient::find($this->getCustomerData()->id);
        if($request->get('dob')) {
            $dob = Carbon::createFromFormat('m/d/Y', $request->get('dob'))->format('Y-m-d');
        }
        if($request->get('anniversary')) {
            $anniversary = Carbon::createFromFormat('m/d/Y', $request->get('anniversary'))->format('Y-m-d');
        }
        if($request->get('password')) {
            $password = Hash::make($request->get('password'));
        }
        if($request->hasFile('file')) {
           $this->uploadImage($request);
        }
        $client->update([
            'first_name' => $request->get('first_name'),
            'middle_name' => $request->get('middle_name'),
            'last_name' => $request->get('last_name'),
            'mobile' => $request->get('mobile'),
            'emergency_contact' => $request->get('emergency_contact'),
            'email' => $request->get('email'),
            'gender' => $request->get('gender'),
            'marital_status' => $request->get('marital_status'),
            'height_feet' => $request->get('height_feet'),
            'height_inches' => $request->get('height_inches'),
            'weight' => $request->get('weight'),
            'fat' => $request->get('fat'),
            'chest' => $request->get('chest'),
            'waist' => $request->get('waist'),
            'arms' => $request->get('arms'),
            'occupation' => $request->get('occupation'),
            'occupation_details' => $request->get('occupation_details'),
            'address' => $request->get('address'),
            'blood_group' => $request->get('blood_group'),
            'dob' => $dob ?? $client->dob,
            'password' => $password ?? $client->password,
            'anniversary' => $anniversary ?? $client->anniversary,
        ]);
        return new GymClientResource($client);
    }

    public function uploadImage(Request $request)
    {
        if(!$request->hasFile('file')) {
            return response()->json(['upload_file_not_found'], 400);
        }

        $id = $this->getCustomerData()->id;
        $allowedfileExtension=['pdf','jpg','png'];
        $extension = $request->file('file')->getClientOriginalExtension();
        $check = in_array($extension,$allowedfileExtension);
        if($check){

            $output = [];
            $image = request()->file('file');

            $x = intval($request->xCoordOne);
            $y = intval($request->yCoordOne);
            $width = intval($request->profileImageWidth);
            $height = intval($request->profileImageHeight);

            $filename  = $id."-".rand(10000,99999).".".$extension;

            if($this->data['gymSettings']->local_storage == 0) {
                $destinationPathMaster = "/uploads/profile_pic/master/$filename";
                $destinationPathThumb = "/uploads/profile_pic/thumb/$filename";


                $image1 = Image::make($image->getRealPath())
                    ->crop($width, $height, $x, $y)
                    ->resize(206, 207);

                $this->uploadImageS3($image1, $destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->crop($width, $height, $x, $y)
                    ->resize(40, 40);

                $this->uploadImageS3($image2, $destinationPathThumb);
            } else {
                if (!file_exists(public_path()."/uploads/profile_pic/master/") &&
                    !file_exists(public_path()."/uploads/profile_pic/thumb/")) {
                    File::makeDirectory(public_path()."/uploads/profile_pic/master/", $mode = 0777, true, true);
                    File::makeDirectory(public_path()."/uploads/profile_pic/thumb/", $mode = 0777, true, true);
                }

                $destinationPathMaster = public_path()."/uploads/profile_pic/master/$filename";
                $destinationPathThumb = public_path()."/uploads/profile_pic/thumb/$filename";
                $image1 = Image::make($image->getRealPath())
                    ->resizeCanvas($width, $height, 'center', false, 'rgba(0, 0, 0, 0)');
                $image1->save($destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->resizeCanvas($width, $height, 'center', false, 'rgba(0, 0, 0, 0)')
                    ->resize(40, 40);
                $image2->save($destinationPathThumb);
            }

            $forUpdate = [
                'image' => $filename
            ];

            $profile = GymClient::find($id);
            $profile->update($forUpdate);

        }else{
            return $this->sendError('Invalid request method');
        }
        $output['image'] = $filename;
        return $this->sendResponse(json_encode($output),'Image Upload Successfully');
    }

    public function uploadImageS3($imageMake, $filePath)
    {
        if (get_class($imageMake) === 'Intervention\Image\Image') {
            Storage::put($filePath, $imageMake->stream()->__toString(), 'public');
        } else {
            Storage::put($filePath, fopen($imageMake, 'r'), 'public');
        }
    }

    public function uploadWebcamImage($id)
    {
        $image = request()->file('webcam');

        $fileName = $id . "-" . rand(10000, 99999) . ".jpg";
        if($this->data['gymSettings']->local_storage == 0) {
            $destinationPathMaster = "profile_pic/master/$fileName";
            $destinationPathThumb = "profile_pic/thumb/$fileName";

            $image1 = Image::make($image->getRealPath())
                ->resize(206, 155);

            $this->uploadImageS3($image1, $destinationPathMaster);

            $image2 = Image::make($image->getRealPath())
                ->resize(35, 34);

            $this->uploadImageS3($image2, $destinationPathThumb);
        } else {
            if (!file_exists(public_path()."/uploads/profile_pic/master/") &&
                !file_exists(public_path()."/uploads/profile_pic/thumb/")) {
                File::makeDirectory(public_path()."/uploads/profile_pic/master/", $mode = 0777, true, true);
                File::makeDirectory(public_path()."/uploads/profile_pic/thumb/", $mode = 0777, true, true);
            }

            $destinationPathMaster = public_path()."/uploads/profile_pic/master/$fileName";
            $destinationPathThumb = public_path()."/uploads/profile_pic/thumb/$fileName";
            $image1 = Image::make($image->getRealPath())
                ->resize(206, 155);
            $image1->save($destinationPathMaster);

            $image2 = Image::make($image->getRealPath())
                ->resize(35, 34);
            $image2->save($destinationPathThumb);
        }

        $gym_client = GymClient::find($id);
        $gym_client->image = $fileName;
        $gym_client->save();

        $output['image'] = $fileName;
        return $this->sendResponse($output,'Image Upload Successfully');
    }


     /**
     * @OA\Post(
     *      path="/api/customer/profile/deactivate",
     *      operationId="customerProfileDeactivate",
     *      tags={"Customer"},
     *      security={ {"passport": {} }},
     *      summary="Customer profile de-active",
     *      description="Add Reason for customer profile de-active",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"remarks"},
     *             @OA\Property(property="remarks", type="string", format="remarks", example="Remarks"),
     *         ),
     *     ),
     *       @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/GymClientResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function updateClientStatus(Request $request){
        $this->validate($request,[
            'remarks' => 'required|string'
        ]);
        $profileStore = GymClient::find($this->getCustomerData()->id);
        $profileStore->status = 0;
        $profileStore->remarks = $request->remarks;
        $profileStore->save();
        return new GymClientResource($profileStore);
    }
}
