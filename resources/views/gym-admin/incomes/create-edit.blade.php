@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css") }}">
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
                <a href="{{ route('gym-admin.incomes.index') }}">Income</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@if(isset($income->uuid)) Edit @else Add @endif Income</span>
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
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">@if(isset($income->uuid))
                                        Edit @else Add @endif Income</span></div>


                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {{ html()->form()->open(['id'=>'create-edit-income','class'=>'ajax-form','files'=>true]) }}
                            <div class="form-body">
                                @if(isset($income->uuid))
                                    <input type="hidden" name="_method" value="PUT">
                                @endif
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <select class="form-control select2" name="income_category" id="income_category">
                                                    <option selected disabled>Please Select</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ isset($income) && ($income->category_id == $category->id) ? 'selected' : ''}}>{{ $category->title }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="form_control_1">Item Name</label>
                                                <span class="help-block">Add Item Name</span>
                                                <i class="fa fa-file"></i>
                                            </div>
                                            @error('income_category')
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
                                                        <option value="{{ $supplier->id }}" {{ isset($income) && ($income->supplier_id == $supplier->id) ? 'selected' : ''}}>{{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="form_control_1">Paid By <span class="required"
                                                    aria-required="true"> * </span></label>
                                                <span class="help-block">Add Party Name</span>
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                            @if( !isset($income->id))
                                                <a class="btn btn-xs btn-success" href="{{ route('gym-admin.suppliers.create')}}" title="Add Party">Add</a>
                                            @endif
                                            @error('supplier_id')
                                                <div style="color: red;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-group left-addon right-addon">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control date-picker" readonly
                                                    name="purchase_date"
                                                    value="@if(isset($income->uuid)) {{ $income->purchase_date->format('m/d/Y') }} @else {{ \Carbon\Carbon::now('Asia/Kathmandu')->format('m/d/Y') }} @endif">
                                                <label for="payment_date">Billing Date<span class="required"
                                                                                            aria-required="true"> * </span></label>
                                            </div>
                                            @error('purchase_date')
                                                <div style="color: red;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Price" name="price" style="padding-left: 50px"
                                                       value="{{$income->price ?? ''}}">
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
                                                <label>Payment Source? <span class="required" aria-required="true"> * </span></label>
                                                <div class="md-radio-inline">
                                                    @foreach($paymentSources as $key=> $source)
                                                    <div class="md-radio">
                                                        <input type="radio" value="{{$key}}" id="{{$key}}_radio" name="payment_source" class="md-radiobtn"
                                                        {{ isset($income) && ($income->payment_source == $key) ? 'checked' : ''}}>
                                                        <label for="{{$key}}_radio">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> <i class="fa fa-{{$source['icon']}}"></i> {{$source['label']}} </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <span class="help-block"></span>
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
                                                        <span class="fileinput-filename">{{$income->bill ?? ''}}</span>
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
                                                    @if(isset($income->uuid) && $income->bill != null)
                                                        <a class="input-group-addon btn default" style="width: 100%;"
                                                           href="{{$incomeUrl.'/'.$income->bill}}" target="_blank">Show
                                                            Bill</a>
                                                    @endif
                                                </div>
                                            </div>
                                            @error('bill')
                                                <div style="color: red;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-md-line-input ">
                                            <div class="input-icon">
                                                <textarea class="form-control" name="remarks" rows="3">{{ isset($income) && !is_null($income->remarks) ? $income->remarks : ''}}</textarea>

                                                <label for="remarks">Remarks <span class="required"
                                                    aria-required="true"> * </span> </label>
                                                <span class="help-block">Add Remarks of Item</span>
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
                                        @if(isset($income) && $income->uuid)
                                            <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                    data-style="zoom-in" onclick="addUpdate('{{ $income->uuid }}')">
                                                <span class="ladda-label"><i class="fa fa-save"></i> Update</span>
                                            </button>
                                        @else
                                            <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                    data-style="zoom-in" onclick="addUpdate()">
                                                <span class="ladda-label"><i class="fa fa-save"></i> Save</span>
                                            </button>
                                        @endif
                                        <a type="button" class="btn default"
                                           href="{{ route('gym-admin.incomes.index') }}">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        {{ html()->form()->close() }}
                        <!-- END FORM-->
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                   @include('gym-admin.incomes.category')
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
    <script src="{{ asset("admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js") }}"></script>
    <script>
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true
        });

        function addUpdate(id) {

            var url;
            if (typeof id != 'undefined') {
                url = "{{route('gym-admin.incomes.update',':id')}}";
                url = url.replace(':id', id);
            } else {
                url = "{{route('gym-admin.incomes.store')}}";
            }

            $.easyAjax({
                type: "POST",
                url: url,
                file: true,
                container: '#create-edit-income'
            });
        }

        function addUpdateCategory(id) {
            var url ,container_data, data;
            if (typeof id != 'undefined') {
                url = "{{route('gym-admin.updateIncomeCategory',':id')}}";
                url = url.replace(':id', id);
                container_data = '#edit-category-'+id;
                data = $('#edit-category-'+id).serialize();
            } else {
                url = "{{route('gym-admin.addIncomeCategory')}}";
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
