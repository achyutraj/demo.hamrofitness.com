@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/datepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
@stop

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('gym-admin.client.index') }}">Customer</a>
            <i class="fa fa-circle"></i>
        </li>
        <li><span>Create</span></li>
    </ul>

    <div class="page-content-inner">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="list" style="list-style-type: none">
                    @foreach ($errors->all() as $error)
                        <li class="item">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('gym-admin.client.store') }}" method="post">
            @csrf
            <div class="col-md-12">
                <!-- Personal Details -->
                @include('gym-admin.gymclients.personal-details')

                <!-- Save Button -->
                <div class="portlet light">
                    <div class="row">
                        <div class="col-md-offset-5 col-md-2 text-center">
                            <button type="submit" class="btn blue mt-ladda-btn ladda-button" data-style="zoom-in">
                                <span class="ladda-label">
                                    <i class="icon-arrow-up"></i> Save
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@section('footer')
    <script src="{{ asset("admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}">
    <script src="{{ asset("admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>
    <script>
        $('#dob').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            endDate: '+0d',
            startView: 'decades'
        });
        $('#anniversary').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true
        });

        $('#dob').change(function() {
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

        $('input[name=marital_status]').on('change', function() {
           var value = $('input[name=marital_status]:checked').val();
            if (value == 'no') {
                $('#anniversaryDiv').css('display', 'none');
            } else {
                $('#anniversaryDiv').css('display', 'block');
            }
        });
    </script>
@stop
