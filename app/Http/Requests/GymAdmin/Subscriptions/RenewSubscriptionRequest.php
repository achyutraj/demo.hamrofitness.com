<?php

namespace App\Http\Requests\GymAdmin\Subscriptions;

use App\Http\Requests\CoreRequest;

class RenewSubscriptionRequest extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'purchase_amount' => 'required',
            'amount_to_be_paid' => 'required',
            'purchase_date' => 'required|date',
            'next_payment_date' => 'required|date',
            'start_date' => 'required|date',
            'membership_id' => 'required_if:payment_for,==,membership',
            'offer_id' => 'required_if:payment_for,==,offer',
        ];
    }
}
