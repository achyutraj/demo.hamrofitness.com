<?php

namespace App\Http\Requests\CustomerApp\ManageReservation;

use App\Http\Requests\CoreRequest;

/**
 * @OA\Schema(
 *      title="Store Reservation request",
 *      description="Store Reservation request body data",
 *      type="object",
 *      required={"branch_id", "locker_id", "cost", "joining_date"}
 * )
 */
class StoreReservationRequest extends CoreRequest
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
     *      title="locker_id",
     *      description="Locker ID",
     *      example="1"
     * )
     * @var int
     */
    public $locker_id;

    /**
     * @OA\Property(
     *      title="cost",
     *      description="Reservation cost",
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
            'locker_id' => 'required|exists:lockers,id',
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
            'locker_id.required' => 'Locker ID is required.',
            'cost.required' => 'Reservation cost is required.',
            'cost.numeric' => 'Reservation cost must be a number.',
            'joining_date.required' => 'Joining date is required.',
            'joining_date.date' => 'Joining date must be a valid date.'
        ];
    }
}
