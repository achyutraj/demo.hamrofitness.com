@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
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
                <a href="{{ route('gym-admin.reservations.index') }}">Locker Reservation</a>
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
                <div class="col-md-10 col-xs-12">
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Add New Reservation</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {{ html()->form->open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}

                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                <div class="form-group form-md-line-input">
                                    <select  class="bs-select form-control" data-live-search="true" data-size="8" name="client_id" id="client_id">
                                        <option value="">Select Client</option>
                                        @foreach($clients as $client)
                                            <option @if($client_id != 0)
                                                        @if($client_id == $client->customer_id)
                                                            selected
                                                    @endif
                                                    @endif value="{{$client->customer_id}}">{{$client->first_name}} {{$client->middle_name}} {{$client->last_name}}</option>                                        @endforeach
                                    </select>
                                    <label for="title">Client</label>
                                    <span class="help-block"></span>
                                </div>
                                    </div>
                                    <div class="col-md-4">
                                <div class="form-group form-md-line-input" id="mem_select">
                                    <select  class="bs-select form-control" data-live-search="true" data-size="8" name="locker_id" id="locker_id">
                                        <option value="">Select Locker</option>
                                        @foreach($lockers as $lock)
                                            <option value="{{$lock->id}}" data-price="{{ $lock->lockerCategory->price }}"
                                                 data-category="{{$lock->lockerCategory->id}}">{{$lock->locker_num}} ({{ $lock->lockerCategory->title }})</option>
                                        @endforeach

                                    </select>
                                    <label for="title">Locker</label>
                                    <span class="help-block"></span>
                                </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="payment_for_area">
                                            <div class="form-group form-md-line-input ">
                                                <select  class="form-control" name="price_type" id="price_type">
                                                    <option value="">Select Price </option>

                                                </select>
                                                <label for="title">Select Price Variations</label>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <div class="input-group left-addon right-addon">
                                                <span class="input-group-addon">{{$gymSettings->currency->acronym}}</span>
                                                <input type="text" class="form-control"  name="purchase_amount" id="purchase_amount">
                                                <span class="help-block">Locker Cost</span>
                                                <span class="input-group-addon">.00</span>
                                                <label for="purchase_amount">Cost</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <div class="input-group left-addon right-addon">
                                                <span class="input-group-addon">{{$gymSettings->currency->acronym}}</span>
                                                <input type="text" class="form-control"  name="discount" id="discount">
                                                <span class="help-block" id="msg">Discount Amount</span>
                                                <span class="input-group-addon">.00</span>
                                                <label for="discount">Discount</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <div class="input-group left-addon right-addon">
                                                <span class="input-group-addon">{{$gymSettings->currency->acronym}}</span>
                                                <input type="text" class="form-control"  name="amount_to_be_paid" id="amount_to_be_paid">
                                                <span class="help-block">Amount to be Paid</span>
                                                <span class="input-group-addon">.00</span>
                                                <label for="amount_to_be_paid">Amount</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" readonly class="form-control date-picker" placeholder="Select Purchase Date" name="purchase_date" id="purchase_date" value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}">
                                                <label for="form_control_1">Purchase Date</label>
                                                <span class="help-block">Enter Purchase Date</span>
                                                <i class="icon-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" readonly class="form-control date-picker" placeholder="Select Start Date" name="start_date" id="start_date" value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}">
                                                <label for="form_control_1">Customer is going to use locker from?</label>
                                                <span class="help-block">Date from when customer will be using locker.</span>
                                                <i class="icon-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <div class="input-group left-addon right-addon">
                                                <span class="input-group-addon">{{$gymSettings->currency->acronym}}</span>
                                                <input type="text" class="form-control"  name="payment_amount" id="payment_amount">
                                                <span class="help-block">Pay Amount</span>
                                                <span class="input-group-addon">.00</span>
                                                <label for="payment_amount">How much client pay now?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" readonly class="form-control date-picker" placeholder="Select Payment Date" name="payment_date" id="payment_date" value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}">
                                                <label for="form_control_1">Payment Date</label>
                                                <span class="help-block"></span>
                                                <i class="icon-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <div class="input-icon">
                                                <input type="text" readonly class="form-control date-picker" placeholder="Select Due Payment Date" name="next_payment_date" id="next_payment_date" value="{{ \Carbon\Carbon::today()->addDays(1)->format('m/d/Y') }}">
                                                <label for="form_control_1">Due Payment Date</label>
                                                <span class="help-block"></span>
                                                <i class="icon-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group form-md-radios">
                                        <label class="col-md-3 control-label">Payment Source</label>
                                        <div class="col-md-9">
                                            <div class="md-radio-inline">
                                                @foreach($payment_sources as $key=> $source)
                                                <div class="md-radio">
                                                    <input type="radio" value="{{$key}}" id="{{$key}}_radio" name="payment_source" class="md-radiobtn">
                                                    <label for="{{$key}}_radio">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> <i class="fa fa-{{$source['icon']}}"></i> {{$source['label']}} </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Remarks" name="remarks" id="remarks">
                                                <label for="form_control_1">Remark</label>
                                                <span class="help-block">Add Reservation remark</span>
                                                <i class="fa fa-pencil"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions" style="margin-top: 70px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn dark mt-ladda-btn ladda-button" data-style="zoom-in" id="save-form">
                                            <span class="ladda-label"><i class="fa fa-save"></i> SAVE</span>
                                        </button>
                                        <button type="reset" class="btn default">Reset</button>
                                    </div>
                                </div>
                            </div>
                            {{ html()->form->close() !!}
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
    <script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>
    <script>

        $(document).ready(function() {

            $("#purchase_date").datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
            }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('#start_date').datepicker('setStartDate', minDate);
            });

            $("#start_date").datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
            }).on('changeDate', function (selected) {
                var maxDate = new Date(selected.date.valueOf());
                $('#purchase_date').datepicker('setEndDate', maxDate);
            });
            $("#next_payment_date").datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
            })
            $("#payment_date").datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
            })

        });
    </script>
    <script>
        $('#save-form').click(function(event){
            event.preventDefault();

            $.easyAjax({
                url:"{{route('gym-admin.reservations.store')}}",
                container:'#storePayments',
                type: "POST",
                data:$('#storePayments').serialize(),
                formReset:true,
                success:function(responce){
                    if(responce.status == 'success'){
                        $('#client_id').val('');
                        $('#client_id').selectpicker('refresh');
                        $('#locker_id').val('');
                        $('#locker_id').selectpicker('refresh');
                    }
                }
            })
        });

        $('#locker_id').change(function () {
            var cateId = $( "#locker_id option:selected" ).data('category');;
            if (cateId == "") return false;
            var url = "{{route('gym-admin.reservations.getLockerCategory',[':id'])}}";
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

        $('#storePayments').on('change', '#price_type', function () {
            var price = $( "#price_type option:selected" ).data('price');
            $('#purchase_amount').val(price);
            $('#amount_to_be_paid').val(price);
            $('#discount').val(0);
        });

        $('#amount_to_be_paid').keyup(function () {
            var cost = $('#purchase_amount').val();
            var discount = parseInt(cost)-parseInt($(this).val());
            $('#discount').val(discount);
        });

        $('#discount').keyup(function() {
            var cost =  $('#purchase_amount').val();
            var amount = cost - $(this).val();
            if(amount < cost){
                if(amount <= 0) {
                    $('#amount_to_be_paid').val(0);
                } else {
                    $('#amount_to_be_paid').val(amount);
                }
            }else{
                $('#msg').html("<p class='text-danger'>Amount should be less than cost.</p>");
            }

        });
    </script>
@stop
