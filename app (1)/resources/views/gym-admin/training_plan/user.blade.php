<div class="col-md-12">
    <div class="actions">
        <a href="" class="btn sbold dark" data-toggle="modal" data-target="#clientTrainingPlans">Add New <i class="fa fa-plus"></i></a>
    </div>
    
    {{-- client Training Plan Details --}}
    <div class="table-responsive" style="padding: 15px">
        <table class="table table-striped" id="paymentTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Level</th>
                    <th>Activities</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($TrainingPlan as $clientTraining)
                    @if($clientTraining->client_id)
                        @php
                            $arr['days'] = json_decode($clientTraining->days,true);
                            $arr['activities'] = json_decode($clientTraining->activity,true);
                            $arr['sets'] = json_decode($clientTraining->sets,true);
                            $arr['repetition'] = json_decode($clientTraining->repetition,true);
                            $arr['weights'] = json_decode($clientTraining->weights,true);
                            $arr['restTime'] = json_decode($clientTraining->restTime,true);
                            $arr['startDate'] = json_decode($clientTraining->startDate,true);
                            $arr['endDate'] = json_decode($clientTraining->endDate,true);
                            $j = count($arr['activities']);
                        @endphp
                        <tr>
                            @foreach($clients as $client)
                                @if($client->id == $clientTraining->client_id)
                                    <td style="width: 15%">{{ $client->first_name.' '. $client->middle_name.' '. $client->last_name }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $clientTraining->level }}</td>
                                    <td style="width: 20%;">
                                        @for($i=0;$i<$j;$i++)
                                            {{$arr['activities'][$i] . ',' }}
                                        @endfor
                                    </td>
                                    <td style="width: 38%">
                                        <a class="btn btn-sm btn-success" data-toggle="modal" data-target="#clientModal{{$client->id}}" style="font-size: 12px;">View <i class="fa fa-eye"></i></a>
                                        <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#clientEditModal{{$client->id}}" style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger user-remove" style="font-size: 12px;" data-user-url="{{route('gym-admin.deleteTrainingPlan',$clientTraining->id)}}">Delete<i class="fa fa-trash"></i></a>
                                        
                                        @include('gym-admin.training_plan.show_user_plan')
                                        <div class="modal fade" id="clientEditModal{{$client->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" style="font-weight: 600;">Edit Plan for {{ $client->first_name.' '. $client->middle_name.' '. $client->last_name }}</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{route('gym-admin.updateClientTrainingPlan',$clientTraining->id)}}" method="POST">
                                                        {{csrf_field()}}
                                                        <input type="hidden" value="{{$client->id}}" name="client_id">
                                                        <div class="modal-body" id="client{{$client->id}}">
                                                            {{-- listing the selected Client Training Plan --}}
                                                            <div class="form-row">
                                                                <label for="trainingLevel"><h4>Select Level</h4></label>
                                                                <select id="trainingLevel" class="form-control bs-select clientEdit" data-live-search="true" data-size="8" name="level" required>
                                                                    <option value="">Select Level</option>
                                                                    <option value="{{$clientTraining->level}}">{{$clientTraining->level}}</option>
                                                                </select>
                                                                <div id="clientTrainingPlanEditBox-{{$clientTraining->id}}"></div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="clientTrainingPlans" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('gym-admin.createDefaultTrainingPlan') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 style="font-weight: 600;" class="modal-title">Create Plan for Client </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="form-row">
                        <label for="clientName"><h5>Select Client</h5></label>
                        <select class="bs-select form-control" data-live-search="true" data-size="8" id="clientName" name="client_id" required>
                            <option value="">Select Clients</option>
                            @foreach($clients as $client)
                                <option value="{{$client->id}}">{{$client->fullName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="trainingLevel"><h5>Select Level</h5></label>
                        <select id="trainingLevel" class="bs-select form-control clientLevel" data-live-search="true" data-size="8" name="level" required>
                            <option value="">Select Level</option>
                            @foreach($levelActivity as $activity)
                            <option value="{{$activity->level}}">{{$activity->level}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="levelActivityDataFormClient"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>