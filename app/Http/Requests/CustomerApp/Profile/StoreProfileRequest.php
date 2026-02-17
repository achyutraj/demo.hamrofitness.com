<?php

namespace App\Http\Requests\CustomerApp\Profile;

use App\Http\Requests\CoreRequest;

/**
 * @OA\Schema(
 *      title="Store Customer profile request",
 *      description="Store Customer profile request body data",
 *      type="object",
 *      required={"first_name", "last_name", "mobile", "email", "gender"}
 * )
 */
class StoreProfileRequest extends CoreRequest
{
    /**
     * @OA\Property(
     *      title="First Name",
     *      description="First name of the customer",
     *      example="John"
     * )
     * @var string
     */
    public $first_name;

    /**
     * @OA\Property(
     *      title="Middle Name",
     *      description="Middle name of the customer",
     *      example="Doe"
     * )
     * @var string
     */
    public $middle_name;

    /**
     * @OA\Property(
     *      title="Last Name",
     *      description="Last name of the customer",
     *      example="Smith"
     * )
     * @var string
     */
    public $last_name;

    /**
     * @OA\Property(
     *      title="Mobile",
     *      description="Mobile number of the customer",
     *      example="+1234567890"
     * )
     * @var string
     */
    public $mobile;

    /**
     * @OA\Property(
     *      title="Emergency Contact",
     *      description="Emergency Contact number of the customer",
     *      example="+1234567890"
     * )
     * @var string
     */
    public $emergency_contact;

    /**
     * @OA\Property(
     *      title="Email",
     *      description="Email address of the customer",
     *      example="customer@example.com"
     * )
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="Gender",
     *      description="Gender of the customer",
     *      example="male"
     * )
     * @var string
     */
    public $gender;

    /**
     * @OA\Property(
     *      title="Marital Status",
     *      description="Marital status of the customer",
     *      example="no"
     * )
     * @var string
     */
    public $marital_status;

    /**
     * @OA\Property(
     *      title="Height (Feet)",
     *      description="Height of the customer in feet",
     *      example=5
     * )
     * @var integer
     */
    public $height_feet;

    /**
     * @OA\Property(
     *      title="Height (Inches)",
     *      description="Height of the customer in inches",
     *      example=9
     * )
     * @var integer
     */
    public $height_inches;

    /**
     * @OA\Property(
     *      title="Weight",
     *      description="Weight of the customer",
     *      example=160
     * )
     * @var integer
     */
    public $weight;

    /**
     * @OA\Property(
     *      title="Fat",
     *      description="Fat of the customer",
     *      example=160
     * )
     * @var integer
     */
    public $fat;

    /**
     * @OA\Property(
     *      title="Arms",
     *      description="Arms of the customer",
     *      example=160
     * )
     * @var integer
     */
    public $arms;

    /**
     * @OA\Property(
     *      title="Chest",
     *      description="Chest of the customer",
     *      example=160
     * )
     * @var integer
     */
    public $chest;

    /**
     * @OA\Property(
     *      title="Waist",
     *      description="Waist of the customer",
     *      example=160
     * )
     * @var integer
     */
    public $waist;

    /**
     * @OA\Property(
     *      title="Address",
     *      description="Address of the customer",
     *      example="123 Main St, Anytown, USA"
     * )
     * @var string
     */
    public $address;

    /**
     * @OA\Property(
     *      title="Date of Birth",
     *      description="Date of birth of the customer",
     *      example="m/d/Y",
     * )
     * @var string
     */
    public $dob;

    /**
     * @OA\Property(
     *      title="Anniversary",
     *      description="Anniversary date of the customer",
     *      example="m/d/Y",
     * )
     * @var string
     */
    public $anniversary;

    /**
     * @OA\Property(
     *      title="Password",
     *      description="Password of the customer",
     *      example="password123"
     * )
     * @var string
     */
    public $password;

    /**
     * @OA\Property(
     *      title="BloodGroup",
     *      description="BloodGroup of the customer",
     *      example="o+"
     * )
     * @var string
     */
    public $blood_group;

    /**
     * @OA\Property(
     *      title="File",
     *      description="Profile image file",
     *      type="string",
     *      format="binary"
     * )
     * @var \Illuminate\Http\UploadedFile
     */
    public $file;

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
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'mobile' => 'required|digits:10',
            'emergency_contact' => 'nullable|digits:10',
            'email' => 'required|email|string',
            'gender' => 'required|in:male,female',
            'marital_status' => 'nullable|in:yes,no',
            'height_feet' => 'nullable|integer',
            'height_inches' => 'nullable|integer',
            'weight' => 'nullable|integer',
            'arms' => 'nullable|integer',
            'fat' => 'nullable|integer',
            'chest' => 'nullable|integer',
            'waist' => 'nullable|integer',
            'address' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'dob' => 'nullable|date_format:m/d/Y',
            'anniversary' => 'nullable|date_format:m/d/Y',
            'password' => 'nullable|string|min:6',
            'file' => 'nullable|file|mimes:jpg,jpeg,png'
        ];
    }
}
