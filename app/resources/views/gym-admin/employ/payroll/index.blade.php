@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Employs Payroll</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->

        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Employs Payroll</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" data-target="#addPayroll" data-toggle="modal"
                                       class="btn sbold dark"> Add New
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100"
                                   id="manage-branches">
                                <thead>
                                <tr>
                                    <th class="desktop"> Date</th>
                                    <th class="desktop"> Employ Name</th>
                                    <th class="desktop" style="width: 10%;"> Salary</th>
                                    <th class="desktop" style="width: 7%;"> Allowance</th>
                                    <th class="desktop" style="width: 7%;"> Deduction</th>
                                    <th class="desktop"> Net Pay</th>
                                    <th class="desktop"> Add</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($payroll as $pay)
                                    <tr>
                                        <td>{{$pay->created_at->toFormattedDateString()}}</td>
                                        <td>{{$pay->employes->fullName}}</td>
                                        <td>{{ $gymSettings->currency->acronym }} {{$pay->salary}}</td>
                                        <td>{{ $gymSettings->currency->acronym }} {{$pay->allowance}}</td>
                                        <td>{{ $gymSettings->currency->acronym }} {{$pay->deduction}}</td>
                                        <td>{{ $gymSettings->currency->acronym }} {{$pay->total}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn blue btn-xs dropdown-toggle" type="button"
                                                        data-toggle="dropdown"><i class="fa fa-gears"></i> <span
                                                            class="hidden-xs">Action</span>
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu pull-right" role="menu">
                                                        <li>
                                                            <a data-toggle="modal" data-target="#allowDeduc{{$pay->id}}"><i
                                                                        class="fa fa-plus"></i> Change Allowance and
                                                                Deduction</a>
                                                        </li>
                                                        <li>
                                                            <a data-toggle="modal" data-target="#editPayroll{{$pay->id}}"><i
                                                                        class="fa fa-edit"></i> Edit Payroll</a>
                                                        </li>
                                                </ul>
                                            </div>
                                            <div class="modal fade bs-modal-md in" id="allowDeduc{{$pay->id}}" role="dialog"
                                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-md" id="modal-data-application">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4>Change Allowance and Deduction from salary</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-hidden="true"></button>
                                                            <span class="caption-subject font-red-sunglo bold uppercase"
                                                                  id="modelHeading"></span>
                                                        </div>
                                                        <form action="{{route('gym-admin.employPayroll.add',$pay->id)}}"
                                                              method="post" enctype="multipart/form-data">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="employ_id"
                                                                   value="{{$pay->employ_id}}">
                                                            <input type="hidden" name="salary" value="{{$pay->salary}}">
                                                            <div class="modal-body">
                                                                <div class="form-group form-md-line-input col-md-6">
                                                                    <label><h4>Allowance</h4></label>
                                                                    <input type="number" class="form-control"
                                                                           placeholder="Allowance" name="allowance">
                                                                    <div class="form-control-focus"></div>
                                                                    <span class="help-block">Enter Allowance</span>
                                                                </div>
                                                                <div class="form-group form-md-line-input col-md-6">
                                                                    <label><h4>Deduction</h4></label>
                                                                    <input type="number" class="form-control"
                                                                           placeholder="Deduction" name="deduction">
                                                                    <div class="form-control-focus"></div>
                                                                    <span class="help-block">Deduction</span>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Update
                                                                </button>
                                                                <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>
                                            <div class="modal fade bs-modal-md in" id="editPayroll{{$pay->id}}" role="dialog"
                                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-md" id="modal-data-application">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4>Change Allowance and Deduction
                                                                from {{$pay->first_name .' '. $pay->middle_name . ' ' . $pay->last_name}}
                                                                salary</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-hidden="true"></button>
                                                            <span class="caption-subject font-red-sunglo bold uppercase"
                                                                  id="modelHeading"></span>
                                                        </div>
                                                        <form action="{{route('gym-admin.employPayroll.add',$pay->id)}}"
                                                              method="post" enctype="multipart/form-data">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="employ_id"
                                                                   value="{{$pay->employ_id}}">
                                                            <div class="modal-body">
                                                                <div class="form-group form-md-line-input col-md-4">
                                                                    <label><h4>Salary</h4></label>
                                                                    <input type="number" value="{{$pay->salary}}"
                                                                           class="form-control" placeholder="Salary"
                                                                           name="salary">
                                                                    <div class="form-control-focus"></div>
                                                                    <span class="help-block">Enter Salary</span>
                                                                </div>
                                                                <div class="form-group form-md-line-input col-md-4">
                                                                    <label><h4>Allowance</h4></label>
                                                                    <input type="number" value="{{$pay->allowance}}"
                                                                           class="form-control" placeholder="Allowance"
                                                                           name="allowance">
                                                                    <div class="form-control-focus"></div>
                                                                    <span class="help-block">Enter Allowance</span>
                                                                </div>
                                                                <div class="form-group form-md-line-input col-md-4">
                                                                    <label><h4>Deduction</h4></label>
                                                                    <input type="number" value="{{$pay->deduction}}"
                                                                           class="form-control" placeholder="Deduction"
                                                                           name="deduction">
                                                                    <div class="form-control-focus"></div>
                                                                    <span class="help-block">Deduction</span>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Update
                                                                </button>
                                                                <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->

        <div class="modal fade bs-modal-md in" id="addPayroll" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" id="modal-data-application">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Add Payroll For Employ</h4>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true"></button>
                        <span class="caption-subject font-red-sunglo bold uppercase"
                              id="modelHeading"></span>
                    </div>
                    <form action="{{route('gym-admin.employPayroll.store')}}"
                          method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group form-md-line-input col-md-12">
                                <label for="employName"><h4>Select Employ</h4></label>
                                <select class="form-control todo-taskbody-tags"
                                        id="employName" name="employ_id">
                                    <option></option>
                                    @foreach($employees as $employ)
                                        <option class="todo-username pull-left"
                                                value="{{$employ->id}}">{{$employ->fullName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group form-md-line-input col-md-4">
                                <label><h4>Salary</h4></label>
                                <input type="number" value="" class="form-control"
                                       placeholder="Salary" name="salary">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Salary</span>
                            </div>
                            <div class="form-group form-md-line-input col-md-4">
                                <label><h4>Allowance</h4></label>
                                <input type="number" value="0" class="form-control"
                                       placeholder="Allowance" name="allowance">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Allowance</span>
                            </div>
                            <div class="form-group form-md-line-input col-md-4">
                                <label><h4>Deduction</h4></label>
                                <input type="number" value="0" class="form-control"
                                       placeholder="Deduction" name="deduction">
                                <div class="form-control-focus"></div>
                                <span class="help-block">Deduction</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-danger"
                                    data-dismiss="modal">Close
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>
@stop

@section('footer')
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    <script>
        var table = $('#manage-branches');
        table.dataTable({
            responsive: true,
        });
    </script>
@stop
