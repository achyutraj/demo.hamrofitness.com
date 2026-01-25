<div class="actions">
    @if($defaultTrainingPlan->isEmpty())
        <a class="btn sbold dark" data-toggle="modal" data-target="#defaultTrainingPlans">Add New <i class="fa fa-plus"></i></a>
    @else
        <a class="btn sbold dark" data-toggle="modal" data-target="#defaultTrainingPlans">Add New <i class="fa fa-plus"></i></a>
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#defaultTrainingPlanEdit">Edit <i class="fa fa-edit"></i></a>
        <a href="#" data-default-url="{{route('gym-admin.deleteDefaultTrainingPlan')}}" class="btn btn-danger default-remove">Delete <i class="fa fa-trash"></i></a>
        <a href="{{route('gym-admin.downloadDefaultTrainingPlan')}}" class="btn btn-success">Download <i class="fa fa-download"></i></a>
        <a  class="btn btn-warning" onclick="printDiv('defaultTrainingPrint')">Print <i class="fa fa-print"></i></a>
    @endif
</div>

{{-- listing the existing Default Training Plan --}}
<div id="defaultTrainingPrint">
    <h4 style="visibility: hidden">Default Training Plan</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Activity</th>
                <th>Level</th>
                <th>Days</th>
                <th>Sets</th>
                <th>Repetitions</th>
                <th>Weights</th>
                <th>Rest Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($defaultTrainingPlan as $defaultTraining)
                @php
                    $arr['days'] = json_decode($defaultTraining->days,true);
                    $arr['activities'] = json_decode($defaultTraining->activity,true);
                    $arr['sets'] = json_decode($defaultTraining->sets,true);
                    $arr['repetition'] = json_decode($defaultTraining->repetition,true);
                    $arr['weights'] = json_decode($defaultTraining->weights,true);
                    $arr['restTime'] = json_decode($defaultTraining->restTime,true);
                    $j = count($arr['activities']);
                    $l = count($arr['days']);
                @endphp
                @for($k=0;$k<$j;$k++)
                    <tr>
                        <td>{{$arr['activities'][$k]}}</td>
                        <td>{{$defaultTraining->level}}</td>
                        <td>
                            @php
                                $count = count($arr['days'][$k]);
                            @endphp
                            @for($i=0;$i<$count;$i++)
                                {{$arr['days'][$k][$i]}},
                            @endfor
                        </td>
                        <td>{{$arr['sets'][$k]}}</td>
                        <td>{{$arr['repetition'][$k]}}</td>
                        <td>{{$arr['weights'][$k]}}</td>
                        <td>{{$arr['restTime'][$k]}}</td>
                    </tr>
                @endfor
            @endforeach
        </tbody>
    </table>
</div>

{{-- Default Training Plan Edit --}}
<div class="modal" id="defaultTrainingPlanEdit" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="font-weight: 600;" class="modal-title">Update Default Plan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="form-row">
                    <label for="trainingLevel"><h5>Select Level</h5></label>
                    <select id="trainingLevel" class="form-control bs-select levelEdit" data-live-search="true" data-size="8" name="level" required>
                        <option value="" selected>Select Level</option>
                        @foreach($levelActivity as $activity)
                            <option value="{{$activity->level}}">{{$activity->level}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="defaultTrainingPlanEditBox"></div>
        </div>
    </div>
</div>

{{-- Default Training Plan Create --}}
<div class="modal" tabindex="-1" id="defaultTrainingPlans" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="font-weight: 600;" class="modal-title">Create Default Plan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('gym-admin.createDefaultTrainingPlan') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-row">
                        <label for="trainingLevel"><h5>Select Level</h5></label>
                        <select id="trainingLevel" class="form-control bs-select level" data-live-search="true" data-size="8" name="level" required>
                            <option value="">Select Level</option>
                            @foreach($levelActivity as $activity))
                                <option value="{{$activity->level}}">{{$activity->level}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="levelActivityDataForm"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>