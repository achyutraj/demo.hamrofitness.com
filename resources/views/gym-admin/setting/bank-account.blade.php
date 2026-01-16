@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                Settings
                <a href=""></a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Bank Accounts</span>
            </li>
        </ul>
        <div class="page-content-inner">
            <div class="row">
                @if(session()->has('message'))
                    <div class="alert alert-message alert-success">
                        {{session()->get('message')}}
                    </div>
                @endif
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase">Bank Accounts</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    @if($user->can("add_bank_account"))
                                        <a id="sample_editable_1_new" data-toggle="modal" data-target="#addLeave"
                                           class="btn sbold dark"> Add New
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-100"
                                   id="manage-branches">
                                <thead>
                                <tr>
                                    <th class="desktop">Bank Name</th>
                                    <th class="desktop">Branch</th>
                                    <th class="desktop">Account Number</th>
                                    <th class="desktop">Balance</th>
                                    <th class="desktop">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bank_accounts as $account)
                                    <tr>
                                        <td>{{$account->bank->name ?? ''}}</td>
                                        <td>{{$account->branch->name ?? ''}}</td>
                                        <td>{{$account->account_number ?? ''}}</td>
                                        <td>{{$gymSettings->currency->acronym}} {{$account->balance ?? ''}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn blue btn-xs dropdown-toggle" type="button"
                                                        data-toggle="dropdown"><i class="fa fa-gears"></i> <span
                                                            class="hidden-xs">Action</span>
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu pull-right" role="menu">
                                                    @if($user->can("edit_bank_account"))
                                                        <li>
                                                            <a data-toggle="modal"
                                                               data-target="#editLeaveType{{$account->id}}"> <i
                                                                        class="fa fa-edit"></i> Edit</a>
                                                        </li>
                                                    @endif
                                                    @if($user->can("delete_bank_account"))
                                                        <li>
                                                            <a data-url="{{route('gym-admin.banksAccount.delete', $account->id)}}"
                                                               class="remove-user">
                                                                <i class="fa fa-trash"></i> Delete</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="modal fade bs-modal-md in" id="editLeaveType{{$account->id}}"
                                                 role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-md" id="modal-data-application">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4>Update Bank Account</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-hidden="true"></button>
                                                            <span class="caption-subject font-red-sunglo bold uppercase"
                                                                  id="modelHeading"></span>
                                                        </div>
                                                        <form action="{{route('gym-admin.banksAccount.update', $account->id)}}"
                                                              method="post" enctype="multipart/form-data">
                                                            {{csrf_field()}}
                                                            <div class="modal-body">
                                                                <div class="form-group form-md-line-input col-md-6">
                                                                    <label><h4>Account Number</h4></label>
                                                                    <input type="text" class="form-control" value="{{$account->account_number}}"
                                                                           placeholder="Account Number"
                                                                           name="account_number" required>
                                                                    <div class="form-control-focus"></div>
                                                                    <span class="help-block">Enter Account Number</span>
                                                                </div>
                                                                <div class="form-group form-md-line-input col-md-6">
                                                                    <label><h4>Balance</h4></label>
                                                                    <input type="number" min="0" class="form-control" value="{{$account->balance}}"
                                                                           placeholder="Balance"
                                                                           name="balance" required>
                                                                    <div class="form-control-focus"></div>
                                                                    <span class="help-block">Enter Balance</span>
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
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bs-modal-md in" id="addLeave" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" id="modal-data-application">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Create Bank Account</h4>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true"></button>
                        <span class="caption-subject font-red-sunglo bold uppercase"
                              id="modelHeading"></span>
                    </div>
                    <form action="{{route('gym-admin.banksAccount.create')}}"
                          method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="branch_id">
                        <div class="modal-body">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="form_control_bank">Bank</label>
                                <select class="form-control" name="bank_id" id="bank"
                                        for="form_control_bank">
                                    <option disabled selected>Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->id}}">{{$bank->name}}</option>
                                    @endforeach
                                </select>
                                <div class="form-control-focus"></div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label" for="form_control_branch">Branch</label>
                                <select class="form-control" name="bank_branch_id" id="branch"
                                        for="form_control_branch">
                                    <option disabled selected>Select Branch</option>
                                </select>
                                <div class="form-control-focus"></div>
                            </div>

                            <div class="form-group form-md-line-input col-md-6">
                                <label><h4>Account Number</h4></label>
                                <input type="text" class="form-control"
                                       placeholder="Bank Account"
                                       name="account_number" required>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Account Number</span>
                            </div>

                            <div class="form-group form-md-line-input col-md-6">
                                <label><h4>Balance</h4></label>
                                <input type="number" min="0" class="form-control"
                                       placeholder="Balance"
                                       name="balance" required>
                                <div class="form-control-focus"></div>
                                <span class="help-block">Enter Balance</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <button type="button" class="btn btn-danger"
                                    data-dismiss="modal">Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('footer')
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
     {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/bootbox/bootbox.min.js') !!}
    <script>
        var table = $('#manage-branches');
        table.dataTable({
            responsive: true,
        });
        $(function () {
            setTimeout(function () {
                $('.alert-message').slideUp();
            }, 3000);
        });
    </script>
    <script>
        $("#bank").on('change', function () {
            var bank_id = $(this).val();
            let url = "{{route('gym-admin.getBankBranches', ':bank_id')}}";
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: url.replace(':bank_id', bank_id),
                success: function (data) {
                    $('#branch').empty().append('<option value="" disabled selected>Select Branch</option><br>');
                    for (var i = 0; i < data.length; i++) {
                        branches = "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
                        $('#branch').append(branches);
                    }
                }
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var UIBootbox = function () {
            var branchData = function () {
                $('.remove-user').on('click', function () {
                    var url = $(this).data('url');
                    bootbox.confirm({
                        message: "Do you want to delete this account?",
                        buttons: {
                            confirm: {
                                label: "Yes",
                                className: "btn-primary"
                            }
                        },
                        callback: function (result) {
                            if (result) {
                                $.easyAjax({
                                    url: url,
                                    type: 'POST',
                                    data: {
                                        '_method': 'delete' , '_token': '{{ csrf_token() }}'
                                    },
                                    success: function () {
                                        location.reload();
                                    }
                                });
                            }
                            else {
                                console.log('cancel');
                            }
                        }
                    })
                });
            };
            return {
                init: function () {
                    branchData()
                }
            }
        }();
        jQuery(document).ready(function () {
            UIBootbox.init()
        });
    </script>
@stop
