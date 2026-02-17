<?php

namespace App\Http\Requests\GymAdmin\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;

class ExtendSubscriptionRequest extends FormRequest
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
            'extend_from' => 'nullable|date',
            'days' => 'required',
            'reasons' => 'required',
        ];
    }
}
