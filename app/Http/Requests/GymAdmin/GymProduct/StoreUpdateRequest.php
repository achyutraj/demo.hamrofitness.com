<?php

namespace App\Http\Requests\GymAdmin\GymProduct;

use App\Http\Requests\CoreRequest;

class StoreUpdateRequest extends CoreRequest
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
            'supplier_id' => 'required|exists:gym_suppliers,id',
            'name' => 'required|string|regex:/^[a-zA-Z0-9\s,.\'-]+$/|max:150',
            'purchase_date' => 'required|date', // Adding date validation to ensure it's a valid date
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'expiry_date' => 'nullable|date', // Adding date validation to ensure it's a valid date
            'tag' => 'nullable|string|regex:/^[a-zA-Z0-9\s,.\'-]+$/|max:100',
            'brand_name' => 'nullable|string|regex:/^[a-zA-Z0-9\s,.\'-]+$/|max:100',


        ];
    }

    public function messages()
    {
        return [
            'supplier_id.required' => 'The supplier field is required.',
            'supplier_id.exists' => 'The selected supplier is invalid.',
            'product.required' => 'The product name field is required.',
            'product.string' => 'The product name must be a string.',
            'product.regex' => 'The product name format is invalid.',
            'product.max' => 'The product name may not be greater than 150 characters.',
            'purchase_date.required' => 'The purchase date field is required.',
            'purchase_date.date' => 'The purchase date is not a valid date.',
            'expiry_date.required' => 'The expiry date field is required.',
            'expiry_date.date' => 'The expiry date is not a valid date.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a number.',
            'quantity.required' => 'The quantity field is required.',
            'quantity.numeric' => 'The quantity must be a number.',
        ];
    }
}
