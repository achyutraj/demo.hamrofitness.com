@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Settings
@endsection

@section('CSS')
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
    <style>
        .alert-dismissable .close, .alert-dismissible .close {
            top: -35px
        }
    </style>
@stop

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Settings</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li>Developers</li>
                <li class="active">Settings</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="card-body">
                    <span class="btn btn-primary me-1 mb-1 generate-token">Generate Token</span>
                    <a href="{{ route('customer-app.developer.docs') }}" class="btn btn-outline-primary mb-1">Read the Docs</a>
                    <hr>
                    <div class="col-5 alert alert-success alert-dismissible inactive">
                        <strong><p id="showMessage"></p>
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        </strong>
                    </div>
                    <div class="mt-2 row">
                        <div class="col-12">
                            <p class="font-medium-2">API EndPoint</p>
                            <span class="text-primary font-medium-2">{{config('app.url')}}/api/</span>
                        </div>
                    </div>

                    <div class="row mt-2">

                        <div class="col-12">
                            <p class="font-medium-2">API Token</p>
                            <span class="font-medium-2 text-primary" id="copy-to-clipboard-input">{{ $api_token }} </span>
                            <span id="btn-copy" data-bs-toggle="tooltip" data-placement="top" title="{{ __('locale.labels.copy') }}">
                                <i data-feather="clipboard" class="font-large-1 text-info cursor-pointer"></i>
                            </span>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('JS')
    {!! HTML::script('admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! HTML::script('admin/pages/scripts/components-bootstrap-select.min.js') !!}
    {!! HTML::script('admin/global/plugins/dropzone/dropzone.min.js')  !!}
    {!! HTML::script('admin/pages/scripts/form-dropzone.min.js')  !!}
    {!! HTML::script('js/cropper.js') !!}

    <script>
        $('.inactive').hide();
        $('.generate-token').on('click',function(){
            $.ajax({
                url: '{{ route('customer-app.developer.generate') }}',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success:function (res){
                    $('.inactive').show()
                    document.getElementById('showMessage').innerHTML = res.message;
                },
                error: function(error){
                    //
                }
            })
        })
    </script>
@endsection
