<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\EmployLeave;
use App\Models\LeaveType;
use App\Models\Employ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class EmployLeaveController extends GymAdminBaseController
{
    public function index()
    {
        if (!$this->data['user']->can("view_leaves")) {
            return App::abort(401);
        }
        $this->data['title'] = "Employ Leave";
        $this->data['leaveType'] = LeaveType::where('branch_id', $this->data['user']->detail_id)->get();
        $this->data['employees'] = Employ::where('detail_id', '=', $this->data['user']->detail_id)->get();

        // Allowed days per leave type
        $allowedByType = LeaveType::where('branch_id', $this->data['user']->detail_id)
            ->pluck('days', 'name')
            ->toArray();

        // Fetch leaves with employee names
        $leaves = EmployLeave::join('employes', 'employ_leaves.employ_id', '=', 'employes.id')
            ->select('employ_leaves.*', 'employes.first_name', 'employes.middle_name', 'employes.last_name')
            ->where('employ_leaves.branch_id', $this->data['user']->detail_id)
            ->whereNull('employ_leaves.deleted_at')
            ->whereNull('employes.deleted_at')
            ->orderByDesc('employ_leaves.startDate')
            ->get();

        // Used days per employee + leave type
        $usedSums = EmployLeave::select('employ_id', 'leaveType', DB::raw('SUM(days) as used_days'))
            ->where('branch_id', $this->data['user']->detail_id)
            ->whereNull('deleted_at')
            ->groupBy('employ_id', 'leaveType')
            ->get();

        $usedMap = [];
        foreach ($usedSums as $s) {
            $usedMap[$s->employ_id . '|' . $s->leaveType] = (int) $s->used_days;
        }

        foreach ($leaves as $row) {
            $key = $row->employ_id . '|' . $row->leaveType;
            $allowed = (int) ($allowedByType[$row->leaveType] ?? 0);
            $used = (int) ($usedMap[$key] ?? 0);
            $row->remaining_days = max(0, $allowed - $used);
        }
        $this->data['employLeaves'] = $leaves;

        return view('gym-admin.employ.leaves.index', $this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("add_leave")) {
            return App::abort(401);
        }
        $request->validate([
            'employ_id' => 'required|exists:employes,id',
            'leaveType' => 'required',
            'days' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $leaveType = LeaveType::where('name',$request->get('leaveType'))->first();
        if(is_null($leaveType)){
            return redirect()->route('gym-admin.employ.showLeave')->with('message', 'Leave Type Not Found');
        }

        // Prevent duplicate/overlapping entry for same employee, type and dates
        $start = $request->get('startDate');
        $end = $request->get('endDate');
        $duplicateExists = EmployLeave::where('branch_id', $this->data['user']->detail_id)
            ->where('employ_id', $request->get('employ_id'))
            ->where('leaveType', $request->get('leaveType'))
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('startDate', [$start, $end])
                    ->orWhereBetween('endDate', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('startDate', '<=', $start)
                            ->where('endDate', '>=', $end);
                    });
            })
            ->exists();
        if ($duplicateExists) {
            return redirect()->back()->with('message', 'Duplicate leave exists for same employee, type and dates.');
        }

        $leave = new EmployLeave();
        $leave->branch_id = $this->data['user']->detail_id;
        $leave->employ_id = $request->get('employ_id');
        $leave->leaveType = $request->get('leaveType');
        $leave->days = $request->get('days');
        $leave->startDate = $request->get('startDate');
        $leave->endDate = $request->get('endDate');
        $leave->leaveDays = $leaveType->days;
        $leave->save();

        return redirect()->route('gym-admin.employ.showLeave')->with('message', 'Employ Leave Added Successfully');
    }

    public function update(Request $request, $id)
    {
        if (!$this->data['user']->can("edit_leave")) {
            return App::abort(401);
        }
        $leave = EmployLeave::findOrFail($id);

        $request->validate([
            'employ_id' => 'required|integer',
            'leaveType' => 'required',
            'days' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $leaveType = LeaveType::where('name',$request->get('leaveType'))->first();
        if(is_null($leaveType)){
            return redirect()->route('gym-admin.employ.showLeave')->with('message', 'Leave Type Not Found');
        }

        // Prevent duplicate/overlapping entry for same employee, type and dates (excluding current)
        $start = $request->get('startDate');
        $end = $request->get('endDate');
        $duplicateExists = EmployLeave::where('branch_id', $this->data['user']->detail_id)
            ->where('employ_id', $request->get('employ_id'))
            ->where('leaveType', $request->get('leaveType'))
            ->where('id', '!=', $id)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('startDate', [$start, $end])
                    ->orWhereBetween('endDate', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('startDate', '<=', $start)
                            ->where('endDate', '>=', $end);
                    });
            })
            ->exists();
        if ($duplicateExists) {
            return redirect()->back()->with('message', 'Duplicate leave exists for same employee, type and dates.');
        }
        $leave->employ_id = $request->get('employ_id');
        $leave->leaveType = $request->get('leaveType');
        $leave->days = $request->get('days');
        $leave->startDate = $request->get('startDate');
        $leave->endDate = $request->get('endDate');
        $leave->leaveDays = $leaveType->days;
        $leave->save();

        return redirect()->route('gym-admin.employ.showLeave')->with('message', 'Employ Leave Updated Successfully');
    }

    public function destroy(Request $request, $id)
    {
        if (!$this->data['user']->can("delete_leave")) {
            return App::abort(401);
        }
        $leave = EmployLeave::find($id);
        if (!$leave) {
            return Reply::error("Leave not found.");
        }
        $leave->delete();
        return Reply::success("Employ Leave deleted successfully.");
    }
}


