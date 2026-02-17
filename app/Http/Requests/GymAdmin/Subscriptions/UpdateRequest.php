<?php

namespace App\Http\Requests\GymAdmin\Subscriptions;

use App\Http\Requests\CoreRequest;

class UpdateRequest extends CoreRequest
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
            'purchase_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'amount_to_be_paid' => 'required|numeric',
            'purchase_date' => 'required',
            'start_date' => 'required',
        ];
    }
}
