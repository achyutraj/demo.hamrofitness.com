<?php

namespace App\Http\Requests\CustomerApp\Product;

use App\Http\Requests\CoreRequest;

/**
 * @OA\Schema(
 *      schema="StoreProductPurchaseRequest",
 *      type="object",
 *      title="Store Product Purchase Request",
 *      required={"product", "product_quantity", "product_price", "amount", "branch_id"},
 *      @OA\Property(
 *          property="product",
 *          type="array",
 *          @OA\Items(type="integer"),
 *          description="Array of product IDs"
 *      ),
 *      @OA\Property(
 *          property="product_quantity",
 *          type="array",
 *          @OA\Items(type="integer"),
 *          description="Array of quantities for each product"
 *      ),
 *      @OA\Property(
 *          property="product_price",
 *          type="array",
 *          @OA\Items(type="number", format="float"),
 *          description="Array of prices for each product"
 *      ),
 *      @OA\Property(
 *          property="product_discount",
 *          type="array",
 *          @OA\Items(type="number", format="float"),
 *          description="Array of discounts for each product"
 *      ),
 *      @OA\Property(
 *          property="amount",
 *          type="array",
 *          @OA\Items(type="number", format="float"),
 *          description="Array of total amounts for each product"
 *      ),
 *      @OA\Property(
 *          property="branch_id",
 *          type="integer",
 *          description="ID of the branch where the purchase is made"
 *      ),
 * )
 */

class StoreProductPurchaseRequest extends CoreRequest
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
            'branch_id' => 'required|exists:common_details,id',
            'product' => 'required|array|min:1',
            'product.*' => 'integer|exists:products,id',
            'product_quantity' => 'required|array|min:1',
            'product_quantity.*' => 'integer|min:1',
            'product_price' => 'required|array|min:1',
            'product_price.*' => 'numeric|min:0',
            'product_discount' => 'nullable|array',
            'product_discount.*' => 'numeric|min:0',
            'amount' => 'required|array|min:1',
            'amount.*' => 'numeric|min:0',
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
            'branch_id.required' => 'Branch ID is required.',
            'product.*.exists' => 'One or more selected products do not exist.',
            'product_quantity.*.min' => 'The quantity must be at least 1.',
            'product_price.*.numeric' => 'The price must be a valid number.',
        ];
    }
}
