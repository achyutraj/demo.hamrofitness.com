@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
    <style>
        .m-heading-2 {
            margin: 0 0 20px;
            background: #fff;
            padding-left: 15px;
            border-top: 8px solid #88909a;
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
                <span>Fitness Calculator</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="m-heading-1 border-red m-bordered">
                        <h3>Fitness Calculator</h3>
                        <ul>
                            <li><strong>BODY MASS INDEX </strong> - <span> helps to assess health risk and tracks change in weight but BMI should not be used as the sole indicator of a person's health.</span></li>
                            <li><strong>BODY FAT </strong><span>- percentage may vary as there is no universally accepted method and other factors such as hydration levels, menstrual cycle, and overall health.</span></li>
                            <li><strong> CALORIE REQUIRED </strong>- <span> may change over time, hence should be regularly reassessed based on changes in weight, activity level, and overall health.</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-target font-red"></i><span class="caption-subject font-red bold uppercase">Select Calculator Type</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            {{ html()->form->open(['id'=>'createTargetReport','class'=>'ajax-form']) !!}
                            <div class="form-body">
                                <div class="form-group form-md-line-input ">
                                    <select class="bs-select form-control targetData" data-live-search="true"
                                            data-size="8" name="type" id="type">
                                        <option value="bmi" selected> Body Mass Index (BMI)</option>
                                        <option value="fat"> Body Fat</option>
                                        <option value="calorie"> Calorie</option>
                                    </select>
                                    <label for="title">Select Calculator</label>
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="number" class="form-control" min="21" max="80" name="age" id="age" placeholder="Enter Age between 21 and 80">
                                    <span class="help-block">Enter Your age.</span>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <div class="form-group form-md-radios">
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" id="gender-1" name="gender" checked value="male" class="md-radiobtn">
                                                <label for="gender-1"><span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Male </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="gender-2" name="gender" class="md-radiobtn" value="female">
                                                <label for="gender-2"><span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" name="height" id="height" placeholder="Enter Height in cm">
                                    <span class="help-block">Enter Your Height in cm.</span>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" name="weight" id="weight" placeholder="Enter Weight in KG">
                                    <span class="help-block">Enter Your weight in KG.</span>

                                </div>

                                <div id="fat_calculate">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" name="neck" id="neck" placeholder="Enter Neck in cm">
                                    <span class="help-block">Enter Your Neck size in cm.</span>

                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" name="waist" id="waist" placeholder="Enter Waist in cm">
                                    <span class="help-block">Enter Your Waist size in cm.</span>
                                </div>

                                <div id="fat_hip">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" name="hip" id="hip" placeholder="Enter Hip in cm">
                                        <span class="help-block">Enter Your Hip size in cm.</span>
                                    </div>
                                </div>
                                </div>

                                <div id="calorie_calculate">
                                <div class="form-group form-md-line-input ">
                                    <select class="bs-select form-control targetData" data-live-search="true"
                                            data-size="8" name="activity" id="activity">
                                        <option value="null" selected> Select Activity</option>
                                        <option value="1" >Basal Metabolic Rate (BMR)</option>
                                        <option value="1.2" >Sedentary: little or no exercise</option>
                                        <option value="1.375" >Light: exercise 1-3 times/week</option>
                                        <option value="1.465" selected>Moderate: exercise 4-5 times/week</option>
                                        <option value="1.55" >Active: daily exercise or intense exercise 3-4 times/week</option>
                                        <option value="1.725" >Very Active: intense exercise 6-7 times/week</option>
                                        <option value="1.9" >Extra Active: very intense exercise daily, or physical job</option>
                                    </select>
                                    <label for="title">Select Activity</label>
                                    <span class="help-block"></span>
                                </div>
                                </div>
                                <div class="form-actions" style="margin-top: 70px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                    data-style="zoom-in" id="calculate">
                                                <span class="ladda-label"><i class="icon-arrow-up"></i> Calculate</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ html()->form->close() !!}
                        </div>

                        <div class="col-lg-7 col-md-7 col-sm-12" id="result_data">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
    <script src="{{ asset("admin/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>
    <script>
        $('#fat_calculate').hide();
        $('#calorie_calculate').hide();
        $('#fat_hip').hide();
        $('#type').on('change',function(){
           var type = $(this).val();
           if(type == 'fat'){
               $('#gender-1').on('click',function(){
                   $('#fat_hip').hide();
               });
               $('#gender-2').on('click',function(){
                   $('#fat_hip').show();
               });
               $('#fat_calculate').show();
               $('#calorie_calculate').hide();
           }
            if(type == 'calorie'){
                $('#fat_calculate').hide();
                $('#calorie_calculate').show();
            }
        });
        $('#calculate').on('click',function(){

            var age = $('#age').val();
            var height = $('#height').val();
            var weight = $('#weight').val();
            var type = $('#type').val();
            if(age < 21){
                $.showToastr('Age must be greater than or equal to 21', 'error');
            }
            if(height && weight && age >= 21){
                $.easyAjax({
                    url: "{{route('gym-admin.calculation')}}",
                    container: '#createTargetReport',
                    type: "POST",
                    data: $('#createTargetReport').serialize(),
                    success: function (res) {
                        if (res.status == 'success') {
                            $('#result_data').html(res.data);
                        }
                    }
                });
            }
        });

    </script>
@stop
