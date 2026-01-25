<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Requests\CustomerApp\ManageReservation\StoreReservationPaymentRequest;
use App\Http\Resources\Swagger\Locker\LockerPaymentResource;
use App\Http\Resources\Swagger\Locker\LockerReservationResource;
use App\Models\BusinessCustomer;
use App\Models\GymClient;
use App\Models\GymInvoice;
use App\Models\GymInvoiceItems;
use App\Models\Locker;
use App\Models\LockerPayment;
use App\Models\LockerReservation;
use App\Models\GymSetting;
use App\Models\Merchant;
use App\Notifications\AddPaymentNotification;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CustomerLockerPaymentController extends CustomerBaseController
{
    /**
     * @OA\Get(
     *      path="/api/customer/locker-payments",
     *      operationId="getLockerPaymentList",
     *      tags={"Locker Payment"},
     *      security={{"passport": {}}},
     *      summary="Get list of reservation payments",
     *      description="Return data of reservations payment",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LockerPaymentResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function index()
    {
        $payments = LockerPayment::where('client_id', '=', $this->getCustomerData()->id)
            ->orderBy('id', 'desc');
        return LockerPaymentResource::collection($payments->paginate(10));
    }

      /**
     * @OA\Post(
     *      path="/api/customer/locker-payments/store",
     *      operationId="customerReservationPaymentAdd",
     *      tags={"Locker Payment"},
     *      security={{"passport": {}}},
     *      summary="Add customer reservation",
     *      description="Returns data of reservation done by customer",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreReservationPaymentRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LockerPaymentResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function store(StoreReservationPaymentRequest $request)
    {
        $payment                 = new LockerPayment();
        $payment->client_id        = $this->getCustomerData()->id;
        $payment->payment_amount = $request->get('payment_amount');
        $payment->reservation_id    = $request->get('reservation_id');
        $payment->payment_source = $request->get('payment_source');
        $payment->remarks        = $request->get('remarks');
        $payment->payment_date   = Carbon::today()->format('Y-m-d');
        $payment->detail_id      = $this->getCustomerData()->detail_id;
        $payment->payment_id = 'P' . random_int(10,99);

        // Update the details of next payment in locker_reservations
        $purchase                 = LockerReservation::find($request->get('reservation_id'));

        $purchase->paid_amount      = $purchase->paid_amount  + $request->get('payment_amount');
        if ($request->get('payment_amount') >= $purchase->amount_to_be_paid ) {
            $payment_required = "no";
        }else{
            $payment_required = "yes";
        }
        if ($payment_required == "no") {
            $purchase->next_payment_date = null;
        } else {
            $purchase->next_payment_date = Carbon::now()->addDays(7)->format('Y-m-d');
        }
        $purchase->payment_required      = $payment_required;
        $purchase->status      = "active";
        $purchase->update();
        $payment->save();
        // try {
        //     Notification::send(GymClient::find($this->getCustomerData()->id), new AddPaymentNotification($payment));
        // } catch (\Exception $e) {
        //     return $this->sendError($e->getMessage());
        // }
        return new LockerPaymentResource($payment);
    }


     /**
     * @OA\Get(
     *      path="/api/customer/locker/due-payments",
     *      operationId="getReservationDuePaymentList",
     *      tags={"Locker Payment"},
     *      security={{"passport": {}}},
     *      summary="Get list of reservation due payments",
     *      description="Return data of reservations due payment",
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

    public function duePayments()
    {
        $dues = LockerReservation::where('payment_required', 'yes')
            ->where('status', '=', 'active')
            ->where('client_id', $this->getCustomerData()->id);
        return LockerReservationResource::collection($dues->paginate(10));
    }


}
