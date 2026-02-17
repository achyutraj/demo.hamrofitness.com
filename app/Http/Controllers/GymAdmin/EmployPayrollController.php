<?php

namespace App\Http\Controllers\GymAdmin;

use App\Models\Employ;
use App\Models\Payroll;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class EmployPayrollController extends GymAdminBaseController
{
    public function index()
    {
        if (!$this->data['user']->can("view_payrolls")) {
            return App::abort(401);
        }
        $this->data['title']     = "Payroll";
        $this->data['employees'] = Employ::where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['payroll']   = Payroll::whereHas('employes', function ($query) {
            $query->where('branch_id', $this->data['user']->detail_id);
        })->get();

        return view('gym-admin.employ.payroll.index', $this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("add_payroll")) {
            return App::abort(401);
        }
        $pay            = new Payroll();
        $pay->employ_id = $request->employ_id;
        $pay->salary    = $request->salary;
        $pay->allowance = $request->allowance;
        $pay->deduction = $request->deduction;
        $pay->total     = ($request->salary + $request->allowance) - $request->deduction;
        $pay->save();

        return redirect()->back();
    }

    public function add(Request $request, $id)
    {
        if (!$this->data['user']->can("edit_payroll")) {
            return App::abort(401);
        }
        $pay            = Payroll::findorfail($id);
        $pay->employ_id = $request->employ_id;
        $pay->salary    = $request->salary;
        $pay->allowance = $request->allowance;
        $pay->deduction = $request->deduction;
        $pay->total     = ($request->salary + $request->allowance) - $request->deduction;
        $pay->save();
        return redirect()->back();
    }
}
