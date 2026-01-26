@extends('layouts.merchant.login')

@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300&display=swap" rel="stylesheet">
{!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/datepicker.css') !!}
{!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
<style>
    body{
        font-family: 'Poppins', sans-serif;
        background-color: #d0cccc;
    }
    label , .control-label{
        font-weight: 600;
        color: #888 !important;
    }
</style>
@stop

@section('content')
    <div class="container">
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="login-logo mb-0">
                @if(!empty($logo))
                <a href="#">
                    <img src="">
                    {!! HTML::image(asset('/uploads/gym_setting/master/').'/'.$logo, 'Hamrofitness',['class' => 'img-responsive', 'style' => 'height: 100px;']) !!}
                </a>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption font-red">
                                <i class="bold uppercase font-red icon-plus"></i> {{ $branch->title}} Enquiry Form
                            </div>
                        </div>

                        <div class="portlet-body form">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list" style="list-style-type: none">
                                        @foreach ($errors->all() as $error)
                                            <li class="item">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session()->has('success'))
                            <div class="alert alert-success">
                                <ul class="list" style="list-style-type: none">
                                    <li class="item">{!! session('success') !!}</li>
                                </ul>
                            </div>
                            @endif
                            <p class="text-danger">Note: * Field are required</p>

                            <form action="{{route('enquiry.store')}}" method="post">
                                @csrf
                                <input type="hidden" name="branch" value="{{ $branch->id}}">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input ">
                                            <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name') }}"
                                                   id="customer_name">
                                            <label for="form_control_1">Customer First Name <span class="required"
                                                                                            aria-required="true"> * </span></label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input ">
                                            <input type="text" class="form-control" name="customer_mname" value="{{ old('customer_mname') }}"
                                                   id="customer_mname">
                                            <label for="form_control_1">Customer Middle Name</label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input ">
                                            <input type="text" class="form-control" name="customer_lname" value="{{ old('customer_lname') }}"
                                                   id="customer_lname">
                                            <label for="form_control_1">Customer Last Name <span class="required"
                                                                                            aria-required="true"> * </span></label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input">
                                            <input type="number" class="form-control" name="mobile" id="mobile" value="{{ old('mobile') }}">
                                            <label for="form_control_1">Mobile <span class="required"a
                                                                                     aria-required="true"> * </span></label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input">
                                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                            <label for="form_control_1">Customer Email <span class="required"
                                                aria-required="true"> * </span></label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input">
                                            <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                                            <label for="form_control_1">Address <span class="required"
                                                aria-required="true"> * </span></label>
                                            <div class="form-control-focus"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-md-line-input">
                                            <div class="form-group form-md-radios">
                                                <div class="md-radio-inline">
                                                    <div class="md-radio">
                                                        <input type="radio" id="sex-1" checked name="sex" value="Male"
                                                               class="md-radiobtn">
                                                        <label for="sex-1">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Male </label>
                                                    </div>
                                                    <div class="md-radio">
                                                        <input type="radio" id="sex-2" name="sex" class="md-radiobtn"
                                                               value="Female">
                                                        <label for="sex-2">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Female</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions noborder">
                                <button type="submit" class="btn green mt-ladda-btn ladda-button" data-style="zoom-in"
                                        id="save-form">
                                            <span class="ladda-label">
                                                <i class="fa fa-save"></i> SAVE</span>
                                    <span class="ladda-spinner"></span>
                                    <div class="ladda-progress" style="width: 0px;"></div>
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT INNER -->
        </div>
    </div>
@stop

@section('js')
{!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
{!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}

    <script>

        $('#dob').change(function () {
            var lre = /^\s*/;

            var inputDate = document.getElementById('dob').value;
            inputDate = inputDate.replace(lre, "");

            age = get_age(new Date(inputDate));

            $('#age').val(age);

        });

        function get_age(birth) {
            var today = new Date();
            var nowyear = today.getFullYear();
            var nowmonth = today.getMonth();
            var nowday = today.getDate();

            var birthyear = birth.getFullYear();
            var birthmonth = birth.getMonth();
            var birthday = birth.getDate();

            var age = nowyear - birthyear;
            var age_month = nowmonth - birthmonth;
            var age_day = nowday - birthday;

            if (age_month < 0 || (age_month == 0 && age_day < 0)) {
                age = parseInt(age) - 1;
            }
            return age;


        }

        $('.exercise-type').css('display', 'none');
        $(function () {
            $("input[name='exercise_regularly']").change(function () {
                if ($(this).val() === 'Yes') {
                    $('.exercise-type').css('display', 'block');
                } else {
                    $('.exercise-type').css('display', 'none');
                }
            });
        });
    </script>
@stop
