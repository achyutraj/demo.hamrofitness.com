@extends('gym-admin.message.index')

@push('show-styles')
    <link rel="stylesheet" href="{{ asset("fitsigma/css/merchant-chat.css") }}">
@endpush

@section('inbox')
    <div class="inbox-header">
        <h1 class="pull-left">View Message</h1>
    </div>
    <div class="inbox-content" style="">
        <div class="chat-box" id="one">
            <ul class="chat-list p-t-30" id="two">
                @if($messageByUser == 'customer')
                    @foreach($messages as $message)
                        <li @if($message->to == 'customer') class="odd" @endif>
                            <div class="chat-image"><img src=""></div>
                            <div class="chat-body">
                                <div class="chat-text">
                                    <h4>@if($message->to == 'customer') {{ $message->merchant->first_name.' '.$message->merchant->last_name }} @else {{ $message->client->fullName }} @endif</h4>
                                    <p> {{ nl2br($message->text) }} </p>
                                    <b>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->diffForHumans() }}</b>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif
                @if($messageByUser == 'employee')
                    @foreach($messages as $message)
                        <li @if($message->to == 'employee') class="odd" @endif>
                            <div class="chat-image"><img src=""></div>
                            <div class="chat-body">
                                <div class="chat-text">
                                    <h4>@if($message->to == 'employee') {{ $message->merchant->first_name.' '.$message->merchant->last_name }} @else {{ $message->employee->fullName }} @endif</h4>
                                    <p> {{ nl2br($message->text) }} </p>
                                    <b>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->diffForHumans() }}</b>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="row text-box">
            <div class="col-md-12">
                <div class="col-md-11">
                    <textarea class="form-control" id="message" rows="2" placeholder="Type your message"></textarea>
                </div>
                <div class="col-md-1">
                    <button id="sendMessage" class="btn btn-info margin-top-send-btn">Send</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('show-scripts')
    <script>
        $('.chat-list').slimScroll({
            start: 'bottom'
        });
        $('#sendMessage').on('click', function () {
            var message = $('#message').val();
            var url = window.location.pathname;
            var id = url.substring(url.lastIndexOf('/') + 1);
            var updateUrl = "{{ route('gym-admin.message.update', ['#id', $messageByUser]) }}";
            var finalUrl = updateUrl.replace('#id', id);
            $.easyAjax({
                type: 'PUT',
                url: finalUrl,
                data: {
                    message: message
                },
                success: function (response) {
                    var str = '<li class="odd">' +
                        '<div class="chat-body">' +
                        '<div class="chat-image"> <img src=""> </div>' +
                        '<div class="chat-text">' +
                        '<h4>{{ $user->first_name.' '.$user->last_name }}</h4>' +
                        '<p>' + response.message + '</p>' +
                        '<b>few seconds ago</b> </div>' +
                        '</div>' +
                        '</li>';
                    $(".chat-list").append(str);
                    $('#message').val('');
                    $('.chat-list').stop().animate({
                        scrollTop: $(".chat-list")[0].scrollHeight
                    }, 800);
                }
            });
        });
    </script>
@endpush
