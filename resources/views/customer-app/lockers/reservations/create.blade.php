@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Add Locker Reservation
@endsection

@section('CSS')
{!! HTML::style('fitsigma_customer/bower_components/bootstrap-select/bootstrap-select.min.css') !!}
{!! HTML::style('fitsigma_customer/bower_components/custom-select/custom-select.css') !!}
{!! HTML::style('fitsigma_customer/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') !!}
    <style>
        .text-center {
            text-align: center;
        }
        .button-space {
            margin-right: 4px;
        }
    </style>
@endsection

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Add Reservation</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li class="active">Add Reservation</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box p-l-20 p-r-20">
                <div class="row">
                    <div class="col-md-6">
                        {!! Form::open(['route'=>'customer-app.manage-subscription.store','id'=>'addReservationStoreForm','class'=>'ajax-form form-material form-horizontal','method'=>'POST']) !!}
                            <div class="form-group">
                                <label class="col-sm-12">Locker </label>
                                <div class="col-sm-12">
                                    <select class="form-control select2" name="locker_id" id="locker_id">
                                        <option selected>Select Locker</option>
                                        @foreach($lockers as $locker)
                                            <option value="{{ $locker->id }}" data-category="{{$locker->lockerCategory->id}}">{{ $locker->locker_num }} - {{ $locker->lockerCategory->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-12">Select Price  </label>
                                <div class="col-md-12">
                                    <div id="payment_for_area">
                                        <div class="form-group form-md-line-input ">
                                            <select  class="form-control" name="price_type" id="price_type">
                                                <option value="">Select Price </option>
    
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-12">Cost</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="purchase_amount" id="purchase_amount" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12">Reserving Date</label>
                                <div class="col-sm-12 input-group">
                                    <input type="text" class="form-control" id="datepicker-autoclose" placeholder="mm/dd/yyyy" name="joining_date">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary waves-effect button-space" id="submit-btn">Submit</button>
                                <button class="btn btn-default waves-effect">Back</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('JS')
{!! HTML::script('fitsigma_customer/bower_components/bootstrap-select/bootstrap-select.min.js') !!}
{!! HTML::script('fitsigma_customer/bower_components/custom-select/custom-select.min.js') !!}
{!! HTML::script('fitsigma_customer/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}
<script>
    $(function () {
        $(".select2").select2();
        $('#locker_id').change(function () {
            var cateId = $( "#locker_id option:selected" ).data('category');;
            if (cateId == "") return false;
            var url = "{{route('customer-app.reservations.getLockerCategory',[':id'])}}";
            url = url.replace(':id', cateId);

            $.easyAjax({
                url: url,
                type: 'GET',
                data: {cateId: cateId},
                success: function (response) {
                    $('#payment_for_area').html(response.data);
                }
            })
        });
        $('#addReservationStoreForm').on('change', '#price_type', function () {
            var price = $( "#price_type option:selected" ).data('price');
            $('#purchase_amount').val(price);
            $('#amount_to_be_paid').val(price);
            $('#discount').val(0);
        });
    });

    $('#submit-btn').click(function () {
        $.easyAjax({
            type: 'POST',
            url: "{{ route('customer-app.reservations.store') }}",
            container: '#addReservationStoreForm',
            data: $('#addReservationStoreForm').serialize()
        });
    });

    $('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
</script>
@endsection
