@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css') !!}

    {!! HTML::style('admin/pages/css/invoice.min.css') !!}
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}

    <style type="text/css" media="print">
        .no-print {
            display: none;
        }

        .only-print {
            display: block;
        }
        @media print {
            @page {
              margin: 0;
            }
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
                <a href="{{ route('gym-admin.gym-invoice.index') }}">Invoices</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Generate Invoice</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner" id="printArea">

            <div class="row">
                <div class="col-xs-12">

                    <div class="portlet light portlet-fit">
                        <div class="portlet-title no-print">
                            <div class="caption">
                                <i class="icon-doc font-red"></i><span class="caption-subject font-red bold uppercase">Generate Invoice</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="invoice">
                                <div class="row invoice-logo">
                                    <div class="col-xs-6 invoice-logo-space">
                                        @if($gymSettings->front_image != '')
                                            {!! HTML::image(asset('/uploads/gym_setting/master/').'/'.$gymSettings->front_image, 'Logo',array("class" => "logo-style")) !!}
                                        @else
                                            {!! HTML::image(asset('/fitsigma/images').'/'.'fitness-plus.png', 'Logo',array("class" => "logo-style")) !!}
                                        @endif
                                    </div>
                                    <div class="col-xs-6">
                                        <p class="invoice-num">
                                            Invoice #{{ $invoice->invoice_number }}
                                        </p>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row invoice-cust-add">
                                    <div class="col-xs-6 col-md-3">
                                        <h4 class="invoice-title uppercase">Customer</h4>
                                        <p class="invoice-desc">
                                            {{ ucwords($invoice->client_name) }}
                                        </p>
                                    </div>
                                    @if($invoice->client_address != null)
                                    <div class="col-xs-6 col-md-3">
                                        <h4 class="invoice-title uppercase">Address</h4>
                                        <p class="invoice-desc inv-address">{{ ucwords($invoice->client_address) }}</p>
                                    </div>
                                    @endif
                                   
                                    @if($invoice->mobile != null)
                                    <div class="col-xs-6 col-md-3">
                                        <h4 class="invoice-title uppercase">Phone</h4>
                                        <p class="invoice-desc inv-address">{{ $invoice->mobile }}</p>
                                    </div>
                                    @endif
                                    <div class="col-xs-6 col-md-3">
                                        <h4 class="invoice-title uppercase">Date</h4>
                                        <p class="invoice-desc">{{ $invoice->invoice_date->format('M d, Y') }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th> #</th>
                                                <th> Item</th>
                                                <th class="hidden-xs"> Quantity</th>
                                                <th class="hidden-xs"> Cost Per Item</th>
                                                <th class="hidden-xs"> Discount(%)</th>
                                                <th> Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($invoice->items as $key=>$item)
                                                <tr>
                                                    <td> {{ $key + 1 }} </td>
                                                    <td> {{ ucfirst($item->item_name) }} </td>
                                                    <td class="hidden-xs"> {{ $item->quantity }} </td>
                                                    <td class="hidden-xs"> {{ $gymSettings->currency->acronym }} {{ $item->cost_per_item }} </td>
                                                    <td class="hidden-xs"> {{ $item->discount_amount }} </td>
                                                    <td> {{ $gymSettings->currency->acronym }} {{ $item->amount }} </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-md-4">
                                        @if($invoice->remarks != null)
                                        <address>
                                            <strong>Remarks</strong> <p>{{ $invoice->remarks}} </p>
                                         </address>
                                        @endif
                                        <div class="">
                                            <address>
                                                <strong>{{ ucwords($merchantBusiness->business->title) }}</strong>
                                                @if(!is_null($merchantBusiness->business->address))
                                                    <br>{{ ucfirst($merchantBusiness->business->address) }}@endif
                                                @if(!is_null($merchantBusiness->business->phone2))<br>
                                                        Phone:{{ $merchantBusiness->business->phone2 }} @endif
                                            </address>

                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-8 invoice-block">
                                        <ul class="list-unstyled amounts">
                                            <li>
                                                <strong>Sub Total:</strong> {{ $gymSettings->currency->acronym }} {{ round($invoice->sub_total, 2) }}
                                            </li>
                                            <li>
                                                <strong>Tax({{$tax[0]->gstin}}
                                                    )%:</strong> {{ $gymSettings->currency->acronym }} {{ round($invoice->tax, 2) }}
                                            </li>
                                            <li>
                                                <strong>Grand Total:</strong> {{ $gymSettings->currency->acronym }} {{ round($invoice->total, 2) }}
                                            </li>
                                        </ul>
                                        <br/>
                                        <a href="#" onclick="printDivArea('printArea')"
                                           class="btn btn-lg default hidden-print margin-bottom-5"> Print
                                            <i class="fa fa-print"></i>
                                        </a>
                                        @if($isDesktop)
                                            <a href="{{ route('gym-admin.gym-invoice.download-invoice', $invoice->id) }}"
                                               class="btn btn-lg green hidden-print margin-bottom-5"> Download
                                                <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <em>Invoice generated by: {{ ucwords($invoice->generated_by) }}</em>
                                        <p class="text-center">This is not a Tax Invoice <br>Thank You !!!</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

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

    <script>
       function printDivArea(printAreaId){
           var printcontent = document.getElementById('printArea').innerHTML;
           document.body.innerHTML = printcontent;
           window.print();
       }
    </script>
@stop
