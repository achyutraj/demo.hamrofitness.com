<?php

namespace App\Http\Requests\CustomerApp\ManageSubscription;

use App\Http\Requests\CoreRequest;

/**
 * @OA\Schema(
 *      title="Store Subscription request",
 *      description="Store Subscription request body data",
 *      type="object",
 *      required={"branch_id", "membership_id", "cost", "joining_date"}
 * )
 */
class StoreSubscriptionRequest extends CoreRequest
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
     *      title="branch_id",
     *      description="Branch ID",
     *      example="1"
     * )
     * @var int
     */
    public $branch_id;

    /**
     * @OA\Property(
     *      title="membership_id",
     *      description="Membership ID",
     *      example="1"
     * )
     * @var int
     */
    public $membership_id;

    /**
     * @OA\Property(
     *      title="cost",
     *      description="Subscription cost",
     *      example="100"
     * )
     * @var float
     */
    public $cost;

    /**
     * @OA\Property(
     *      title="joining_date",
     *      description="Joining date",
     *      example="m/d/Y"
     * )
     * @var string
     */
    public $joining_date;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'branch_id' => 'required|exists:common_details,id',
            'membership_id' => 'required|exists:gym_memberships,id',
            'cost' => 'required|numeric',
            'joining_date' => 'required|date_format:m/d/Y'
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
            'membership_id.required' => 'Membership ID is required.',
            'cost.required' => 'Subscription cost is required.',
            'cost.numeric' => 'Subscription cost must be a number.',
            'joining_date.required' => 'Joining date is required.',
            'joining_date.date' => 'Joining date must be a valid date.'
        ];
    }
}
