<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Imports\GymClientAttendanceImports;
use App\Models\GymClientAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use DataTables;
use Excel;


class GymAdminAttendanceController extends GymAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['attendanceMenu'] = 'active';
    }

    public function index() {

        $this->data['title'] = "Client Attendance";

        return view('gym-admin.attendance.index', $this->data);
    }

    public function create() {
        if (!$this->data['user']->can("add_attendance")) {
            return App::abort(401);
        }

        $this->data['title'] = "Client Attendance";
        return view('gym-admin.attendance.create', $this->data);
    }

    public function markAttendance(Request $request) {
        if (!$this->data['user']->can("add_attendance")) {
            return App::abort(401);
        }

        $date = Carbon::createFromFormat('d/M/Y H:ia', request()->get('date'))->format('Y-m-d H:i:s');
        $data = GymClientAttendance::markAttendance($request->input('clientId'), $date);
        return Reply::successWithData("Attendance marked successfully.", ['id' => $data->id]);
    }

    public function show($id){
        //nothing
    }

    public function checkin($Id) {
        $this->data['id'] = $Id;
        return View::make('gym-admin.attendance.checkin', $this->data);
    }

    public function checkout($Id) {
        $this->data['id'] = $Id;
        return View::make('gym-admin.attendance.checkout', $this->data);
    }

    public function markCheckout(Request $request) {
        if (!$this->data['user']->can("add_attendance")) {
            return App::abort(401);
        }
        $date = Carbon::createFromFormat('d/M/Y H:ia', request()->get('date'))->format('Y-m-d H:i:s');
        $data = GymClientAttendance::find($request->input('clientId'));
        $data->check_out = $date;
        $data->update();
        return Reply::successWithData("Attendance checkout successfully.", ['id' => $data->id]);
    }

    public function destroy($id) {
        GymClientAttendance::destroy($id);
        return Reply::success("Checkin deleted successfully.");

    }

    public function ajax_create(Request $request) {
        if (!$this->data['user']->can("add_attendance")) {
            return App::abort(401);
        }

        $date = Carbon::createFromFormat('d/M/Y', $request->date)->format('Y-m-d');
        $search = $request->search_data;
        $client_attandence = GymClientAttendance::clientAttendanceByDate($date, $search, $this->data['user']->detail_id)->get();
        return Datatables::of($client_attandence)
            ->editColumn('first_name', function ($row) {
                return view('gym-admin.attendance.ajaxview', ['row' => $row, 'imageURL' => $this->data['profileHeaderPath'], 'gymSettings' => $this->data['gymSettings']])->render();
            })
            ->rawColumns(['first_name'])
            ->make();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        $file = $request->file('file');
        Excel::import(new GymClientAttendanceImports, $file);

        return back()->with('success', 'Records imported successfully.');
    }
}
