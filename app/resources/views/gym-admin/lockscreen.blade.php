@extends('layouts.merchant.locked')

@section('content')
    <div class="page-lock">
        <div class="panel">
            <div class="panel-body">
                <div class="page-logo">
                    <a class="brand" href="javascript:;">
                        @if(is_null($gymSettings))
                            {!! HTML::image(asset('/fitsigma/images/').'/'.'fitness-plus.png', 'Logo',array("class" => "img-responsive")) !!}
                        @else
                            @if($gymSettings->front_image != '')
                                {!! HTML::image(asset('/uploads/gym_setting/master/').'/'.$gymSettings->front_image, 'Logo',array("class" => "img-responsive")) !!}
                            @else
                                {!! HTML::image(asset('/fitsigma/images').'/'.'fitness-plus.png', 'Logo',array("class" => "img-responsive")) !!}
                            @endif
                        @endif
                    </a>
                </div>
                <div class="page-body">
                    @if($userValue->image == '')
                        <img class="page-lock-img" src="{{ asset('/fitsigma/images/').'/'.'user.svg' }}" alt="">
                    @else
                        <img class="page-lock-img" src="{{ $profileHeaderPath.$userValue->image }}" alt="">
                    @endif
                    <div class="page-lock-info">
                        <h1>{{ $userValue->first_name }}</h1>
                        <small> {{ $userValue->email }} </small><br/>
                        <span class="locked"> Locked </span>
                        {!! Form::open(array('route' => ['merchant.lockLogin'], 'method' => 'POST', "id" => "login-form", "class" => 'form-inline')) !!}
                        <div id="error-message"></div>
                        <div class="input-group input-medium">
                            <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                            <span class="input-group-btn">
                                <a class="btn blue icn-only view">
                                    <i class="fa fa-eye size-icon"></i>
                                </a>
                            </span>
                            <span class="input-group-btn">
                                <button type="submit" class="btn green icn-only">
                                    <i class="fa fa-arrow-circle-o-right size-icon"></i>
                                </button>
                            </span>
                        </div>
                        <!-- /input-group -->
                        <div class="relogin">
                            <a href="{{ route('merchant.logout') }}"> Not {{ $userValue->first_name }} ? </a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <small> {{ \Carbon\Carbon::now('Asia/Kathmandu')->year }} &copy; HamroFitness </small>
            </div>
        </div>
    </div>
    <style>
        body {
            color: #111;
        }
    </style>
@stop
