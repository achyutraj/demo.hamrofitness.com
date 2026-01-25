@extends('layouts.gym-merchant.gymbasic')
@section('CSS')
    <style>
        h4, h5 {
            font-weight: 600;
        }

        .danger {
            color: red;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Bank & Branch Management</span>
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
                                <i class="fa fa-university font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Bank & Branch Management</span>
                            </div>
                        </div>

                        <div class="portlet-body">
                            <div class="table-toolbar">
                                @if(session()->has('message'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message') }}
                                    </div>
                                @endif
                                @if(session()->has('danger'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('danger') }}
                                    </div>
                                @endif
                                <div class="asset-tab">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#bankList">Bank</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#branchList">Branch</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="bankList" class="tab-pane fade in active">
                                            @if($user->can("add_bank"))
                                            <div class="actions">
                                                <a class="btn sbold dark" data-toggle="modal" data-target="#addNewTrainer">Add New
                                                    <i class="fa fa-plus"></i></a>
                                            </div>
                                            @endif
                                            <div class="modal" tabindex="-1" id="addNewTrainer" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 style="font-weight: 600;" class="modal-title">Add New Bank</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{route('gym-admin.banks.create')}}" method="post">
                                                            {{csrf_field()}}
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="name"><h5>Bank Name</h5></label>
                                                                    <input type="text" class="form-control" name="name"
                                                                           placeholder="Bank Name"
                                                                           value="{{old('name')}}" required>
                                                                    @if ($errors->has('name'))
                                                                        <span class="invalid-feedback danger" role="alert">
                                                                            <strong>{{ $errors->first('name') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Create</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($banks as $bank)
                                                    <tr>
                                                        <td>{{$bank->name}}</td>
                                                        <td>
                                                            @if($user->can("edit_bank"))
                                                                <a class="btn btn-sm btn-primary" data-toggle="modal"
                                                                   data-target="#trainerEditModal{{$bank->id}}"
                                                                   style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>
                                                            @endif
                                                            @if($user->can("delete_bank"))
                                                                <a class="btn btn-sm btn-danger" style="font-size: 12px;"
                                                                   onclick="return confirm('Are You Sure?')"
                                                                   href="{{route('gym-admin.banks.delete', $bank->id)}}">Delete<i
                                                                            class="fa fa-trash"></i></a>
                                                            @endif
                                                            <div class="modal" tabindex="-1" id="trainerEditModal{{$bank->id}}"
                                                                 role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 style="font-weight: 600;" class="modal-title">Edit
                                                                                Bank {{$bank->name}}</h4>
                                                                            <button type="button" class="close" data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="{{route('gym-admin.banks.update', $bank->id)}}"
                                                                              method="post">
                                                                            {{csrf_field()}}
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <label for="name"><h5>Bank Name</h5></label>
                                                                                    <input type="text" value="{{$bank->name}}"
                                                                                           class="form-control" name="name"
                                                                                           placeholder="Bank Name"
                                                                                           required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary"
                                                                                        data-dismiss="modal">Close
                                                                                </button>
                                                                                <button type="submit" class="btn btn-primary">
                                                                                    Update
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
                                        <div id="branchList" class="tab-pane fade">
                                            @if($user->can("add_bank_branch"))
                                            <div class="actions">
                                                <a class="btn sbold dark" data-toggle="modal" data-target="#addNewClass">Add New
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </div>
                                            @endif
                                            <div class="modal" tabindex="-1" id="addNewClass" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 style="font-weight: 600;" class="modal-title">Add New Branch</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{route('gym-admin.bankBranch.create')}}" method="post">
                                                            {{csrf_field()}}
            
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label class="control-label" for="form_control_bank">Bank</label>
                                                                    <select class="form-control" name="bank_id" for="form_control_bank">
                                                                        @foreach($banks as $bank)
                                                                            <option value="{{$bank->id}}">{{$bank->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="form-control-focus"></div>
                                                                </div>
            
                                                                <div class="form-group">
                                                                    <label for="name"><h5>Branch Name</h5></label>
                                                                    <input type="text" class="form-control" name="name"
                                                                           placeholder="Enter Branch Name" required>
                                                                </div>
            
                                                            </div>
            
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Create</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Branch Name</th>
                                                    <th>Bank Name</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($branches as $branch)
                                                    <tr>
                                                        <td>{{$branch->name}}</td>
                                                        <td>{{$branch->bank->name}}</td>
                                                        <td>
                                                            @if($user->can("edit_bank_branch"))
                                                                <a class="btn btn-sm btn-primary" data-toggle="modal"
                                                                   data-target="#classEditModal{{$branch->id}}"
                                                                   style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>
                                                            @endif
                                                            @if($user->can("delete_bank_branch"))
                                                                <a class="btn btn-sm btn-danger" style="font-size: 12px;"
                                                                   onclick="return confirm('Are You Sure?')"
                                                                   href="{{ route('gym-admin.bankBranch.delete', $branch->id)}}">Delete<i
                                                                            class="fa fa-trash"></i></a>
                                                            @endif
                                                            <div class="modal" tabindex="1" id="classEditModal{{$branch->id}}"
                                                                 role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 style="font-weight: 600;" class="modal-title">Edit
                                                                                Class {{$branch->name}}</h4>
                                                                            <button type="button" class="close" data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="{{ route('gym-admin.bankBranch.update', $branch->id)}}"
                                                                              method="post">
                                                                            {{csrf_field()}}
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <label for="name"><h5>Branch Name</h5></label>
                                                                                    <input type="text" value="{{$branch->name}}"
                                                                                           class="form-control" name="name"
                                                                                           placeholder="Branch Name" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary"
                                                                                        data-dismiss="modal">Close
                                                                                </button>
                                                                                <button type="submit" class="btn btn-primary">
                                                                                    Update
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Model -->
    <div class="modal" tabindex="-1" id="addNewTrainer" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="font-weight: 600;" class="modal-title">Add New Bank</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('gym-admin.banks.create')}}" method="post">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name"><h5>Bank Name</h5></label>
                            <input type="text" class="form-control" name="name"
                                   placeholder="Bank Name"
                                   value="{{old('name')}}" required>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback danger" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="addNewClass" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="font-weight: 600;" class="modal-title">Add New Branch</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('gym-admin.bankBranch.create')}}" method="post">
                    {{csrf_field()}}

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" for="form_control_bank">Bank</label>
                            <select class="form-control" name="bank_id" for="form_control_bank">
                                @foreach($banks as $bank)
                                    <option value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                            </select>
                            <div class="form-control-focus"></div>
                        </div>

                        <div class="form-group">
                            <label for="name"><h5>Branch Name</h5></label>
                            <input type="text" class="form-control" name="name"
                                   placeholder="Enter Branch Name" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/bootbox/bootbox.min.js') !!}
    <script>
        $(function () {
            setTimeout(function () {
                $('.alert-message').slideUp();
            }, 3000);
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var UIBootbox = function () {
            var branchData = function () {
                $('.remove-branch').on('click', function () {
                    var url = $(this).data('branch-url');
                    bootbox.confirm({
                        message: "Do you want to delete this branch?",
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
            var bankData = function () {
                $('.remove-bank').on('click',function () {
                    var bank_url = $(this).data('bank-url');
                    bootbox.confirm({
                        message: "Do you want to delete this bank?",
                        buttons: {
                            confirm: {
                                label: "Yes",
                                className: "btn-primary"
                            }
                        },
                        callback: function (result) {
                            if (result) {
                                $.easyAjax({
                                    url: bank_url,
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
                    bankData()
                }
            }
        }();
        jQuery(document).ready(function () {
            UIBootbox.init()
        });
    </script>
@endsection
