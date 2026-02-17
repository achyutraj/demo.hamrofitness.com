@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    <style>
        .table-scrollable .dataTable td .btn-group, .table-scrollable .dataTable th .btn-group {
            position: relative;
            margin-top: -2px;
        }
    </style>
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                Accounts
                <a href=""></a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Bank Ledger</span>
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
                                <span class="caption-subject font-red bold uppercase">Bank Ledger</span>
                            </div>
                            <div class="actions col-sm-2 col-xs-12">
                                @if($user->can("view_bank_ledger"))
                                    <a id="sample_editable_1_new" data-toggle="modal" data-target="#addLeave"
                                       class="btn dark"> Deposit/Withdraw
                                        <i class="fa fa-plus"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-toolbar">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="modal fade bs-modal-md in" id="addLeave" role="dialog"
                                             aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-md" id="modal-data-application">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4>Add Deposit/Withdraw</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true"></button>
                                                        <span class="caption-subject font-red-sunglo bold uppercase"
                                                              id="modelHeading"></span>
                                                    </div>
                                                    <form action="{{route('gym-admin.bankLedger.create')}}"
                                                          method="post" enctype="multipart/form-data">
                                                        {{csrf_field()}}
                                                        <div class="modal-body">
                                                            <div class="form-group form-md-line-input col-md-12">
                                                                <label class="control-label" for="form_control_bank">Bank
                                                                    Account</label>
                                                                <select class="form-control" name="bank_account"
                                                                        for="form_control_bank">
                                                                    <option disabled selected>Select Bank Account
                                                                    </option>
                                                                    @foreach($accounts as $account)
                                                                        <option
                                                                            value="{{$account->id}}">{{$account->bank->name.'-'.$account->branch->name.'-'.$account->account_number}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="form-control-focus"></div>
                                                            </div>

                                                            <div class="form-group form-md-line-input col-md-6">
                                                                <label class="control-label" for="form_control_bank">Transaction
                                                                    Type</label>
                                                                <select class="form-control" name="transaction_type"
                                                                        id="transaction_type"
                                                                        for="form_control_bank">
                                                                    <option disabled selected>Select Transaction Type
                                                                    </option>
                                                                    <option value="deposit">Deposit</option>
                                                                    <option value="withdraw">Withdraw</option>
                                                                </select>
                                                                <div class="form-control-focus"></div>
                                                            </div>

                                                            <div class="form-group form-md-line-input col-md-6">
                                                                <label class="control-label" for="form_control_bank">Transaction
                                                                    Method</label>
                                                                <select class="form-control" name="transaction_method"
                                                                        id="transaction_method"
                                                                        for="form_control_bank">
                                                                    <option disabled selected>Select Transaction Method
                                                                    </option>
                                                                    <option value="cash">Cash</option>
                                                                    <option value="cheque">Cheque</option>
                                                                    <option value="card">Card</option>
                                                                    <option value="IPS-Connect">IPS Connect</option>
                                                                    <option value="eBanking">eBanking</option>
                                                                    <option value="esewa">Esewa</option>
                                                                    <option value="khalti">Khalti</option>
                                                                    <option value="imepay">IME Pay</option>
                                                                </select>
                                                                <div class="form-control-focus"></div>
                                                                <span class="help-block">Transaction Method</span>
                                                            </div>

                                                            <div class="form-group form-md-line-input col-md-6">
                                                                <label><h4>Date</h4></label>
                                                                <input type="text" class="form-control date-picker"
                                                                       placeholder="Date" id="date" 
                                                                       data-provide="datepicker" data-date-autoclose="true" data-date-today-highlight="true"
                                                                       name="date" required>
                                                                <div class="form-control-focus"></div>
                                                                <span class="help-block">Select Date</span>
                                                            </div>

                                                            <div class="form-group form-md-line-input col-md-6">
                                                                <label><h4>Amount</h4></label>
                                                                <input type="number" min="0" class="form-control"
                                                                       placeholder="Amount"
                                                                       name="amount" required>
                                                                <div class="form-control-focus"></div>
                                                                <span class="help-block">Enter Amount</span>
                                                            </div>

                                                            <div class="form-group form-md-line-input col-md-12">
                                                            <textarea name="remarks" class="form-control" id="remarks"
                                                                      rows="3"></textarea>
                                                                <label for="form_control_1">Remarks</label>
                                                                <div class="form-control-focus"></div>
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
                                    <div class="col-md-6">
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered table-100"
                                   id="manage-branches">
                                <thead>
                                <tr>
                                    <th class="desktop">Bank Details</th>

                                    <th class="desktop">Ledgers</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($accounts as $account)

                                    <tr>
                                        <td style="text-align: left" width="20%">
                                            Bank Name:<strong> {{$account->bank->name}}</strong>
                                            <br>Branch: <strong>{{$account->branch->name}} </strong>
                                            <br>Account Number:<strong> {{$account->account_number}} </strong>
                                        </td>

                                        <td>
                                            <table class="table table-bordered order-column table-100"
                                                   id="manage-branches">
                                                <thead>
                                                <tr>
                                                    <th class="desktop">Transaction Type</th>
                                                    <th class="desktop">Transaction Method</th>
                                                    <th class="desktop">Date</th>
                                                    <th class="desktop">Remarks</th>
                                                    <th class="desktop">Amount</th>
                                                    <th class="desktop">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="">
                                                    <?php
                                                    $deposit = 0.00;
                                                    $withdrawl = 0.00;
                                                    ?>
                                                     @if(!is_null($account->ledgers))
                                                    @foreach($account->ledgers as $ledger)
                                                        <?php
                                                        if ($ledger->transaction_type == 'deposit' || $ledger->transaction_type == 'initial') {
                                                            $deposit += (float)$ledger->amount;
                                                        }

                                                        if ($ledger->transaction_type == 'withdraw') {
                                                            $withdrawl += (float)$ledger->amount;
                                                        }
                                                        ?>
                                                        <td class="
                                                        @if($ledger->transaction_type == 'deposit') success @endif
                                                        @if($ledger->transaction_type == 'withdraw') danger @endif
                                                        @if($ledger->transaction_type == 'initial') info @endif
                                                            ">{{ucfirst($ledger->transaction_type)}}</td>
                                                        <td class="
                                                        @if($ledger->transaction_type == 'deposit') success @endif
                                                        @if($ledger->transaction_type == 'withdraw') danger @endif
                                                        @if($ledger->transaction_type == 'initial') info @endif
                                                            ">{{ucfirst($ledger->transaction_method)}}</td>
                                                        <td class="
                                                        @if($ledger->transaction_type == 'deposit') success @endif
                                                        @if($ledger->transaction_type == 'withdraw') danger @endif
                                                        @if($ledger->transaction_type == 'initial') info @endif
                                                            ">{{ \Carbon\Carbon::createFromFormat('m/d/Y', $ledger->date)->toFormattedDateString()}}</td>
                                                        <td class="
                                                        @if($ledger->transaction_type == 'deposit') success @endif
                                                        @if($ledger->transaction_type == 'withdraw') danger @endif
                                                        @if($ledger->transaction_type == 'initial') info @endif
                                                            ">{{$ledger->remarks}}</td>
                                                        <td class="
                                                        @if($ledger->transaction_type == 'deposit') success @endif
                                                        @if($ledger->transaction_type == 'withdraw') danger @endif
                                                        @if($ledger->transaction_type == 'initial') info @endif
                                                            ">{{ $gymSettings->currency->acronym }} {{$ledger->amount}}</td>
                                                        <td>
                                                            @if($ledger->transaction_type !== 'initial')
                                                                <div class="btn-group">
                                                                    <button class="btn blue btn-xs dropdown-toggle"
                                                                            type="button"
                                                                            data-toggle="dropdown"><i
                                                                            class="fa fa-gears"></i> <span
                                                                            class="hidden-xs">Action</span>
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                                        @if($user->can("view_bank_ledger"))
                                                                            <li>
                                                                                <a data-toggle="modal"
                                                                                   data-target="#editLeaveType{{$ledger->id}}">
                                                                                    <i class="fa fa-edit"></i> Edit</a>
                                                                            </li>
                                                                        @endif
                                                                        @if($user->can("view_bank_ledger"))
                                                                            <li>
                                                                                <a data-url="{{route('gym-admin.bankLedger.delete', $ledger->id)}}"
                                                                                class="remove-user">
                                                                                    <i class="fa fa-trash"></i> Delete</a>
                                                                            </li>
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            @endif

                                                            <div class="modal fade bs-modal-md in"
                                                                 id="editLeaveType{{$ledger->id}}"
                                                                 role="dialog" aria-labelledby="myModalLabel"
                                                                 aria-hidden="true">
                                                                <div class="modal-dialog modal-md"
                                                                     id="modal-data-application">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4>Edit Transaction</h4>
                                                                            <button type="button" class="close"
                                                                                    data-dismiss="modal"
                                                                                    aria-hidden="true"></button>
                                                                            <span class="caption-subject font-red-sunglo bold uppercase"
                                                                                  id="modelHeading"></span>
                                                                        </div>
                                                                        <form action="{{route('gym-admin.bankLedger.update', $ledger->id)}}"
                                                                              method="post"
                                                                              enctype="multipart/form-data">
                                                                            {{csrf_field()}}
                                                                            <div class="modal-body">
                                                                                <div class="form-group form-md-line-input col-md-6">
                                                                                    <label class="control-label"
                                                                                           for="form_control_bank">Transaction
                                                                                        Type</label>
                                                                                    <select class="form-control"
                                                                                            name="transaction_type"
                                                                                            id="transaction_type"
                                                                                            for="form_control_bank">
                                                                                        <option disabled selected>Select
                                                                                            Transaction
                                                                                            Type
                                                                                        </option>
                                                                                        <option
                                                                                            value="deposit" {{$ledger->transaction_type == 'deposit' ? 'selected' : ''}}>
                                                                                            Deposit
                                                                                        </option>
                                                                                        <option
                                                                                            value="withdraw" {{$ledger->transaction_type == 'withdraw' ? 'selected' : ''}}>
                                                                                            Withdraw
                                                                                        </option>
                                                                                    </select>
                                                                                    <div class="form-control-focus"></div>
                                                                                </div>

                                                                                <div class="form-group form-md-line-input col-md-6">
                                                                                    <label class="control-label"
                                                                                           for="form_control_bank">Transaction
                                                                                        Method</label>
                                                                                    <select class="form-control"
                                                                                            name="transaction_method"
                                                                                            id="transaction_method"
                                                                                            for="form_control_bank">
                                                                                        <option disabled selected>Select
                                                                                            Transaction
                                                                                            Method
                                                                                        </option>
                                                                                        <option
                                                                                            value="cash" {{$ledger->transaction_method == 'cash' ? 'selected' : ''}}>
                                                                                            Cash
                                                                                        </option>
                                                                                        <option
                                                                                            value="cheque" {{$ledger->transaction_method == 'cheque' ? 'selected' : ''}}>
                                                                                            Cheque
                                                                                        </option>
                                                                                        <option
                                                                                            value="card" {{$ledger->transaction_method == 'card' ? 'selected' : ''}}>
                                                                                            Card
                                                                                        </option>
                                                                                        <option
                                                                                            value="IPS-Connect" {{$ledger->transaction_method == 'IPS-Connect' ? 'selected' : ''}}>
                                                                                            IPS-Connect
                                                                                        </option>
                                                                                        <option
                                                                                            value="eBanking" {{$ledger->transaction_method == 'eBanking' ? 'selected' : ''}}>
                                                                                            eBanking
                                                                                        </option>
                                                                                        <option
                                                                                            value="esewa" {{$ledger->transaction_method == 'esewa' ? 'selected' : ''}}>
                                                                                            Esewa
                                                                                        </option>
                                                                                        <option
                                                                                            value="khalti" {{$ledger->transaction_method == 'khalti' ? 'selected' : ''}}>
                                                                                            Khalti
                                                                                        </option>
                                                                                        <option
                                                                                            value="imepay" {{$ledger->transaction_method == 'imepay' ? 'selected' : ''}}>
                                                                                            IME Pay
                                                                                        </option>
                                                                                    </select>
                                                                                    <div class="form-control-focus"></div>
                                                                                    <span class="help-block">Transaction Method</span>
                                                                                </div>

                                                                                <div class="form-group form-md-line-input col-md-6">
                                                                                    <label><h4>Date</h4></label>
                                                                                    <input type="text"
                                                                                           class="form-control date-picker"
                                                                                           placeholder="Date" id="date"
                                                                                           value="{{$ledger->date}}"
                                                                                           name="date" required>
                                                                                    <div class="form-control-focus"></div>
                                                                                    <span class="help-block">Select Date</span>
                                                                                </div>

                                                                                <div class="form-group form-md-line-input col-md-6">
                                                                                    <label><h4>Amount</h4></label>
                                                                                    <input type="number" min="0"
                                                                                           class="form-control"
                                                                                           placeholder="Amount"
                                                                                           value="{{$ledger->amount}}"
                                                                                           name="amount" required>
                                                                                    <div class="form-control-focus"></div>
                                                                                    <span class="help-block">Enter Amount</span>
                                                                                </div>

                                                                                <div class="form-group form-md-line-input col-md-12">
                                                                                    <textarea name="remarks"
                                                                                          class="form-control"
                                                                                          id="remarks"
                                                                                          rows="3">{{$ledger->remarks}}</textarea>
                                                                                    <label for="form_control_1">Remarks</label>
                                                                                    <div class="form-control-focus"></div>
                                                                                </div>

                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit"
                                                                                        class="btn btn-primary">Update
                                                                                </button>
                                                                                <button type="button"
                                                                                        class="btn btn-danger"
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
                                                 @endif
                                                <tr>
                                                    <td colspan="4" class="text-right" style="font-weight: 600">
                                                        Total Balance
                                                    </td>
                                                    <td style="font-weight: 600">Rs. {{$deposit-$withdrawl}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
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
@endsection
@section('footer')
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
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
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            startView: 'month'
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
                        message: "Do you want to delete this ledger account?",
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
