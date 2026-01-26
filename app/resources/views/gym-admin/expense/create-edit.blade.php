@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') !!}
    <style>
        .bill-color {
            color: #888;
        }

        .file-size {
            line-height: 0;
            color: #a2a2a2;
            font-size: 13px;
        }
    </style>
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('gym-admin.expense.index') }}">Expense</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@if(isset($expense->id)) Edit @else Add @endif Expense</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">@if(isset($expense->id))
                                        Edit @else Add @endif Expense</span></div>


                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {!! Form::open(['id'=>'create-edit-expense','class'=>'ajax-form','files'=>true]) !!}
                            <div class="form-body">
                                @if(isset($expense->uuid))
                                    <input type="hidden" name="_method" value="PUT">
                                @endif

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <select class="form-control select2" name="expense_category" id="expense_category">
                                                    <option selected disabled>Please Select</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ isset($expense) && ($expense->category_id == $category->id) ? 'selected' : ''}}>{{ $category->title }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="expense_category">Item Category<span class="required"
                                                                                           aria-required="true"> * </span></label>
                                                <span class="help-block">Add Item Category</span>
                                                <i class="fa fa-file"></i>
                                            </div>
                                            @error('expense_category')
                                                <div style="color: red;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Item Name"
                                                       name="item_name" value="{{$expense->item_name ?? ''}}">
                                                <label for="form_control_1">Item Name<span class="required"
                                                                                           aria-required="true"> * </span></label>
                                                <span class="help-block">Add Item Name</span>
                                                <i class="fa fa-sticky-note-o"></i>
                                            </div>
                                            @error('item_name')
                                                <div style="color: red;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <select class="form-control select2" name="supplier_id"
                                                        id="supplier_id">
                                                    <option selected disabled>Please Select</option>
                                                    @foreach($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}" {{ isset($expense) && ($expense->supplier_id == $supplier->id) ? 'selected' : ''}}>{{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="form_control_1">Purchase From <span class="required"
                                                    aria-required="true"> * </span></label>
                                                <span class="help-block">Add Party Name</span>
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                            @if( !isset($expense->id))
                                                <a class="btn btn-xs btn-success" href="{{ route('gym-admin.suppliers.create')}}" title="Add Party">Add</a>
                                            @endif

                                            @error('supplier_id')
                                                <div style="color: red;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input ">
                                    <div class="input-group left-addon right-addon">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control date-picker" readonly
                                               name="purchase_date"
                                               value="@if(isset($expense->uuid)) {{ \Carbon\Carbon::createFromFormat('Y-m-d', $expense->purchase_date)->format('m/d/Y') }} @else {{ \Carbon\Carbon::now('Asia/Kathmandu')->format('m/d/Y') }} @endif">
                                        <label for="payment_date">Purchase Date<span class="required"
                                                                                     aria-required="true"> * </span></label>
                                    </div>
                                    @error('purchase_date')
                                        <div style="color: red;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Price" name="price" style="padding-left: 50px"
                                                       value="{{$expense->price ?? ''}}">
                                                <label for="form_control_1">Price<span class="required"
                                                                                       aria-required="true"> * </span></label>
                                                <span class="help-block">Add Price of Item</span>
                                                <i class="fa">{{$gymSettings->currency->acronym}}</i>
                                            </div>
                                            @error('price')
                                                <div style="color: red;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-md-line-input">
                                            <div class="form-group form-md-radios">
                                                <label>Payment Status <span class="required" aria-required="true"> * </span></label>
                                                <div class="md-radio-inline">
                                                    <div class="md-radio">
                                                        <input type="radio" value="paid" id="paid_radio" name="payment_status" class="md-radiobtn"
                                                        {{ isset($expense) && ($expense->payment_status == 'paid') ? 'checked' : ''}}>
                                                        <label for="paid_radio">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Paid </label>
                                                    </div>

                                                    <div class="md-radio">
                                                        <input type="radio" value="unpaid" id="unpaid_radio" name="payment_status" class="md-radiobtn"
                                                        {{ isset($expense) && ($expense->payment_status == 'unpaid') ? 'checked' : ''}}>
                                                        <label for="unpaid_radio">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> UnPaid </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-md-line-input">
                                            <div class="form-group form-md-radios">
                                                <label>Payment Source? <span class="required" aria-required="true"> * </span></label>
                                                <div class="md-radio-inline">
                                                    @foreach($paymentSources as $key=> $source)
                                                    <div class="md-radio">
                                                        <input type="radio" value="{{$key}}" id="{{$key}}_radio" name="payment_source" class="md-radiobtn"
                                                        {{ isset($expense) && ($expense->payment_source == $key) ? 'checked' : ''}}>
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
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 bill-color">Bill</label>
                                        <div class="col-md-12">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="input-group input-large">
                                                    <div class="form-control uneditable-input input-fixed input-medium"
                                                         data-trigger="fileinput">
                                                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                        <span class="fileinput-filename">{{$expense->bill ?? ''}}</span>
                                                    </div>
                                                    <span class="input-group-addon btn default btn-file">
                                                        <span class="fileinput-new"> Select file </span>
                                                        <span class="fileinput-exists"> Change </span>
                                                        <input type="file" name="bill">
                                                    </span>
                                                    <a href="javascript:;"
                                                       class="input-group-addon btn red fileinput-exists"
                                                       data-dismiss="fileinput"> Remove </a>
                                                </div>
                                                <div class="fileinput fileinput-new" style="margin-top: 10px;">
                                                    @if(isset($expense->uuid) && $expense->bill != null)
                                                        <a class="input-group-addon btn default" style="width: 100%;"
                                                           href="{{$expenseUrl.'/'.$expense->bill}}" target="_blank">Show
                                                            Bill</a>
                                                    @endif
                                                </div>
                                                @error('bill')
                                                    <div style="color: red;">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <textarea class="form-control" name="remarks" rows="3">{{ isset($expense) && !is_null($expense->remarks) ? $expense->remarks : ''}}</textarea>

                                                <label for="remarks">Remarks <span class="required"
                                                    aria-required="true"> * </span></label>
                                                <span class="help-block">Add Remarks of Expense</span>
                                            </div>
                                            @error('remarks')
                                                <div style="color: red;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(isset($expense) && $expense->uuid)
                                            <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                    data-style="zoom-in" onclick="addUpdate('{{ $expense->uuid }}')">
                                                <span class="ladda-label"><i class="fa fa-save"></i> Update</span>
                                            </button>
                                        @else
                                            <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                    data-style="zoom-in" onclick="addUpdate()">
                                                <span class="ladda-label"><i class="fa fa-save"></i> Save</span>
                                            </button>
                                        @endif
                                        <a type="button" class="btn default"
                                           href="{{ route('gym-admin.expense.index') }}">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                   @include('gym-admin.expense.category')
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')

    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') !!}
    <script>
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true
        });

        function addUpdate(id) {

            var url;
            if (typeof id != 'undefined') {
                url = "{{route('gym-admin.expense.update',':id')}}";
                url = url.replace(':id', id);
            } else {
                url = "{{route('gym-admin.expense.store')}}";
            }

            $.easyAjax({
                type: "POST",
                url: url,
                file: true,
                container: '#create-edit-expense',
            });
        }

        function addUpdateCategory(id) {
            var url ,container_data, data;
            if (typeof id != 'undefined') {
                url = "{{route('gym-admin.updateExpenseCategory',':id')}}";
                url = url.replace(':id', id);
                container_data = '#edit-category-'+id;
                data = $('#edit-category-'+id).serialize();
            } else {
                url = "{{route('gym-admin.addExpenseCategory')}}";
                container_data = '#create-category';
                data = $('#create-category').serialize();
            }
            $.easyAjax({
                type: "POST",
                url: url,
                container: container_data,
                data: data,
                success:(function(res){
                    location.reload()
                }),
            });
        }
    </script>
@stop
