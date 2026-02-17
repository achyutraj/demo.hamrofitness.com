<?php

namespace App\Http\Requests\GymAdmin\GymExpense;

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
            'expense_category' => 'required|exists:expense_categories,id',
            'supplier_id' => 'required|exists:gym_suppliers,id',
            'item_name' => 'required|string|regex:/^[a-zA-Z0-9\s,.\'-]+$/|max:50',
            'purchase_date' => 'required|date', // Adding date validation to ensure it's a valid date
            'price' => 'required|numeric',
            'bill' => 'nullable|max:1024|mimes:jpeg,png,pdf,jpg',
            'remarks' => 'sometimes|string|max:100',
            'payment_status' => 'required|string',
            'payment_source' => 'required_if:payment_status,paid',
        ];
    }

    public function messages()
    {
        return [
            'expense_category.required' => 'The expense category field is required.',
            'expense_category.exists' => 'The selected expense category is invalid.',
            'supplier_id.required' => 'The supplier field is required.',
            'supplier_id.exists' => 'The selected supplier is invalid.',
            'item_name.required' => 'The item name field is required.',
            'item_name.string' => 'The item name must be a string.',
            'item_name.regex' => 'The item name format is invalid.',
            'item_name.max' => 'The item name may not be greater than 50 characters.',
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
