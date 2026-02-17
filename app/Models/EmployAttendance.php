<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployAttendance extends Model
{
    use HasFactory;

    protected $table = "employ_attendances";

    protected $dates = ['check_in','check_out'];

    protected $guarded = ['id'];

    public static function reportRules($action)
    {
        $rules = [
            'defaulter'  => [
                'days'       => 'required',
                'date_range' => 'required'
            ],
            'attendance' => [
                'date_range' => 'required',
                'cat'        => 'required',
            ]
        ];
        return $rules[$action];
    }

    public static function attendanceByDate($date, $search, $branchID)
    {
        return Employ::select(
            'employes.first_name',
            'employes.middle_name',
            'employes.last_name',
            'employ_attendances.check_in',
            'employ_attendances.check_out',
            'employ_attendances.status',
            'employes.image',
            'employ_attendances.id as checkin_id',
            DB::raw('(select count(`employes.id`) from employ_attendances where employes.id=employ_attendances.client_id) as total_checkin')
        )
            ->where('employes.detail_id', $branchID)
            ->leftJoin(
                'employ_attendances',
                function ($join) use ($date) {
                    $join->on('employes.id', '=', 'employ_attendances.client_id')
                        ->where(DB::raw('DATE(employ_attendances.check_in)'), '=', $date);
                }
            )
            ->where(
                function ($query) use ($search) {
                    if ($search != '') {
                        $query->where('employes.first_name', 'LIKE', '%' . $search . '%');
                        $query->orWhere('employes.middle_name', 'LIKE', '%' . $search . '%');
                        $query->orWhere('employes.last_name', 'LIKE', '%' . $search . '%');
                    }
                }
            )
            ->orderBy('employes.first_name', 'asc');
    }

    public static function clientAttendanceByDate($date, $search, $detail_id)
    {
        return Employ::leftJoin(
            'employ_attendances', function ($join) use ($date) {
            $join->on('employes.id', '=', 'employ_attendances.client_id')
                ->whereDate('employ_attendances.check_in', '=', $date);
        })
            ->where('employes.detail_id', $detail_id)
            ->select(
                'employes.id',
                'employes.first_name',
                'employes.middle_name',
                'employes.last_name',
                'employ_attendances.check_in',
                'employ_attendances.check_out',
                'employ_attendances.status',
                'employes.image',
                'employ_attendances.id as checkin_id',
                DB::raw('(select count(`id`) from employ_attendances where employes.id=employ_attendances.client_id) as total_checkin')
            )
            ->where(
                function ($query) use ($search) {
                    if ($search != '') {
                        $query->where('employes.first_name', 'LIKE', '%' . $search . '%');
                        $query->orWhere('employes.middle_name', 'LIKE', '%' . $search . '%');
                        $query->orWhere('employes.last_name', 'LIKE', '%' . $search . '%');
                    }
                }
            )
            ->orderBy('employes.first_name', 'asc');

    }

    public static function attendanceByDateCount($date, $businessID)
    {
        return EmployAttendance::join('employes', 'employes.id', '=', 'employ_attendances.client_id')
            ->where(DB::raw("DATE(employ_attendances.check_in)"), $date)
            ->where('business_customers.detail_id', $businessID)
            ->count();
    }

    public static function markAttendance($clientId, $date, $status)
    {
        return EmployAttendance::firstOrCreate(['client_id' => $clientId, 'check_in' => $date, 'status' => $status]);
    }
}
