<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Requests\CustomerApp\Membership\StoreMembershipPaymentRequest;
use App\Http\Resources\Swagger\Membership\MembershipPaymentResource;
use App\Http\Resources\Swagger\Membership\SubscriptionResource;
use App\Models\BusinessCustomer;
use App\Models\GymClient;
use App\Models\GymInvoice;
use App\Models\GymInvoiceItems;
use App\Models\GymMembership;
use App\Models\GymMembershipPayment;
use App\Models\GymPurchase;
use App\Models\GymSetting;
use App\Models\Merchant;
use App\Notifications\AddPaymentNotification;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CustomerPaymentController extends CustomerBaseController
{
    /**
     * @OA\Get(
     *      path="/api/customer/payments",
     *      operationId="getPaymentList",
     *      tags={"Membership Payment"},
     *      security={{"passport": {}}},
     *      summary="Get list of subscription payments",
     *      description="Return data of subscriptions payment",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MembershipPaymentResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function index()
    {
       $payments = GymMembershipPayment::where('user_id', '=', $this->getCustomerData()->id);
        return MembershipPaymentResource::collection($payments->paginate(10));
    }

      /**
     * @OA\Post(
     *      path="/api/customer/payments/store",
     *      operationId="customerSubscriptionPaymentAdd",
     *      tags={"Membership Payment"},
     *      security={{"passport": {}}},
     *      summary="Add customer subscription payment",
     *      description="Returns data of subscription payment done by customer",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreMembershipPaymentRequest")
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MembershipPaymentResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function store(StoreMembershipPaymentRequest $request)
    {
        $payment                 = new GymMembershipPayment();
        $payment->user_id        = $this->getCustomerData()->id;
        $payment->payment_amount = $request->get('payment_amount');
        $payment->purchase_id    = $request->get('purchase_id');
        $payment->payment_source = $request->get('payment_source');
        $payment->remarks        = $request->get('remarks');
        $payment->payment_date   = Carbon::today()->format('Y-m-d');
        $payment->detail_id      = $this->getCustomerData()->detail_id;
        $payment->payment_id = 'P' . rand(1000,9999);

        // Update the details of next payment in gym_client_purchases
        $purchase                 = GymPurchase::find($request->get('purchase_id'));
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
        return new MembershipPaymentResource($payment);
    }


     /**
     * @OA\Get(
     *      path="/api/customer/membership/due-payments",
     *      operationId="getSubscriptionDuePaymentList",
     *      tags={"Membership Payment"},
     *      security={{"passport": {}}},
     *      summary="Get list of subscription due payments",
     *      description="Return data of subscriptions due payment",
     *     @OA\Response(
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

    public function duePayments()
    {
        $dues = GymPurchase::where('payment_required', 'yes')
            ->where('status', '=', 'active')
            ->where('client_id', $this->getCustomerData()->id);
        return SubscriptionResource::collection($dues->paginate(10));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function downloadInvoice($id)
    {
        $purchase      = GymPurchase::find($id);
        $membership    = GymMembership::find($purchase->membership_id);
        $clientDetails = GymClient::find($purchase->client_id);
        $merchant      = Merchant::where('is_admin', 1)->first();

        $invoiceId = $this->saveInvoice($merchant, $purchase, $clientDetails);
        $this->saveInvoiceItems($invoiceId, $membership, $purchase);

        header('Content-type: application/pdf');

        $this->data['invoice']  = GymInvoice::byInvoiceId($invoiceId, $this->getCustomerData()->detail_id);
        $this->data['settings'] = GymSetting::GetMerchantInfo($this->getCustomerData()->detail_id);
        $this->data['customerBusiness'] = BusinessCustomer::findByCustomer($this->getCustomerData()->id);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('customer-app.payments.invoice', $this->data);
        $filename = $this->data['customerBusiness']->business->slug . '-' . $this->data['invoice']->invoice_number.'.pdf';
        return response()->view('customer-app.payments.invoice',$this->data);
    }

    /**
     * @param $merchant
     * @param $purchase
     * @param $clientDetails
     * @return mixed
     */
    public function saveInvoice($merchant, $purchase, $clientDetails)
    {
        $name = ucfirst($clientDetails->first_name).' ';
        if(!is_null($clientDetails->middle_name)) {
            $name .= ucfirst($clientDetails->middle_name).' ';
        }
        $name .= ucfirst($clientDetails->last_name);
        $invoice                  = new GymInvoice();
        $invoice->merchant_id     = $merchant->id;
        $invoice->detail_id       = $purchase->detail_id;
        $invoice->client_name     = $name;
        $invoice->client_address  = $clientDetails->address ?? '';
        $invoice->email           = $clientDetails->email;
        $invoice->mobile          = $clientDetails->mobile;
        $invoice->invoice_date    = Carbon::now()->format('Y-m-d');
        $invoice->sub_total       = $purchase->paid_amount;
        $invoice->discount_amount = $purchase->discount;
        $invoice->total           = $purchase->paid_amount;
        $invoice->generated_by    = $name;
        $invoice->save();

        $invoice->invoice_number = strtoupper(Str::random(5)) . $invoice->id;
        $invoice->save();

        return $invoice->id;
    }

    /**
     * @param $invoiceId
     * @param $membership
     * @param $purchase
     */
    public function saveInvoiceItems($invoiceId, $membership, $purchase)
    {
        $invoiceItems                = new GymInvoiceItems();
        $invoiceItems->invoice_id    = $invoiceId;
        $invoiceItems->item_type     = 'item';
        $invoiceItems->item_name     = $membership->title;
        $invoiceItems->quantity      = 1;
        $invoiceItems->cost_per_item = $purchase->paid_amount;
        $invoiceItems->amount        = $purchase->paid_amount;
        $invoiceItems->save();
    }

}
