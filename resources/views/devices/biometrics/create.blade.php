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
                <span>Add</span>
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
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Create</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            @if (is_array($errors) && count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul class="list" style="list-style-type: none">
                                        @foreach ($errors as $error)
                                            <li class="item">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif ($errors instanceof \Illuminate\Support\MessageBag && $errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list" style="list-style-type: none">
                                        @foreach ($errors->all() as $error)
                                            <li class="item">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- BEGIN FORM-->
                            <form action="{{ route('device.biometrics.store') }}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="text-center"><h4>Select Client and their shifts </h4>
                                    <p class="text-danger">Note: Only Active Client are lists here</p></div>
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Client</th>
                                                @foreach($shifts as $shift)
                                                <th>
                                                   <div class="md-checkbox">
                                                        <input type="checkbox" id="check_all-{{$shift->id}}" value="{{ $shift->id}}"
                                                            class="md-check">

                                                        <label for="check_all-{{$shift->id}}">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Check All {{ $shift->name }}</label>
                                                    </div>
                                                </th>
                                                @endforeach
                                                @foreach($devices as $device)
                                                    <th>
                                                    <div class="md-checkbox">
                                                            <input type="checkbox" id="device_check_all-{{$device->id}}" value="{{ $device->id}}"
                                                                class="md-check">

                                                            <label for="device_check_all-{{$device->id}}">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Check All {{ $device->name }}</label>
                                                        </div>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($clients as $client)
                                                @php
                                                    $clientShifts = $client->shifts()->pluck('shift_id')->toArray();
                                                    $device_clients = $client->devices()->pluck('device_id')->toArray();
                                                @endphp
                                                <tr>
                                                    <td>{{ $client->fullName }}</td>
                                                    @foreach($shifts as $shift)
                                                        <td>
                                                            <div class="md-checkbox data-shift" data-shift="{{$shift->id}}">
                                                                <input type="checkbox" name="shifts[{{ $client->customer_id }}][{{$shift->id}}]" class="md-check"
                                                                id="s-{{$client->customer_id}}-{{$shift->id}}" value="{{$shift->id}}"
                                                                @if(in_array($shift->id,$clientShifts)) checked @endif>
                                                                <label for="s-{{$client->customer_id}}-{{$shift->id}}">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> {{ $shift->name }} </label>
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                    @foreach($devices as $device)
                                                        <td>
                                                            <div class="md-checkbox data-device" data-device="{{$device->id}}">
                                                                <input type="checkbox" name="devices[{{ $client->customer_id }}][{{$device->id}}]" class="md-check"
                                                                id="d-{{$client->customer_id}}-{{$device->id}}" value="{{$device->id}}"
                                                                @if(in_array($device->id,$device_clients)) checked @endif >
                                                                <label for="d-{{$client->customer_id}}-{{$device->id}}">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> {{ $device->name }} </label>
                                                            </div>
                                                        </td>
                                                    @endforeach
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
@section('footer')
    <script>
        $(document).ready(function () {
            //for shifts check all
            @foreach($shifts as $shift)
            $('#check_all-'+{{$shift->id}}).change(function () {
                var shiftId = $(this).val();
                var check = $(this).prop('checked');
                var elementsToSelect = $('.data-shift[data-shift="' + shiftId + '"]');
                if(check) {
                    elementsToSelect.each(function () {
                        var $collection = $(this);
                        $collection.find('input[type="checkbox"]').prop('checked', true);
                        $collection.closest('span').addClass('checked');
                    });
                }else{
                    elementsToSelect.each(function () {
                        var $collection = $(this);
                        $collection.find('input[type="checkbox"]').prop('checked', false);
                        $collection.closest('span').removeClass('checked');
                    });
                }
            });
            @endforeach
            //for device check all
            @foreach($devices as $device)
            $('#device_check_all-'+{{$device->id}}).change(function () {
                var deviceId = $(this).val();
                var check = $(this).prop('checked');
                var elementsToSelect = $('.data-device[data-device="' + deviceId + '"]');
                if(check) {
                    elementsToSelect.each(function () {
                        var $collection = $(this);
                        $collection.find('input[type="checkbox"]').prop('checked', true);
                        $collection.closest('span').addClass('checked');
                    });
                }else{
                    elementsToSelect.each(function () {
                        var $collection = $(this);
                        $collection.find('input[type="checkbox"]').prop('checked', false);
                        $collection.closest('span').removeClass('checked');
                    });
                }
            });
            @endforeach
        });

    </script>
@endsection
