<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Resources\Swagger\Attendance\GymClientAttendanceResource;
use App\Models\GymClientAttendance;

class CustomerAttendanceController extends CustomerBaseController
{
     /**
     * @OA\Get(
     *      path="/api/customer/attendance",
     *      operationId="attendance",
     *      tags={"Attendance"},
     *      security={{"passport": {}}},
     *      summary="Customer attendance list",
     *      description="Returns list of customer attendance",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/GymClientAttendanceResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function index()
    {
        $attendance = GymClientAttendance::where('client_id',$this->getCustomerData()->id)->orderBy('id', 'desc');
        return GymClientAttendanceResource::collection($attendance->paginate(10));
    }
}
