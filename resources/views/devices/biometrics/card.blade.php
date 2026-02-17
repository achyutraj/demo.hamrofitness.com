@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
@stop

@section('content')
    <div class="container-fluid"  >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('device.biometrics.index') }}">Customer Biometric</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Add Card</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Add Client Card</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list" style="list-style-type: none">
                                        @foreach ($errors->all() as $error)
                                            <li class="item">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <!-- BEGIN FORM-->
                            <form action="{{ route('device.biometrics.addUserInfo') }}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="form-group form-md-line-input col-md-12">
                                        <label for="device">Device *</label>
                                        <select class="form-control todo-taskbody-tags" id="device"
                                                name="device" required>
                                            <option selected disabled>Select Device</option>
                                            @foreach($devices as $device)
                                                <option class="todo-username pull-left"
                                                        value="{{$device->id}}">{{$device->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Client</th>
                                                <th>Card Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($clients as $client)
                                                <tr>
                                                    <td>{{ $client->fullName }}</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Enter Client Card Number" name="card[{{$client->customer_id}}]"
                                                                       value="{{ $client->card }}">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <a href="{{ route('device.biometrics.index')}}" class="btn default">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- END FORM-->
                          </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop