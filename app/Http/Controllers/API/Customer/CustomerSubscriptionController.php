<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\ManageSubscription\StoreSubscriptionRequest;
use App\Http\Resources\Swagger\Membership\MembershipPlanResource;
use App\Http\Resources\Swagger\Membership\SubscriptionResource;
use App\Mail\AdminSubscriptionNotification;
use App\Models\GymClient;
use App\Models\GymMembership;
use App\Models\GymPurchase;
use App\Models\Merchant;
use App\Models\MerchantNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CustomerSubscriptionController extends CustomerBaseController
{
   
    protected $datas = [];

     /**
     * @OA\Get(
     *      path="/api/customer/membership-plans",
     *      operationId="getMembershipPlanList",
     *      tags={"Membership Subscription"},
     *      security={{"passport": {}}},
     *      summary="Get list of all membership plans",
     *      description="Return listing of membership plans",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MembershipPlanResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function membershipPlanLists()
    {
        $memberships  = GymMembership::where('detail_id',$this->getCustomerData()->detail_id);
        return MembershipPlanResource::collection($memberships->paginate(10));
    }

    /**
     * @OA\Get(
     *      path="/api/customer/manage-subscription",
     *      operationId="getSubscriptionList",
     *      tags={"Membership Subscription"},
     *      security={{"passport": {}}},
     *      summary="Get list of all subscriptions",
     *      description="Return listing of customer membership subscriptions",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SubscriptionResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function index()
    {
        $purchases = GymPurchase::where('client_id', $this->getCustomerData()->id)
            ->orderBy('id', 'desc');
        return SubscriptionResource::collection($purchases->paginate(10));
    }

      /**
     * @OA\Post(
     *      path="/api/customer/manage-subscription/store",
     *      operationId="customerSubscriptionAdd",
     *      tags={"Membership Subscription"},
     *      security={{"passport": {}}},
     *      summary="Add customer subscription",
     *      description="Returns data of subscription done by customer. Add branch_id as customer->detail_id",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreSubscriptionRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SubscriptionResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function store(StoreSubscriptionRequest $request)
    {
        $purchase = new GymPurchase();
        $purchase->client_id = $this->getCustomerData()->id;
        $purchase->membership_id = $request->get('membership_id');
        $purchase->detail_id = $request->get('branch_id');
        $purchase->purchase_amount = $request->get('cost');
        $purchase->amount_to_be_paid = $request->get('cost');
        $purchase->paid_amount = 0;
        $purchase->discount = 0;
        $purchase->start_date = Carbon::createFromFormat('m/d/Y', $request->get('joining_date'))->format('Y-m-d');
        $purchase->status = 'pending';
        $purchase->payment_required = 'yes';
        $purchase->purchase_date = Carbon::now()->format('Y-m-d');
        $purchase->save();

        //region Notification
        $notification = new MerchantNotification();
        $notification->detail_id = $request->get('branch_id');
        $notification->notification_type = 'Subscription';
        $notification->title = 'New subscription is added by customer';
        $notification->save();
        //endregion

        $admin = Merchant::find($this->getCustomerData()->detail_id);

        $eText = "".$this->getCustomerData()->first_name.' '.$this->getCustomerData()->middle_name.' '.$this->getCustomerData()->last_name."added a Subscription";

        $this->data['title'] = "Subscription Notification";
        $this->data['mailHeading'] = "Subscription Notification";
        $this->data['emailText'] = $eText;
        $this->data['url'] = '';

        try {
            Mail::to($admin->email)->send(new AdminSubscriptionNotification($this->data));
        } catch (\Exception $e) {
            $response['errorEmailMessage'] = 'error';
        }
        return new SubscriptionResource($purchase);
    }


     /**
     * @OA\Get(
     *      path="/api/customer/manage-subscription/show/{id}",
     *      operationId="getSubscriptionById",
     *      tags={"Membership Subscription"},
     *      security={{"passport": {}}},
     *      summary="Get data of subscription",
     *      description="Return info of subscriptions",
     *      @OA\Parameter(
     *          name="id",
     *          description="Subscription id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SubscriptionResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function show($id)
    {
        $purchase = GymPurchase::where('id',$id)
                ->where('client_id',$this->getCustomerData()->id)->first();
        if($purchase == null){
            return $this->sendError('Membership Subscription Not Found');
        }
        return new SubscriptionResource($purchase);
    }


     /**
     * @OA\Delete(
     *      path="/api/customer/manage-subscription/destroy/{id}",
     *      operationId="deleteSubscriptionById",
     *      tags={"Membership Subscription"},
     *      security={{"passport": {}}},
     *      summary="Delete data of subscription",
     *      description="Remove subscriptions",
     *      @OA\Parameter(
     *          name="id",
     *          description="Subscription id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
    public function destroy($id)
    {
        $purchase =  GymPurchase::where('id',$id)
                ->where('client_id',$this->getCustomerData()->id)->first();
        if($purchase == null){
            return $this->sendError('Membership Subscription Not Found');
        }
        if($purchase->status == "active"){
            return $this->sendError('Subscription is active and can not be removed');
        }
        $purchase->delete();
        return $this->sendResponse($purchase,'Membership Subscription Delete');
    }

}
