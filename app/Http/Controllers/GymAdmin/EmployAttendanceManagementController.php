<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\EmployAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Employ;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use DataTables;

class EmployAttendanceManagementController extends GymAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['attendanceMenu'] = 'active';
    }

    public function index() {

        $this->data['title'] = "Employee Attendance";

        return view('gym-admin.employ.attendance.index', $this->data);
    }

    public function create() {
        if (!$this->data['user']->can("add_attendance")) {
            return App::abort(401);
        }
        $this->data['title'] = "Employee Attendance";
        return view('gym-admin.employ.attendance.create', $this->data);
    }

    public function markAttendance(Request $request) {
        if (!$this->data['user']->can("add_attendance")) {
            return App::abort(401);
        }

        $check_indate = Carbon::createFromFormat('d/M/Y H:ia', request()->get('date'))->format('Y-m-d H:i:s');
        $data = EmployAttendance::markAttendance($request->input('clientId'), $check_indate,'arrived' );
        return Reply::successWithData("Attendance marked successfully.", ['id' => $data->id]);
    }

    public function checkin($Id) {
        $this->data['id'] = $Id;
        return View::make('gym-admin.employ.attendance.checkin', $this->data);
    }

    public function checkout($Id) {
        $this->data['id'] = $Id;
        return View::make('gym-admin.employ.attendance.checkout', $this->data);
    }

    public function markCheckout(Request $request) {
        if (!$this->data['user']->can("add_attendance")) {
            return App::abort(401);
        }
        $date = Carbon::createFromFormat('d/M/Y H:ia', request()->get('date'))->format('Y-m-d H:i:s');
        $data = EmployAttendance::find($request->input('clientId'));
        $data->check_out = $date;
        $data->update();
        return Reply::successWithData("Attendance checkout successfully.", ['id' => $data->id]);
    }

    public function destroy($id) {
        EmployAttendance::destroy($id);
        return Reply::success("Checkin deleted successfully.");

    }

    public function ajax_create(Request $request) {
        if (!$this->data['user']->can("add_attendance")) {
            return App::abort(401);
        }

        $date = Carbon::createFromFormat('d/M/Y', $request->date)->format('Y-m-d');
        $search = $request->search_data;
        $client_attandence = EmployAttendance::clientAttendanceByDate($date, $search,$this->data['user']->detail_id)->get();
        return Datatables::of($client_attandence)
            ->editColumn('first_name', function ($row) {
                return view('gym-admin.employ.attendance.ajaxview', ['row' => $row, 'imageURL' => $this->data['profileHeaderPath'], 'gymSettings' => $this->data['gymSettings'], 'defaultImageURL'=> $this->data['profilePath']])->render();
            })
            ->rawColumns(['first_name'])
            ->make();
    }

    public function show($id)
    {
        $this->data['attendanceMenu'] = 'active';
        $this->data['attendance'] = EmployAttendance::where('client_id', '=', $id)
            ->get();
        $this->data['employe'] = Employ::find($id);

        return view('gym-admin.employ.attendance.show', $this->data);
    }

}
