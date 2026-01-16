@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
    <style>
        #manage-branches_wrapper {
            margin-top: 20px;
        }

        .marquee {
            display: block;
            height: 30px;
            overflow: hidden;
            position: relative;
            color: black;
        }

        .marquee p {
            position: absolute;
            white-space: nowrap;
            width: 100%;
            height: 100%;
            margin: 5px;
            text-align: center;
            /* Starting position */
            -moz-transform: translateX(100%);
            -webkit-transform: translateX(100%);
            transform: translateX(100%);
            /* Apply animation to this element */
            -moz-animation: scroll-left 13s linear infinite;
            -webkit-animation: scroll-left 13s linear infinite;
            animation: scroll-left 13s linear infinite;
        }

        /* Move it (define the animation) */
        @-moz-keyframes scroll-left {
            0% {
                -moz-transform: translateX(100%);
            }
            100% {
                -moz-transform: translateX(-100%);
            }
        }
        @-webkit-keyframes scroll-left {
            0% {
                -webkit-transform: translateX(100%);
            }
            100% {
                -webkit-transform: translateX(-100%);
            }
        }
        @keyframes scroll-left {
            0% {
                -moz-transform: translateX(100%); /* Browser bug fix */
                -webkit-transform: translateX(100%); /* Browser bug fix */
                transform: translateX(100%);
            }
            100% {
                -moz-transform: translateX(-100%); /* Browser bug fix */
                -webkit-transform: translateX(-100%); /* Browser bug fix */
                transform: translateX(-100%);
            }
        }
    </style>
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Product Sales</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light ">
                        @if(session()->has('message'))
                            <div class="alert alert-message alert-success">
                                {{session()->get('message')}}
                            </div>
                        @endif
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="fa fa-dropbox font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Product Sales</span>
                            </div>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a class="btn sbold dark" href="{{route('gym-admin.sales.create')}}">Sell Product<i
                                        class="fa fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="marquee" behavior="scroll" direction="left">
                                    <p class="bold uppercase">
                                        @foreach($products as $product)
                                            @if(($product->quantity - ($product->quantity_expired + $product->quantity_damaged + $product->quantity_sold)) == 0)
                                                <span style="color: green;">{{$product->name}}</span> <span
                                                    style="color: red"> :  Out Of Stock</span> <span> @if(!$loop->last) | @endif </span>
                                            @endif
                                            <span style="color: green;">{{$product->name}}</span> <span
                                                class="font-red"> : Rs {{$product->price}}</span> <span> @if(!$loop->last) | @endif </span>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover table-100"
                                           id="manage-branches">
                                        <thead>
                                        <tr>
                                            <th class="desktop">Consumer Name</th>
                                            <th class="desktop">Product Name</th>
                                            <th class="desktop">Purchased At</th>
                                            <th class="desktop">Payment Required</th>
                                            <th class="desktop">Total Price</th>
                                            <th class="desktop">Remaining</th>
                                            <th class="desktop">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($soldProduct as $product)
                                            @php
                                                $arr['product_name'] = json_decode($product->product_name,true);
                                                $arr['product_amount'] = json_decode($product->product_amount,true);
                                                $j= count($arr['product_name']);
                                            @endphp
                                            <tr>
                                                <td>{{$product->customer_name}}</td>
                                                <td>
                                                    @for($i=0;$i<$j;$i++)
                                                        <?php
                                                        $product_name = App\Models\Product::find($arr['product_name'][$i]);
                                                        ?>
                                                        {{ $product_name->name ?? '' }} <br>
                                                    @endfor
                                                </td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $product->created_at)->toFormattedDateString()}}</td>
                                                <td>
                                                    <span class="badge badge-{{ $product->payment_required == "yes" ? 'success' : 'danger' }}">{{$product->payment_required}}</span>
                                                </td>
                                                <td>
                                                    {{$gymSettings->currency->acronym}} {{$product->total_amount}}
                                                </td>
                                                <td>
                                                    {{$gymSettings->currency->acronym}} {{$product->total_amount - $product->paid_amount}}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn green btn-xs dropdown-toggle" type="button"
                                                                data-toggle="dropdown"><i class="fa fa-gears"></i><span
                                                                class="hidden-xs hidden-medium">Actions</span>
                                                            <i class="fa fa-angle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right" role="menu">

                                                            <li>
                                                                <a href="{{route('gym-admin.sales.download',$product->id)}}"><i
                                                                        class="fa fa-download"></i> Download </a>
                                                            </li>

                                                            <li>
                                                                <a data-toggle="modal"
                                                                   data-target="#productSalesView{{$product->id}}"><i
                                                                        class="fa fa-search"></i> View</a>
                                                            </li>
                                                            @if($product->payment_required == "yes")
                                                            <li>
                                                                <a href="{{route('gym-admin.sales.edit',$product->id)}}"><i
                                                                        class="fa fa-edit"></i> Edit</a>
                                                            </li>
                                                                <li>
                                                                    <a data-toggle="modal" data-target="#deleteProduct{{$product->id}}"><i
                                                                            class="fa fa-trash"></i> Delete</a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    {{-- view purchase info--}}
                                                    <div class="modal" tabindex="-1"
                                                         id="productSalesView{{$product->id}}" role="dialog">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 style="font-weight: 600;" class="modal-title">
                                                                        Product Sales Data for <span
                                                                            style="color: red;">{{$product->customer_name}}</span>
                                                                        purchased at <span
                                                                            style="color: red;">{{$product->created_at->format('Y-m-d')}}</span>
                                                                    </h4>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div id="printArea">
                                                                    @include('gym-admin.products.sales.show')
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a href="#" onclick="printDivArea('printArea')"
                                                                       class="btn btn-lg default hidden-print margin-bottom-5"> Print
                                                                        <i class="fa fa-print"></i>
                                                                    </a>

                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{--Delete Purchase Modal--}}
                                                    <div class="modal fade bs-modal-md in"
                                                         id="deleteProduct{{$product->id}}" role="dialog"
                                                         aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-md" id="modal-data-application">
                                                            {!! Form::open(['route' => ['gym-admin.sales.destroy', $product->id], 'class' => 'delete', 'method' => 'DELETE']) !!}
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                    <span class="caption-subject font-red-sunglo bold uppercase">Remove Product sale</span>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p> Are you Sure you want to Remove ?</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"  class="btn blue">Remove</button>
                                                                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                                                                </div>
                                                            </div>
                                                            {!! Form::close() !!}

                                                        </div>
                                                    </div>

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
@endsection
@section('footer')
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    <script>
        function printDivArea(printAreaId){
            var printcontent = document.getElementById('printArea').innerHTML;
            document.body.innerHTML = printcontent;
            window.print();
        }
        var table = $('#manage-branches');
        table.dataTable({
            responsive: true,
            lengthMenu: [
                [25, 50, 75 , 100, -1],
                ['25', '50','75' ,'100', 'All']
            ],
            pageLength: 25,
        });
        $(window).ready(function () {
            setTimeout(function () {
                $(".alert-success").remove();
            }, 3000);
        });
    </script>
@endsection
