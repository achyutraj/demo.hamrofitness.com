<div class="modal fade" id="clientModal{{$client->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="font-weight: 600;">Training Plan for {{ $client->first_name.' '. $client->middle_name.' '. $client->last_name }}
                    <span class="pull-right">
                    <a class="btn btn-sm btn-success" href="{{route('gym-admin.downloadClientTrainingPlan',$clientTraining->id)}}">Download <i class="fa fa-download"></i></a>
                    <a class="btn btn-sm btn-warning" onclick="printDiv('client{{$client->id}}')">Print <i class="fa fa-print"></i></a>
                </span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="client{{$client->id}}">
                <h4 style="font-weight: 600; visibility: hidden; text-align: center;">Training Plan for {{ $client->first_name.' '. $client->middle_name.' '. $client->last_name }}</h4>
                {{-- listing the selected Client Training Plan --}}
                <table class="table">
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Level</th>
                            <th>Days</th>
                            <th>Sets</th>
                            <th>Repetition</th>
                            <th>Weights</th>
                            <th>Rest Time</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i=0;$i<$j;$i++)
                            <tr>
                                <td>{{$arr['activities'][$i]}}</td>
                                <td>{{$clientTraining->level}}</td>
                                <td>
                                    @php
                                        $count = count($arr['days'][$i]);
                                    @endphp
                                    @for($k=0;$k<$count;$k++)
                                        {{$arr['days'][$i][$k]}},
                                    @endfor
                                </td>
                                <td>{{$arr['sets'][$i]}}</td>
                                <td>{{$arr['repetition'][$i]}}</td>
                                <td>{{$arr['weights'][$i]}} KGs</td>
                                <td>{{$arr['restTime'][$i]}} minutes</td>
                                <td>{{$arr['startDate'][$i]}}</td>
                                <td>{{$arr['endDate'][$i]}}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>