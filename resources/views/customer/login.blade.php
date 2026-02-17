@extends('layouts.customer.login')

@section('title')
    HamroFitness | Customer Login
@endsection
@section('CSS')
    <style>
        .view {
            float: right;
            margin-top: -34px;
        }
    </style>
@endsection

@section('content')
    <div class="login-box">
        <div class="login-logo">
            @if(is_null($gymSettings))
                {!! HTML::image(asset('/fitsigma/images/').'/'.'fitsigma-logo-full-red.png', 'Hamrofitness',['class' => 'img-responsive inline-block', 'style' => 'height: 60px;']) !!}
            @else
                @if($gymSettings->image != '')
                    {!! HTML::image($gymSettingPath.$gymSettings->image, 'Hamrofitness',array('class' => 'img-responsive inline-block', 'style' => 'height: 60px;')) !!}
                @else
                    {!! HTML::image(asset('/fitsigma/images/').'/'.'fitsigma-logo-full-red.png', 'Hamrofitness',['class' => 'img-responsive inline-block', 'style' => 'height: 60px;']) !!}
                @endif
            @endif

            <a href="{{ route('gym-admin.dashboard.index') }}"><img src="{{asset('fitsigma/images/fitsigma-logo-full-red.png')}}" alt="" height="40"></a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Sign In</p>
            {!! Form::open(array('route' => ['merchant.login.store'], 'method' => 'POST', "id" => "login-form", "class" => 'login-form')) !!}
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-danger display-hide" id="error-message">
                        <span id="error-message"></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="email"><b>Email Address</b> <span class="required">*</span> </label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" id="email"
                       placeholder="Email">
            </div>
            <div class="form-group">
                <label for="password"><b>Password</b> <span class="required">*</span></label>
                <div>
                    <input type="password" name="password" class="form-control" id="password"
                           placeholder="Password">
                    <a class="btn blue icn-only view"><i class="fa fa-eye size-icon"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label class="rememberme mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="remember" value="1"/> Remember me
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn blue btn-block" type="submit">Sign In</button>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@endsection

@section('JS')
    <script>
        $('#login-form').on('submit', function (event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route('customer.store') }}',
                type: 'POST',
                data: $('#login-form').serialize(),
                container: '#login-form',
                success: function (response) {
                    if (response.status == 'fail') {
                        $('.display-hide').css('display', 'block');
                        $('#error-message').addClass("alert alert-danger");
                        $('#error-message').html(response.message);
                    } else {
                        $('#error-message').removeClass("alert-danger");
                        $('#error-message').addClass("alert-success");
                        $('.display-hide').css('display', 'block');
                        $('#error-message').html(response.message);
                        window.location.href = response.url;
                    }
                }
            });
            return false;
        });
    </script>
@endsection
