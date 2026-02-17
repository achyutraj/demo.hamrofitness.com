<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\ManageReservation\StoreReservationRequest;
use App\Http\Resources\Swagger\Locker\LockerResource;
use App\Http\Resources\Swagger\Locker\LockerReservationResource;
use App\Mail\AdminSubscriptionNotification;
use App\Models\Locker;
use App\Models\LockerReservation;
use App\Models\Merchant;
use App\Models\MerchantNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CustomerReservationController extends CustomerBaseController
{
   
      /**
     * @OA\Get(
     *      path="/api/customer/locker-lists",
     *      operationId="getAvailableLockerList",
     *      tags={"Locker Reservation"},
     *      security={{"passport": {}}},
     *      summary="Get list of all available locker lists",
     *      description="Return listing of locker lists",
     *       @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LockerResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function availableLockerLists()
    {
        $lockers = Locker::where('status','available')->where('detail_id',$this->getCustomerData()->detail_id);
        return LockerResource::collection($lockers->paginate(10));
    }

    /**
     * @OA\Get(
     *      path="/api/customer/manage-reservation",
     *      operationId="getReservationList",
     *      tags={"Locker Reservation"},
     *      security={{"passport": {}}},
     *      summary="Get list of all reservations",
     *      description="Return listing of customer locker reservations",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LockerReservationResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function index()
    {
        $purchases = LockerReservation::where('client_id', $this->getCustomerData()->id)
            ->orderBy('id', 'desc');
        return LockerReservationResource::collection($purchases->paginate(10));
    }

      /**
     * @OA\Post(
     *      path="/api/customer/manage-reservation/store",
     *      operationId="customerReservationAdd",
     *      tags={"Locker Reservation"},
     *      security={{"passport": {}}},
     *      summary="Add customer reservation",
     *      description="Returns data of reservation done by customer. Add branch_id as customer->detail_id",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreReservationRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LockerReservationResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function store(StoreReservationRequest $request)
    {
        $purchase = new LockerReservation();
        $purchase->client_id = $this->getCustomerData()->id;
        $purchase->locker_id = $request->get('locker_id');
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
        $notification->notification_type = 'Locker Reservation';
        $notification->title = 'New reservation is added by customer';
        $notification->save();
        //endregion

        // $admin = Merchant::find($this->getCustomerData()->detail_id);

        // $eText = "".$this->getCustomerData()->first_name.' '.$this->getCustomerData()->middle_name.' '.$this->getCustomerData()->last_name."added a Reservation";

        // $this->data['title'] = "Reservation Notification";
        // $this->data['mailHeading'] = "Reservation Notification";
        // $this->data['emailText'] = $eText;
        // $this->data['url'] = '';

        // try {
        //     Mail::to($admin->email)->send(new AdminSubscriptionNotification($this->data));
        // } catch (\Exception $e) {
        //     $response['errorEmailMessage'] = 'error';
        // }
        return new LockerReservationResource($purchase);
    }


     /**
     * @OA\Get(
     *      path="/api/customer/manage-reservation/show/{id}",
     *      operationId="getReservationById",
     *      tags={"Locker Reservation"},
     *      security={{"passport": {}}},
     *      summary="Get data of reservation",
     *      description="Return info of reservations",
     *      @OA\Parameter(
     *          name="id",
     *          description="Reservation id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LockerReservationResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function show($id)
    {
        $purchase = LockerReservation::where('id',$id)
            ->where('client_id',$this->getCustomerData()->id)->first();
        
        if($purchase == null){
            return $this->sendError('Locker Reservation Not Found');
        }
        return new LockerReservationResource($purchase);
    }


     /**
     * @OA\Delete(
     *      path="/api/customer/manage-reservation/destroy/{id}",
     *      operationId="deleteReservationById",
     *      tags={"Locker Reservation"},
     *      security={{"passport": {}}},
     *      summary="Delete data of reservation",
     *      description="Remove reservations",
     *      @OA\Parameter(
     *          name="id",
     *          description="Reservation id",
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
        $purchase =  LockerReservation::where('id',$id)
                ->where('client_id',$this->getCustomerData()->id)->first();
        if($purchase == null){
            return $this->sendError('Locker Reservation Not Found');
        }
        if($purchase->status == "active"){
            return $this->sendError('Reservation is active and can not be removed');
        }
        $purchase->delete();
        return $this->sendResponse($purchase,'Locker Reservation Delete');
    }

}
