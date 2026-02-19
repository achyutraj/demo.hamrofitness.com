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
                <a href="{{ route('gym-admin.products.index') }}">Product</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@if(isset($product->uuid)) Edit @else Add @endif Product</span>
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
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">@if(isset($product->uuid))
                                        Edit @else Add @endif Product</span></div>


                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {{ html()->form()->open(['id'=>'create-edit-product','class'=>'ajax-form']) }}
                                <div class="form-body">
                                    @if(isset($product->uuid))
                                        <input type="hidden" name="_method" value="PUT">
                                    @endif
                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <select class="form-control select2" name="supplier_id"
                                                            id="supplier_id">
                                                        <option selected disabled>Please Select</option>
                                                        @foreach($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}" {{ isset($product) && ($product->supplier_id == $supplier->id) ? 'selected' : ''}}>{{ $supplier->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="supplier_id">Purchase From <span class="required"
                                                        aria-required="true"> * </span></label>
                                                    <span class="help-block">Add Supplier Name</span>
                                                    <i class="fa fa-shopping-cart"></i>
                                                </div>
                                                @if( !isset($product->uuid)) 
                                                    <a class="btn btn-xs btn-success" href="{{ route('gym-admin.suppliers.create')}}" title="Add Supplier">Add</a>
                                                @endif

                                                @error('supplier_id')
                                                    <div style="color: red;">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <input type="text" class="form-control" placeholder="Product Name"
                                                        name="name" value="{{$product->name ?? ''}}">
                                                    <label for="name">Product Name<span class="required"
                                                                                            aria-required="true"> * </span></label>
                                                    <span class="help-block">Add Product Name</span>
                                                    <i class="fa fa-sticky-note-o"></i>
                                                </div>
                                                @error('name')
                                                    <div style="color: red;">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <input type="text" class="form-control" placeholder="Product Tag"
                                                        name="tag" value="{{$product->tag ?? ''}}">
                                                    <label for="tag">Product Tag</label>
                                                    <span class="help-block">Add Product Tag</span>
                                                    <i class="fa fa-sticky-note-o"></i>
                                                </div>
                                                @error('tag')
                                                    <div style="color: red;">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <input type="text" class="form-control" placeholder="Product Brand"
                                                        name="brand_name" value="{{$product->brand_name ?? ''}}">
                                                    <label for="brand_name">Product Brand</label>
                                                    <span class="help-block">Add Product Brand</span>
                                                    <i class="fa fa-sticky-note-o"></i>
                                                </div>
                                                @error('brand_name')
                                                    <div style="color: red;">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <input type="number" class="form-control" placeholder="Product Quantity"
                                                        name="quantity" value="{{$product->quantity ?? ''}}">
                                                    <label for="quantity">Product Quantity</label>
                                                    <span class="help-block">Add Product Quantity</span>
                                                    <i class="fa fa-sticky-note-o"></i>
                                                </div>
                                                @error('quantity')
                                                    <div style="color: red;">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input ">
                                                <div class="input-icon">
                                                    <input type="number" class="form-control" placeholder="Price" name="price" style="padding-left: 50px"
                                                        value="{{$product->price ?? ''}}">
                                                    <label for="price">Product Price Per Piece<span class="required"
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

                                    <div class="form-group form-md-line-input ">
                                        <div class="input-group left-addon right-addon">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control date-picker" readonly
                                                name="purchase_date"
                                                value="@if(isset($product->uuid)) {{ \Carbon\Carbon::parse($product->purchase_date)->format('m/d/Y') }} @else {{ \Carbon\Carbon::now('Asia/Kathmandu')->format('m/d/Y') }} @endif">
                                            <label for="purchase_date">Purchase Date<span class="required"
                                                                                        aria-required="true"> * </span></label>
                                        </div>
                                        @error('purchase_date')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group form-md-line-input ">
                                        <div class="input-group left-addon right-addon">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control date-picker" readonly
                                                name="expiry_date"
                                                value="@if(isset($product->uuid) && !is_null($product->expiry_date)) {{ \Carbon\Carbon::parse($product->expiry_date)->format('m/d/Y') }} @endif">
                                            <label for="expiry_date">Expiry Date </label>
                                        </div>
                                        @error('expiry_date')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if(isset($product) && $product->uuid)
                                                <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                        data-style="zoom-in" onclick="addUpdate('{{ $product->uuid }}')">
                                                    <span class="ladda-label"><i class="fa fa-save"></i> Update</span>
                                                </button>
                                            @else
                                                <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                        data-style="zoom-in" onclick="addUpdate()">
                                                    <span class="ladda-label"><i class="fa fa-save"></i> Save</span>
                                                </button>
                                            @endif
                                            <a type="button" class="btn default"
                                            href="{{ route('gym-admin.products.index') }}">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            {{ html()->form()->close() }}
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
                url = "{{route('gym-admin.products.update',':id')}}";
                url = url.replace(':id', id);
            } else {
                url = "{{route('gym-admin.products.store')}}";
            }

            $.easyAjax({
                type: "POST",
                url: url,
                container: '#create-edit-product',
                data: $('#create-edit-product').serialize(),
            });
        }
    </script>
@stop
