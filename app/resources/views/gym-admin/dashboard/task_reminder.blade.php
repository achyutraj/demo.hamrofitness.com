@if(count($todayTasks) > 0)
<div class="page-content-inner">
    <div class="row card">
        <h3>Today Task</h3>
        <div class="card-body">
            @foreach($todayTasks as $task)
                    <?php
                    $status = 'info';
                    if($task->priority == 'high'){
                        $status = 'danger';
                    }elseif($task->priority == 'medium'){
                        $status = 'warning';
                    }
                    ?>
                    <div class="col-md-4 col-lg-4 col-xs-12">
                        <div class="task-reminder alert-{{ $status }}">
                            <a href="{{ route('gym-admin.task.index') }}" class="text-white">
                                <h4>{{ ucfirst($task->heading) }} </h4>
                                <p>Description: {{ $task->description }}</p>
                                <p>   Status: {{ $task->status }}</p>
                                 <p>   Deadline: {{ $task->deadline->toFormattedDateString() }}</p>
                            </a>
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
</div>
@endif
