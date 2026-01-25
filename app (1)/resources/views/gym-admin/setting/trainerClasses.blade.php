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
                <span>Trainers & Classes</span>
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
                            <i class="fa fa-user font-red"></i>
                            <span class="caption-subject font-red bold uppercase"> Trainers & Classes</span>
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
                                    <li class="active"><a data-toggle="tab" href="#home">Trainers</a></li>
                                    <li><a data-toggle="tab" href="#menu1">Classes</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div id="home" class="tab-pane fade in active">
                                        <div class="actions">
                                            <a class="btn sbold dark" data-toggle="modal" data-target="#addNewTrainer">Add New <i class="fa fa-plus"></i></a>
                                        </div>
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Phone</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($trainer as $trainers)
                                                <tr>
                                                    <td>{{$trainers->name}}</td>
                                                    <td>{{$trainers->email}}</td>
                                                    <td>{{$trainers->address}}</td>
                                                    <td>{{$trainers->phone}}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#trainerEditModal{{$trainers->id}}"
                                                        style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>
                                                        <a class="btn btn-sm btn-danger trainer-remove" style="font-size: 12px;" 
                                                            data-trainer-url="{{ route('gym-admin.trainers.destroy',$trainers->id)}}">
                                                            Delete<i class="fa fa-trash"></i>
                                                        </a>
                                                        <div class="modal" tabindex="-1" id="trainerEditModal{{$trainers->id}}" role="dialog">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 style="font-weight: 600;" class="modal-title">Edit Trainer {{$trainers->name}}</h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="{{ route('gym-admin.trainers.update',$trainers->id)}}" method="post">
                                                                        {{csrf_field()}}
                                                                        <div class="modal-body">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="name"><h5>Trainer Full Name</h5></label>
                                                                                <input type="text" value="{{$trainers->name}}" class="form-control" name="name"
                                                                                    placeholder="Full Name of Trainer" required>
                                                                                    @if ($errors->has('name'))
                                                                                        <span class="invalid-feedback danger" role="alert">
                                                                                        <strong>{{ $errors->first('name') }}</strong>
                                                                                    </span>
                                                                                    @endif
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="address"><h5>Trainer Address</h5></label>
                                                                                <input type="text" value="{{$trainers->address}}" class="form-control" name="address"
                                                                                    placeholder="Trainer's Address" required>
                                                                                @if ($errors->has('address'))
                                                                                    <span class="invalid-feedback danger" role="alert">
                                                                                        <strong>{{ $errors->first('address') }}</strong>
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="email"><h5>Trainer Email</h5></label>
                                                                                <input type="email" class="form-control" value="{{$trainers->email}}" name="email"
                                                                                    placeholder="Trainer's Email Address" required>
                                                                                @if ($errors->has('email'))
                                                                                    <span class="invalid-feedback danger" role="alert">
                                                                                        <strong>{{ $errors->first('email') }}</strong>
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="phone"><h5>Trainer Phone Number</h5></label>
                                                                                <input type="tel" class="form-control" value="{{$trainers->phone}}" name="phone"
                                                                                    placeholder="Trainer's Phone Number" required>
                                                                                @if ($errors->has('phone'))
                                                                                    <span class="invalid-feedback danger" role="alert">
                                                                                        <strong>{{ $errors->first('phone') }}</strong>
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-primary">Update</button>
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
                                    <div id="menu1" class="tab-pane fade">
                                        <div class="actions">
                                            <a class="btn sbold dark" data-toggle="modal" data-target="#createNewClass">Add New <i class="fa fa-plus"></i></a>
                                        </div>
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($class as $classes)
                                                <tr>
                                                    <td>{{$classes->class_name}}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#classEditModal{{$classes->id}}"
                                                        style="font-size: 12px;">Edit <i class="fa fa-edit"></i></a>
                                                        <a class="btn btn-sm btn-danger class-remove" style="font-size: 12px;" 
                                                        data-classes-url="{{ route('gym-admin.classes.destroy',$classes->id)}}">Delete<i class="fa fa-trash"></i></a>
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
                    
        {{--  Trainer Modal--}}
        <div class="modal" tabindex="-1" id="addNewTrainer" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 style="font-weight: 600;" class="modal-title">Add New Trainer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('gym-admin.trainers.store')}}" method="post">
                        {{csrf_field()}}
                        <div class="modal-body">
                            <div class="form-group col-md-6">
                                <label for="name"><h5>Trainer Full Name</h5></label>
                                <input type="text" class="form-control" name="name" placeholder="Full Name of Trainer"
                                       value="{{old('name')}}" required>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback danger" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="address"><h5>Trainer Address</h5></label>
                                <input type="text" class="form-control" name="address" placeholder="Trainer's Address"
                                       value="{{old('address')}}">
                                @if ($errors->has('address'))
                                    <span class="invalid-feedback danger" role="alert">
                                                                <strong>{{ $errors->first('address') }}</strong>
                                                            </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email"><h5>Trainer Email</h5></label>
                                <input type="email" class="form-control" name="email" placeholder="Trainer's Email Address"
                                       value="{{old('email')}}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback danger" role="alert">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone"><h5>Trainer Phone Number</h5></label>
                                <input type="tel" class="form-control" name="phone" placeholder="Trainer's Phone Number"
                                       value="{{old('phone')}}">
                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback danger" role="alert">
                                                                <strong>{{ $errors->first('phone') }}</strong>
                                                            </span>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{--Class Modal--}}
        <div class="modal" tabindex="-1" id="createNewClass" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 style="font-weight: 600;" class="modal-title">Add New Class</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('gym-admin.classes.store')}}" method="post">
                        {{csrf_field()}}
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="class_name"><h5>Class Name</h5></label>
                                <input type="text" class="form-control" name="class_name" placeholder="Enter Class Name" required>
                                @if ($errors->has('class_name'))
                                    <span class="invalid-feedback danger" role="alert">
                                        <strong>{{ $errors->first('class_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @foreach($class as $classes)
            <div class="modal" tabindex="1" id="classEditModal{{$classes->id}}" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 style="font-weight: 600;" class="modal-title">Edit Class {{$classes->name}}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('gym-admin.classes.update',$classes->id)}}" method="post">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="class_name"><h5>Class Name</h5></label>
                                    <input type="text" value="{{$classes->class_name}}" class="form-control" name="class_name"
                                           placeholder="Class Name" required>
                                    @if ($errors->has('class_name'))
                                        <span class="invalid-feedback danger" role="alert">
                                                                            <strong>{{ $errors->first('class_name') }}</strong>
                                                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@push('after-scripts')
    <script>
        @if (count($errors) > 0)
            @if($errors->first('class_name'))
                @if (request()->session()->get('class_id') !== 0)
                    $('#classEditModal{{request()->session()->get('class_id')}}').modal('show');
                @else
                $('#createNewClass').modal('show');
                @endif
            @else
                @if (request()->session()->get('trainer_id') !== 0)
                $('#trainerEditModal{{request()->session()->get('trainer_id')}}').modal('show');
                @else
                $('#addNewTrainer').modal('show');
                @endif
            @endif
        @endif
    </script>
@endpush()

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
                $('.class-remove').on('click', function () {
                    var url = $(this).data('classes-url');
                    bootbox.confirm({
                        message: "Do you want to delete this class?",
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
                $('.trainer-remove').on('click',function () {
                    var bank_url = $(this).data('trainer-url');
                    bootbox.confirm({
                        message: "Do you want to delete this trainer?",
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