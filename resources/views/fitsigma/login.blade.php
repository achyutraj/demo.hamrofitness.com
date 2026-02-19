@extends('layouts.merchant.login')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('gym-admin.dashboard.index') }}">
                @if(is_null($gymSettings))
                    {{ html()->img(asset('/fitsigma/images/').'/'.'fitsigma-logo-full-red.png', 'Hamrofitness')->attributes(['class' => 'img-responsive inline-block', 'style' => 'height: 60px;']) }}
                @else
                    @if($gymSettings->image != '')
                        {{ html()->img($gymSettingPath.$gymSettings->image, 'Hamrofitness')->attributes(array('class' => 'img-responsive inline-block', 'style' => 'height: 60px;')) }}
                    @else
                        {{ html()->img(asset('/fitsigma/images/').'/'.'fitsigma-logo-full-red.png', 'Hamrofitness')->attributes(['class' => 'img-responsive inline-block', 'style' => 'height: 60px;']) }}
                    @endif
                @endif
            </a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Sign In</p>
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-danger display-hide" id="error-message">
                        <span id="error-message"></span>
                    </div>
                </div>
            </div>
            {{ html()->form()
        ->id('login-form')
        ->class('login-form')
        ->open() }}


            {{-- {{ html()->form->open(array("id" => "login-form", "class" => 'login-form')) }} --}}

            <div class="form-group">
                <label for="username"><b>Username</b> <span class="required">*</span> </label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" id="username"
                       placeholder="Username">
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
            {{ html()->form()->close() }}

        </div>
        <div class="panel panel-body text-center" style="margin-top: 16px">
            <span class="support-heading"><b>For Any Support</b></span>
            <br><p>Mobile : 9851096919 <br>
                Email : info@encodenepal.com</p>
        </div>
    </div>
@stop
@section('js')
<script>
    $('#login-form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('merchant.login.store') }}",
            type: 'POST',
            data: $('#login-form').serialize(),
            container: '#login-form',
            success: function (response) {
                if (response.success === false) {
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

    $('.view').on('click',function(){
        var p = document.getElementById('password');
        if(p.getAttribute("type") == 'password'){
            p.setAttribute('type', 'text');
        }else{
            p.setAttribute('type', 'password');
        }
    })
    var image_1;

    $('.login-bg').backstretch([
            image_1
        ], {
            fade: 1000,
            duration: 8000
        }
    );

    $('.forget-form').hide();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var image_1 = '{{ asset("admin/pages/media/bg/1.png") }}';
    var image_2 = '{{ asset("admin/pages/media/bg/2.png") }}';
    var image_3 = '{{ asset("admin/pages/media/bg/3.png") }}';

    $.backstretch([
        image_2,
        image_1,
        image_3
    ], {
        fade: 1000,
        duration: 8000
    });
</script>
@endsection
