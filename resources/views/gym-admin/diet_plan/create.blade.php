@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/datatables/datatables.min.css') !!}
    {!! HTML::style('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
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
                <span>Diet Plan</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
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
                                <i class="icon-users font-red"></i>
                                <span class="caption-subject font-red bold uppercase"> Diet Plan</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="defaultDietPlan" data-toggle="tab"
                                               href="#defaultDiet" role="tab"
                                               aria-controls="defaultDiet" aria-selected="true">Default Diet Plan</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="userDietPlan" data-toggle="tab" href="#userDiet"
                                               role="tab" aria-controls="userDiet"
                                               aria-selected="false">User Diet Plan</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        {{-- contents of default diet plan --}}
                                        <div class="tab-pane fade show active" id="defaultDiet" role="tabpanel"
                                             aria-labelledby="home-tab">
                                            @include('gym-admin.diet_plan.default')
                                        </div>
                                        {{-- contents of individual diet Plan --}}
                                        <div class="tab-pane fade" id="userDiet" role="tabpanel"
                                             aria-labelledby="profile-tab">
                                            @include('gym-admin.diet_plan.client')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
    {{--Modal Start--}}


    {{--End Modal--}}
@stop

@section('footer')
    {!! HTML::script('admin/global/scripts/datatable.js') !!}
    {!! HTML::script('admin/pages/scripts/table-datatables-managed.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/datatables.min.js') !!}
    {!! HTML::script('admin/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('admin/global/plugins/bootbox/bootbox.min.js') !!}

    <script>
        $(function () {
            setTimeout(function () {
                $('.alert-message').slideUp();
            }, 3000);
        });
        $('select.select2').select2({
            placeholder: "Please Select",
        }).focus(function () {
            $(this).select2('focus');
        });
        $(document).ready(function () {
            $('#paymentTable').DataTable();
        });
    </script>
    <script>
        $('.bs-select').select2();
        $('#myTab a[href="#defaultDiet"]').tab('show') // Select tab by name
        $('#myTab li:first-child a').tab('show') // Select first tab
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            setTimeout(function () {
                location.reload();
            }, 100);

        }
    </script>
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var UIBootbox = function () {
            var o = function () {
                $(".client-diet-remove").click(function () {
                    var url = $(this).data('client_diet_url');
                    bootbox.confirm({
                        message: "Do you want to delete this diet plan?",
                        buttons: {
                            confirm: {
                                label: "Yes",
                                className: "btn-primary"
                            }
                        },
                        callback: function(result){
                            if(result){
                                $.easyAjax({
                                    url: url,
                                    type: 'POST',
                                    data: {
                                        '_method': 'delete' , '_token': '{{ csrf_token() }}'
                                    },
                                    success: function(){
                                        location.reload();
                                    }
                                });
                            }
                            else {
                                console.log('cancel');
                            }
                        }
                    })
    
                })
            };
            return {
                init: function () {
                    o()
                }
            }
        }();
        jQuery(document).ready(function () {
            UIBootbox.init()
        });
    </script>
@stop
