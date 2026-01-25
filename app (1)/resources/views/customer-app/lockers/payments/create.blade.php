@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Add Payments
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
            <h4 class="page-title">Add Payment</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li class="active">Add Payment</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box p-l-20 p-r-20">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::open(['route'=>['customer-app.locker-payments.pay'],'class'=>'form-material form-horizontal','method'=>'POST']) !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-sm-6">Locker</label>
                                <div class="col-sm-6">
                                    <select class="form-control select2" name="reservation_id" id="reservation_id">
                                        <option selected disabled>Select Locker</option>
                                        @foreach($purchases as $purchase)
                                            <option value="{{ $purchase->id }}">{{ $purchase->locker->locker_num }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-6" for="price">Payment Amount <span class="required" aria-required="true"> * </span></label>
                                <div class="col-sm-6 input-group left-addon right-addon">
                                    <span class="input-group-addon">{{ $gymSettings->currency->acronym }}</span>
                                    <input type="number" min="0" class="form-control" name="payment_amount" id="payment_amount">
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>

                            <div id="remaining_div" class="form-group">
                                <label class="col-sm-6">Remaining Amount</label>
                                <div class="col-sm-6 input-group left-addon right-addon">
                                    <span class="input-group-addon">{{ $gymSettings->currency->acronym }}</span>
                                    <input disabled type="number" min="0" class="form-control" name="remaining_amount" id="remaining_amount" value="">
                                    <input disabled type="hidden" class="form-control" name="remaining_amount_store" id="remaining_amount_store">
                                    <span class="input-group-addon">.00</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-6">Payment Method</label>
                                <div class="col-sm-6">
                                    <input type="radio" value="esewa" id="esewa" name="payment_source" class="btn">
                                    <img src="{{ asset('admin/payments/esewa.png') }}" width="40" height="40">
                                    <label for="no_radio"> Pay with Esewa </label>
                                    <br>

                                    <input type="radio" value="khalti" id="khalti" name="payment_source" class="btn">
                                    <img src="{{ asset('admin/payments/khalti.png') }}" width="50" height="50">
                                    <label for="no_radio"> Pay with Khalti </label>
                                    <br>

                                    <input type="radio" value="offline" id="offline" name="payment_source" class="btn">
                                    <img src="{{ asset('admin/payments/offline.png') }}" width="50" height="50">
                                    <label for="no_radio"> Pay with Offline </label>
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <button class="btn btn-primary waves-effect button-space" type="submit" id="submit-btn">Submit</button>
                                <button class="btn btn-default waves-effect">Back</button>
                            </div>

                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-modal-md in" id="offlinePayment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Offline Payment Method</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase"
                          id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    <p>{!! $gymSettings->offline_text !!}</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('customer-app.locker-payments.index') }}" class="btn btn-success">Ok</a>
                </div>
            </div>
        </div>
    </div>

@stop

@section('JS')

    {!! HTML::script('fitsigma_customer/bower_components/bootstrap-select/bootstrap-select.min.js') !!}
    {!! HTML::script('fitsigma_customer/bower_components/custom-select/custom-select.min.js') !!}
    {!! HTML::script('fitsigma_customer/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}
    <script>
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        });

        $('#reservation_id').on('change',function () {
            var purchaseId = $(this).val();
            var url = "{{route('customer-app.locker-payments.remainingPayment',[':id'])}}";
            url = url.replace(':id', purchaseId);
            $.easyAjax({
                url: url,
                type: 'GET',
                data: {purchaseId: purchaseId},
                success: function (response) {
                    $('#remaining_amount').val(response);
                    $('#remaining_amount_store').val(response);
                }
            })
        });

        $('#payment_amount').keyup(function () {
            var remainingAmount = $('#remaining_amount_store').val();
            var total = remainingAmount - $(this).val();
            $("#remaining_amount").val(total);
            $("#tAmt").val(total);
            $("#amt").val(total);

        });
        $('#offline').on('click',function () {
            $('#offlinePayment').modal('show');
        });

    </script>
@stop
