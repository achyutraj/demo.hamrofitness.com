<?php

namespace App\Http\Requests\GymAdmin\GymIncome;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
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
            'income_category' => 'required|exists:income_categories,id',
            'supplier_id' => 'required|exists:gym_suppliers,id',
            'purchase_date' => 'required|date', // Adding date validation to ensure it's a valid date
            'price' => 'required|numeric',
            'bill' => 'nullable|max:1024|mimes:jpeg,png,pdf,jpg',
            'remarks' => 'sometimes|string|max:100',
            'payment_source' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'income_category.required' => 'The income category field is required.',
            'income_category.exists' => 'The selected income category is invalid.',
            'supplier_id.required' => 'The supplier field is required.',
            'supplier_id.exists' => 'The selected supplier is invalid.',
            'purchase_date.required' => 'The purchase date field is required.',
            'purchase_date.date' => 'The purchase date is not a valid date.',
            'purchase_date.date_format' => 'The purchase date does not match the format Y-m-d.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a number.',
            'bill.max' => 'The bill may not be greater than 1024 kilobytes.',
            'bill.mimes' => 'The bill must be a file of type: jpeg, png, pdf, jpg.',
            'remarks.string' => 'The remarks must be a string.',
            'remarks.max' => 'The remarks may not be greater than 100 characters.',
        ];
    }
}
