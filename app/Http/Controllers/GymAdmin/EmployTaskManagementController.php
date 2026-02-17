<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\Employ;
use App\Models\EmployTask;
use App\Http\Requests\GymAdmin\Tasks\StoreUpdateRequest;
use App\Models\MerchantBusiness;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class EmployTaskManagementController extends GymAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['taskMenu'] = 'active';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->data['user']->can("task")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Task Manager';
        $this->data['employees'] = Employ::where('detail_id',$this->data['user']->detail_id)->get();
        return View::make('gym-admin.employ.task.index', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateRequest $request)
    {
        //Check if reminder date isn't present day or past date
        if($request->reminder == 1 && Carbon::createFromFormat('m/d/Y', $request->deadline)->subDays($request->numberOfDays)->format('Y-m-d') <= Carbon::now()->format('Y-m-d')) {
            return Reply::error('Reminder should be set after the current date');
        }

        $task = new EmployTask();
        $task->employ_id = $request->employ_id;
        $task->detail_id = $this->data['user']->detail_id;
        $task->heading = $request->heading;
        $task->description = $request->description;
        $task->deadline = Carbon::createFromFormat('m/d/Y', $request->deadline)->format('Y-m-d');
        $task->status = $request->status;
        $task->priority = $request->priority;

        if($request->reminder == 1) {
            $task->reminder_date = Carbon::createFromFormat('m/d/Y', $request->deadline)->subDays($request->numberOfDays)->format('Y-m-d');
        } else {
            $task->reminder_date = $request->numberOfDays;
        }

        $task->save();

        return Reply::redirect(route('gym-admin.employTaskList.index'), 'Task added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = EmployTask::find($id);

        return Reply::dataOnly(['taskDetail' => $task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreUpdateRequest $request
     * @param  int $id
     * @return array
     */
    public function update(StoreUpdateRequest $request, $id)
    {
        //Check if reminder date isn't present day or past date
        if($request->reminder == 1 && Carbon::createFromFormat('m/d/Y', $request->deadline)->subDays($request->numberOfDays)->format('Y-m-d') <= Carbon::now()->format('Y-m-d')) {
            return Reply::error('Reminder should be set after the current date');
        }

        $task = EmployTask::findorfail($id);
        $task->employ_id = $request->employ_id;
        $task->detail_id = $this->data['user']->detail_id;
        $task->heading = $request->heading;
        $task->description = $request->description;
        $task->deadline = Carbon::createFromFormat('m/d/Y', $request->deadline)->format('Y-m-d');
        $task->status = $request->status;
        $task->priority = $request->priority;

        if($request->reminder == 1) {
            $task->reminder_date = Carbon::createFromFormat('m/d/Y', $request->deadline)->subDays($request->numberOfDays)->format('Y-m-d');
        } else {
            $task->reminder_date = $request->numberOfDays;
        }

        $task->save();

        return Reply::redirect(route('gym-admin.employTaskList.index'), 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $task = EmployTask::find($id);
        $task->delete();
        return Reply::success("Task removed successfully");
    }

    public function loadMoreTask(Request $request) {

        $tasks = EmployTask::select('id', 'employ_id', 'heading', 'description', 'deadline', 'status', 'priority', 'created_at')
            ->where('detail_id',$this->data['user']->detail_id)->latest();
        if($request->data == 'pending' || $request->data == 'complete') {
            if($request->sort == 'new') {
                $tasks = $tasks->where('status', '=', $request->data)->latest();
            } else if($request->sort == 'deadline') {
                $tasks = $tasks->where('status', '=', $request->data)
                    ->where('deadline', '<=', Carbon::now('Asia/Kathmandu')->format('Y-m-d'));
            } else {
                $tasks = $tasks->where('status', '=', $request->data);
            }

        } else if($request->data == 'low' || $request->data == 'high' || $request->data == 'medium') {

            if($request->sort == 'new') {
                $tasks = $tasks->where('priority', '=', $request->data)
                    ->latest();
            } else if($request->sort == 'deadline') {
                $tasks = $tasks->where('priority', '=', $request->data)
                    ->where('deadline', '<=', Carbon::now('Asia/Kathmandu')->format('Y-m-d'));
            } else {
                $tasks = $tasks->where('priority', '=', $request->data);
            }

        } else if($request->sort == 'new') {
            $tasks = $tasks->latest();
        } else if($request->sort == 'deadline') {
            $tasks = $tasks->where('deadline', '<=', Carbon::now('Asia/Kathmandu')->format('Y-m-d'));
        }

        $tasks = $tasks->get();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageSearchResults = $tasks->slice(($currentPage-1) * $perPage, $perPage)->all();
        $paginatedSearchResults= new LengthAwarePaginator($currentPageSearchResults, count($tasks), $perPage);
        $paginatedSearchResults->withPath('task');
        $data['tasks'] = $paginatedSearchResults;
        $data['currentPage'] = $currentPage;
        $data['profilePath'] = $this->data['profilePath'];

        return View::make('gym-admin.employ.task.loadmoretaskcard', $data);
    }
}
