@if($defaultDietPlan->isEmpty())
    <div class="actions">
        <a class="btn sbold dark" data-toggle="modal"
           data-target="#defaultDietPlans">Add New <i
                class="fa fa-plus"></i></a>
    </div>
    <div class="modal" tabindex="-1" id="defaultDietPlans" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="font-weight: 600;" class="modal-title">
                        Default Diet</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('gym-admin.createDefaultDietPlan') }}"
                      method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
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
                        <button type="submit" class="btn btn-primary">Create
                            Default Diet Plan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@else
    @foreach($defaultDietPlan as $defaultDiet)
        @php
            $arr['days'] = unserialize($defaultDiet->days);
            $arr['breakfast'] = json_decode($defaultDiet->breakfast,true);
            $arr['lunch'] = json_decode($defaultDiet->lunch,true);
            $arr['dinner'] = json_decode($defaultDiet->dinner,true);
            $arr['meal_4'] = json_decode($defaultDiet->meal_4,true);
            $arr['meal_5'] = json_decode($defaultDiet->meal_5,true);
        @endphp
        <div class="actions">
            <a href="#" class="btn btn-primary" data-toggle="modal"
               data-target="#defaultDietPlanEdit">Edit <i
                    class="fa fa-edit"></i></a>
            <a class="btn btn-sm btn-danger client-diet-remove"
                data-client_diet_url="{{route('gym-admin.deleteDietPlan',$defaultDiet->id)}}"
                style="font-size: 12px;"
                href="#">Delete<i
                    class="fa fa-trash"></i></a>
            <a href="{{route('gym-admin.downloadDietPlan',$defaultDiet->id)}}"
               class="btn btn-success">Download
                <i class="fa fa-download"></i></a>
            <a class="btn btn-warning"
               onclick="printDiv('defaultDietPrint')">Print <i
                    class="fa fa-print"></i></a>
        </div>
        {{-- listing the existing Default Diet Plan --}}
        <div id="defaultDietPrint">
            <h4 style="visibility: hidden">Default Diet Plan</h4>
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
                @if(!$defaultDiet->client_id)
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
                @endif
                </tbody>
            </table>
        </div>
        {{-- Default Diet Plan Edit --}}
        <div class="modal" id="defaultDietPlanEdit" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 style="font-weight: 600;" class="modal-title">
                            Update Default Diet Plan</h4>
                        <button type="button" class="close"
                                data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('gym-admin.updateDefaultDietPlan',$defaultDiet->id)}}"
                          method="post">
                        {{csrf_field()}}
                        <div class="modal-body">
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
                                    <td><input type="hidden" value="sunday"
                                               name="days[]">Sunday
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{($arr['breakfast'][0])}}"
                                                  name="sunday[breakfast]"
                                                  required>{{$arr['breakfast'][0]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['lunch'][0]}}"
                                                  name="sunday[lunch]"
                                                  required>{{$arr['lunch'][0]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['dinner'][0]}}"
                                                  name="sunday[dinner]"
                                                  required>{{$arr['dinner'][0]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_4'][0]}}"
                                                  name="sunday[meal_4]"
                                                  required>{{$arr['meal_4'][0]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_5'][0]}}"
                                                  name="sunday[meal_5]"
                                                  required>{{$arr['meal_5'][0]}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="hidden" value="monday"
                                               name="days[]">Monday
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['breakfast'][1]}}"
                                                  name="monday[breakfast]"
                                                  required>{{$arr['breakfast'][1]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['lunch'][1]}}"
                                                  name="monday[lunch]"
                                                  required>{{$arr['lunch'][1]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['dinner'][1]}}"
                                                  name="monday[dinner]"
                                                  required>{{$arr['dinner'][1]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_4'][0]}}"
                                                  name="monday[meal_4]"
                                                  required>{{$arr['meal_4'][0]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_5'][0]}}"
                                                  name="monday[meal_5]"
                                                  required>{{$arr['meal_5'][0]}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="hidden" value="tuesday"
                                               name="days[]">Tuesday
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['breakfast'][2]}}"
                                                  name="tuesday[breakfast]"
                                                  required>{{$arr['breakfast'][2]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['lunch'][2]}}"
                                                  name="tuesday[lunch]"
                                                  required>{{$arr['lunch'][2]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['dinner'][2]}}"
                                                  name="tuesday[dinner]"
                                                  required>{{$arr['dinner'][2]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_4'][0]}}"
                                                  name="tuesday[meal_4]"
                                                  required>{{$arr['meal_4'][0]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_5'][0]}}"
                                                  name="tuesday[meal_5]"
                                                  required>{{$arr['meal_5'][0]}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="hidden"
                                               value="wednesday"
                                               name="days[]">Wednesday
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['breakfast'][3]}}"
                                                  name="wednesday[breakfast]"
                                                  required>{{$arr['breakfast'][3]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['lunch'][3]}}"
                                                  name="wednesday[lunch]"
                                                  required>{{$arr['lunch'][3]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['dinner'][3]}}"
                                                  name="wednesday[dinner]"
                                                  required>{{$arr['dinner'][3]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_4'][0]}}"
                                                  name="wednesday[meal_4]"
                                                  required>{{$arr['meal_4'][0]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_5'][0]}}"
                                                  name="wednesday[meal_5]"
                                                  required>{{$arr['meal_5'][0]}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="hidden"
                                               value="thursday"
                                               name="days[]">Thursday
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['breakfast'][4]}}"
                                                  name="thursday[breakfast]"
                                                  required>{{$arr['breakfast'][4]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['lunch'][4]}}"
                                                  name="thursday[lunch]"
                                                  required>{{$arr['lunch'][4]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['dinner'][4]}}"
                                                  name="thursday[dinner]"
                                                  required>{{$arr['dinner'][4]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_4'][0]}}"
                                                  name="thursday[meal_4]"
                                                  required>{{$arr['meal_4'][0]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_5'][0]}}"
                                                  name="thursday[meal_5]"
                                                  required>{{$arr['meal_5'][0]}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="hidden" value="friday"
                                               name="days[]">Friday
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['breakfast'][5]}}"
                                                  name="friday[breakfast]"
                                                  required>{{$arr['breakfast'][6]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['lunch'][5]}}"
                                                  name="friday[lunch]"
                                                  required>{{$arr['lunch'][5]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['dinner'][5]}}"
                                                  name="friday[dinner]"
                                                  required>{{$arr['dinner'][5]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_4'][0]}}"
                                                  name="friday[meal_4]"
                                                  required>{{$arr['meal_4'][0]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_5'][0]}}"
                                                  name="friday[meal_5]"
                                                  required>{{$arr['meal_5'][0]}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="hidden"
                                               value="saturday"
                                               name="days[]">Saturday
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['breakfast'][6]}}"
                                                  name="saturday[breakfast]"
                                                  required>{{$arr['breakfast'][6]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['lunch'][6]}}"
                                                  name="saturday[lunch]"
                                                  required>{{$arr['lunch'][6]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['dinner'][6]}}"
                                                  name="saturday[dinner]"
                                                  required>{{$arr['dinner'][6]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_4'][0]}}"
                                                  name="saturday[meal_4]"
                                                  required>{{$arr['meal_4'][0]}}</textarea>
                                    </td>
                                    <td>
                                        <textarea class="form-control"
                                                  value="{{$arr['meal_5'][0]}}"
                                                  name="saturday[meal_5]"
                                                  required>{{$arr['meal_5'][0]}}</textarea>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif
