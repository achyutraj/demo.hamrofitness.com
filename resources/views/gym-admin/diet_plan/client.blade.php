<div class="col-md-12">
    <div class="actions"
         @if($defaultDietPlan->isEmpty()) style="margin-top: -3rem;"
         @else style="margin-top: -50rem;"@endif>
        <a href="" class="btn sbold dark" data-toggle="modal"
           data-target="#clientDietPlans">Add New <i
                class="fa fa-plus"></i></a>
    </div>
    <div class="modal fade" id="clientDietPlans" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form action="{{ route('gym-admin.createDefaultDietPlan') }}"
                      method="POST">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <h4 style="font-weight: 600;" class="modal-title">
                            Client Diet Plans</h4>
                        <button type="button" class="close"
                                data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <select class="bs-select" name="client_id"
                                    style="width:100%;min-height:38px !important;"
                                    required>
                                <option value="">Select Clients</option>
                                @foreach ($selected_clients as $client)
                                    <option value="{{$client->id}}">{{$client->fullName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <table class="table table-striped">

                            <thead>
                            <tr>
                                <th>Days</th>
                                <th>Meal1</th>
                                <th>Meal2</th>
                                <th>Meal3</th>
                                <th>Meal4</th>
                                <th>Meal5</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="hidden" value="sunday"
                                           name="days[]">Sunday
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="sunday[breakfast]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="sunday[lunch]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="sunday[dinner]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="sunday[meal_4]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="sunday[meal_5]"
                                                                                          required></textarea>
                                </td>

                            </tr>
                            <tr>
                                <td><input type="hidden" value="monday"
                                           name="days[]">Monday
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="monday[breakfast]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="monday[lunch]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="monday[dinner]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="monday[meal_4]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="monday[meal_5]"
                                                                                          required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="hidden" value="tuesday"
                                           name="days[]">Tuesday
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="tuesday[breakfast]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="tuesday[lunch]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="tuesday[dinner]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="tuesday[meal_4]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="tuesday[meal_5]"
                                                                                          required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="hidden" value="wednesday"
                                           name="days[]">Wednesday
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="wednesday[breakfast]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="wednesday[lunch]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="wednesday[dinner]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="wednesday[meal_4]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="wednesday[meal_5]"
                                                                                          required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="hidden" value="thursday"
                                           name="days[]">Thursday
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="thursday[breakfast]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="thursday[lunch]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="thursday[dinner]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="thursday[meal_4]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="thursday[meal_5]"
                                                                                          required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="hidden" value="friday"
                                           name="days[]">Friday
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="friday[breakfast]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="friday[lunch]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="friday[dinner]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="friday[meal_4]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="friday[meal_5]"
                                                                                          required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="hidden" value="saturday"
                                           name="days[]">Saturday
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="saturday[breakfast]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="saturday[lunch]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="saturday[dinner]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="saturday[meal_4]"
                                                                                          required></textarea>
                                </td>
                                <td>
                                                                                <textarea class="form-control"
                                                                                          name="saturday[meal_5]"
                                                                                          required></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-primary">Save
                            changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="table-responsive" style="padding: 15px;">
        <table class="table table-striped" id="paymentTable">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($dietPlan as $clientDiet)
                @if($clientDiet->client_id)
                    @php
                        $arr['days'] = unserialize($clientDiet->days);
                        $arr['breakfast'] = json_decode($clientDiet->breakfast,true);
                        $arr['lunch'] = json_decode($clientDiet->lunch,true);
                        $arr['dinner'] = json_decode($clientDiet->dinner,true);
                        $arr['meal_4'] = json_decode($clientDiet->meal_4,true);
                        $arr['meal_5'] = json_decode($clientDiet->meal_5,true);
                    @endphp
                    <tr>
                        @foreach($clients as $client)
                            @if($client->id == $clientDiet->client_id)
                                <td style="width: 15%">{{ $client->first_name.' '. $client->middle_name.' '. $client->last_name }}</td>
                                <td>{{ $client->email }}</td>
                                <td style="width: 38%">
                                    <a class="btn btn-sm btn-success"
                                       data-toggle="modal"
                                       data-target="#clientModal{{$client->id}}"
                                       style="font-size: 12px;">View
                                        <i class="fa fa-eye"></i></a>
                                    <a class="btn btn-sm btn-primary"
                                       data-toggle="modal"
                                       data-target="#clientEditModal{{$client->id}}"
                                       style="font-size: 12px;">Edit
                                        <i class="fa fa-edit"></i></a>
                                    <a class="btn btn-sm btn-danger client-diet-remove"
                                        data-client_diet_url="{{route('gym-admin.deleteDietPlan',$clientDiet->id)}}"
                                       style="font-size: 12px;"
                                       href="#">Delete<i
                                            class="fa fa-trash"></i></a>
                                    <div class="modal fade"
                                         id="clientModal{{$client->id}}"
                                         tabindex="-1"
                                         role="dialog"
                                         aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-md"
                                             role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"
                                                        style="font-weight: 600;">
                                                        Diet Plan
                                                        for {{ $client->first_name.' '. $client->middle_name.' '. $client->last_name }}
                                                        <span class="pull-right">
                                                            <a class="btn btn-sm btn-success"
                                                               href="{{route('gym-admin.downloadDietPlan',$clientDiet->id)}}">Download <i
                                                                    class="fa fa-download"></i></a>
                                                            <a class="btn btn-sm btn-warning"
                                                               onclick="printDiv('client{{$client->id}}')">Print <i
                                                                    class="fa fa-print"></i></a>
                                                        </span>
                                                    </h4>
                                                    <button type="button"
                                                            class="close"
                                                            data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body"
                                                     id="client{{$client->id}}">
                                                    <h4 style="font-weight: 600; visibility: hidden; text-align: center;">
                                                        Diet Plan
                                                        for {{ $client->first_name.' '. $client->middle_name.' '. $client->last_name }}</h4>
                                                    {{-- listing the selected Client Diet Plan --}}
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>Days</th>
                                                            <th>Meal1</th>
                                                            <th>Meal2</th>
                                                            <th>Meal3</th>
                                                            <th>Meal4</th>
                                                            <th>Meal5</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @for($i = 0; $i<7; $i++)
                                                            <tr>
                                                                <td>{{ucfirst($arr['days'][$i])}}</td>
                                                                <td>{{ucfirst($arr['breakfast'][$i])}}</td>
                                                                <td>{{ucfirst($arr['lunch'][$i])}}</td>
                                                                <td>{{ucfirst($arr['dinner'][$i])}}</td>
                                                                <td>{{ucfirst($arr['meal_4'][$i])}}</td>
                                                                <td>{{ucfirst($arr['meal_5'][$i])}}</td>
                                                            </tr>
                                                        @endfor
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button"
                                                            class="btn btn-secondary"
                                                            data-dismiss="modal">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade"
                                         id="clientEditModal{{$client->id}}"
                                         tabindex="-1"
                                         role="dialog"
                                         aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-md"
                                             role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"
                                                        style="font-weight: 600;">
                                                        Edit Diet
                                                        Plan
                                                        for {{ $client->first_name.' '. $client->middle_name.' '. $client->last_name }}</h4>
                                                    <button type="button"
                                                            class="close"
                                                            data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{route('gym-admin.updateClientDietPlan',$clientDiet->id)}}"
                                                      method="POST">
                                                    {{csrf_field()}}
                                                    <input type="hidden"
                                                           value="{{$client->id}}"
                                                           name="client_id">
                                                    <div class="modal-body"
                                                         id="client{{$client->id}}">
                                                        {{-- listing the selected Client Diet Plan --}}
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>Days</th>
                                                                <th>Meal1</th>
                                                                <th>Meal2</th>
                                                                <th>Meal3</th>
                                                                <th>Meal4</th>
                                                                <th>Meal5</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden"
                                                                           value="sunday"
                                                                           name="days[]">Sunday
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['breakfast'][0]}}"
                                                                        name="sunday[breakfast]"
                                                                        required>{{$arr['breakfast'][0]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['lunch'][0]}}"
                                                                        name="sunday[lunch]"
                                                                        required>{{$arr['lunch'][0]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['dinner'][0]}}"
                                                                        name="sunday[dinner]"
                                                                        required>{{$arr['dinner'][0]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_4'][0]}}"
                                                                        name="sunday[meal_4]"
                                                                        required>{{$arr['meal_4'][0]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_5'][0]}}"
                                                                        name="sunday[meal_5]"
                                                                        required>{{$arr['meal_5'][0]}}</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden"
                                                                           value="monday"
                                                                           name="days[]">Monday
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['breakfast'][1]}}"
                                                                        name="monday[breakfast]"
                                                                        required>{{$arr['breakfast'][1]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['lunch'][1]}}"
                                                                        name="monday[lunch]"
                                                                        required>{{$arr['lunch'][1]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['dinner'][1]}}"
                                                                        name="monday[dinner]"
                                                                        required>{{$arr['dinner'][1]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_4'][0]}}"
                                                                        name="monday[meal_4]"
                                                                        required>{{$arr['meal_4'][0]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_5'][0]}}"
                                                                        name="monday[meal_5]"
                                                                        required>{{$arr['meal_5'][0]}}</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden"
                                                                           value="tuesday"
                                                                           name="days[]">Tuesday
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['breakfast'][2]}}"
                                                                        name="tuesday[breakfast]"
                                                                        required>{{$arr['breakfast'][2]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['lunch'][2]}}"
                                                                        name="tuesday[lunch]"
                                                                        required>{{$arr['lunch'][2]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['dinner'][2]}}"
                                                                        name="tuesday[dinner]"
                                                                        required>{{$arr['dinner'][2]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_4'][0]}}"
                                                                        name="tuesday[meal_4]"
                                                                        required>{{$arr['meal_4'][0]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_5'][0]}}"
                                                                        name="tuesday[meal_5]"
                                                                        required>{{$arr['meal_5'][0]}}</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden"
                                                                           value="wednesday"
                                                                           name="days[]">Wednesday
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['breakfast'][3]}}"
                                                                        name="wednesday[breakfast]"
                                                                        required>{{$arr['breakfast'][3]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['lunch'][3]}}"
                                                                        name="wednesday[lunch]"
                                                                        required>{{$arr['lunch'][3]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['dinner'][3]}}"
                                                                        name="wednesday[dinner]"
                                                                        required>{{$arr['dinner'][3]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_4'][0]}}"
                                                                        name="wednesday[meal_4]"
                                                                        required>{{$arr['meal_4'][0]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_5'][0]}}"
                                                                        name="wednesday[meal_5]"
                                                                        required>{{$arr['meal_5'][0]}}</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden"
                                                                           value="thursday"
                                                                           name="days[]">Thursday
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['breakfast'][4]}}"
                                                                        name="thursday[breakfast]"
                                                                        required>{{$arr['breakfast'][4]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['lunch'][4]}}"
                                                                        name="thursday[lunch]"
                                                                        required>{{$arr['lunch'][4]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['dinner'][4]}}"
                                                                        name="thursday[dinner]"
                                                                        required>{{$arr['dinner'][4]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_4'][0]}}"
                                                                        name="thursday[meal_4]"
                                                                        required>{{$arr['meal_4'][0]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_5'][0]}}"
                                                                        name="thursday[meal_5]"
                                                                        required>{{$arr['meal_5'][0]}}</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden"
                                                                           value="friday"
                                                                           name="days[]">Friday
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['breakfast'][5]}}"
                                                                        name="friday[breakfast]"
                                                                        required>{{$arr['breakfast'][6]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['lunch'][5]}}"
                                                                        name="friday[lunch]"
                                                                        required>{{$arr['lunch'][5]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['dinner'][5]}}"
                                                                        name="friday[dinner]"
                                                                        required>{{$arr['dinner'][5]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_4'][0]}}"
                                                                        name="friday[meal_4]"
                                                                        required>{{$arr['meal_4'][0]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_5'][0]}}"
                                                                        name="friday[meal_5]"
                                                                        required>{{$arr['meal_5'][0]}}</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden"
                                                                           value="saturday"
                                                                           name="days[]">Saturday
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['breakfast'][6]}}"
                                                                        name="saturday[breakfast]"
                                                                        required>{{$arr['breakfast'][6]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['lunch'][6]}}"
                                                                        name="saturday[lunch]"
                                                                        required>{{$arr['lunch'][6]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['dinner'][6]}}"
                                                                        name="saturday[dinner]"
                                                                        required>{{$arr['dinner'][6]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_4'][0]}}"
                                                                        name="saturday[meal_4]"
                                                                        required>{{$arr['meal_4'][0]}}</textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea
                                                                        class="form-control"
                                                                        value="{{$arr['meal_5'][0]}}"
                                                                        name="saturday[meal_5]"
                                                                        required>{{$arr['meal_5'][0]}}</textarea>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit"
                                                                class="btn btn-primary">
                                                            Update
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-secondary"
                                                                data-dismiss="modal">
                                                            Close
                                                        </button>
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
            @empty

            @endforelse
            </tbody>
        </table>
    </div>
</div>
