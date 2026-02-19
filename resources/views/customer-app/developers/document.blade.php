@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Api Documents
@endsection

@section('CSS')
    <style>
        .anniversary-display {
            display: none;
        }

        .padding-top-btn {
            padding-top: 20px;
        }

    </style>
@endsection

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Api Documents</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li>Developers</li>
                <li class="active">Api Documents</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box p-l-20 p-r-20">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title">

                            </div>
                            <div class="portlet-body">
                                <ul class="nav nav-pills">
                                    <li class="active" id="dashboard">
                                        <a href="#tab_2_1" data-toggle="tab"> Dashboard API </a>
                                    </li>
                                    <li id="subscription">
                                        <a href="#tab_2_2" data-toggle="tab"> Subscription API </a>
                                    </li>
                                    <li id="profile">
                                        <a href="#tab_2_3" data-toggle="tab"> Profile API </a>
                                    </li>
                                    <li id="membership">
                                        <a href="#tab_2_4" data-toggle="tab"> Membership API </a>
                                    </li>
                                    <li id="products">
                                        <a href="#tab_2_5" data-toggle="tab"> Products API </a>
                                    </li>
                                    <li id="attendance">
                                        <a href="#tab_2_6" data-toggle="tab"> Attendance API </a>
                                    </li>
                                    <li id="message">
                                        <a href="#tab_2_7" data-toggle="tab"> Message API </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="tab_2_1">
                                        @include('customer-app.developers.dashboard')
                                    </div>
                                    <div class="tab-pane fade in" id="tab_2_2">
                                        @include('customer-app.developers.subscription')
                                    </div>
                                    <div class="tab-pane fade in" id="tab_2_3">
                                        @include('customer-app.developers.profile')
                                    </div>
                                    <div class="tab-pane fade in" id="tab_2_4">
                                        @include('customer-app.developers.membership')
                                    </div>
                                    <div class="tab-pane fade in" id="tab_2_5">
                                        @include('customer-app.developers.product')
                                    </div>
                                    <div class="tab-pane fade in" id="tab_2_6">
                                        @include('customer-app.developers.attendance')
                                    </div>
                                    <div class="tab-pane fade in" id="tab_2_7">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('JS')
    <script src="{{ asset("fitsigma_customer/bower_components/jquery/dist/jquery.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/jquery.blockui.min.js") }}"></script>
    <script src="{{ asset("admin/admin/layout3/scripts/layout.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js") }}"></script>
    <script src="{{ asset("fitsigma_customer/bower_components/counterup/jquery.counterup.min.js") }}"></script>
    <script src="{{ asset("fitsigma_customer/js/custom.min.js") }}"></script>

@endsection
