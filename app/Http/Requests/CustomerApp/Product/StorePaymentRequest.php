<?php

namespace App\Http\Requests\CustomerApp\Product;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Product Payment request",
 *      description="Store Payment request body data",
 *      type="object",
 *      required={"product_sale_id", "payment_amount", "payment_source"}
 * )
 */
class StorePaymentRequest extends FormRequest
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
     * @OA\Property(
     *      title="product_sale_id",
     *      description="Product sale ID",
     *      example="1"
     * )
     * @var int
     */
    public $product_sale_id;

    /**
     * @OA\Property(
     *      title="payment_amount",
     *      description="Payment amount",
     *      example="100"
     * )
     * @var int
     */
    public $payment_amount;

    /**
     * @OA\Property(
     *      title="payment_source",
     *      description="Payment source",
     *      example="Credit Card"
     * )
     * @var string
     */
    public $payment_source;

    /**
     * @OA\Property(
     *      title="remarks",
     *      description="Remarks",
     *      example="Payment for product"
     * )
     * @var string
     */
    public $remarks;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_sale_id' => 'required|exists:product_sales,id',
            'payment_amount' => 'required|integer',
            'payment_source' => 'required|string',
            'remarks' => 'nullable|string'
        ];
    }

    /**
     * Custom error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_sale_id.required' => 'Product sale ID is required.',
            'payment_amount.required' => 'Payment amount is required.',
            'payment_amount.integer' => 'Payment amount must be an integer.',
            'payment_source.required' => 'Payment source is required.'
        ];
    }
}
