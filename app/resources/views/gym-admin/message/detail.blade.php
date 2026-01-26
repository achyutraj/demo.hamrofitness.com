@extends('gym-admin.message.index')
@section('inbox')
    <div class="inbox-header">
        <h1 class="pull-left">Inbox</h1>
    </div>
    <div class="inbox-content">
        <table class="table table-striped table-advance table-hover">
            <tbody>
            @if($messageByUser == 'customer')
                @foreach($messages as $message)
                    <tr @if(isset($unreadMessages) && $unreadMessages > 0 && $message->mark_as == 'unread') class="unread"
                        @endif data-messageid="{{ $message->thread_id }}">
                        <td class="view-message hidden-xs"> {{ $message->client->first_name.' '.$message->client->middle_name.' '.$message->client->last_name }} </td>
                        <td class="view-message "> {{ substr($message->text, 0, 80) }}...</td>
                        <td class="view-message inbox-small-cells">
                            <i class="fa fa-paperclip"></i>
                        </td>
                        <td class="view-message text-right"> {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->diffForHumans() }} </td>
                    </tr>
                @endforeach
            @endif
            @if($messageByUser == 'employee')
                @foreach($messages as $message)
                    <tr @if(isset($unreadMessages) && $unreadMessages > 0 && $message->mark_as == 'unread') class="unread"
                        @endif data-messageid="{{ $message->thread_id }}">
                        <td class="view-message hidden-xs"> {{ $message->employee->fullName }} </td>
                        <td class="view-message "> {{ substr($message->text, 0, 80) }}...</td>
                        <td class="view-message inbox-small-cells">
                            <i class="fa fa-paperclip"></i>
                        </td>
                        <td class="view-message text-right"> {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->diffForHumans() }} </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
@endsection

@push('detail-scripts')
    <script>
        $('tr').on('click', function () {
            var threadId = $(this).data('messageid');
            var url = "{{ route('gym-admin.message.show', [$messageByUser,'#id']) }}";
            url = url.replace('#id', threadId);
            window.location = url;
        });
    </script>
@endpush
