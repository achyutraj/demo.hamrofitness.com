
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('First Name') }}</th>
            <th>{{ __('Middle Name') }}</th>
            <th>{{ __('Last Name') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Mobile') }}</th>
            <th>{{ __('Gender') }}</th>
            @if($id=='birthday')
            <th>{{ __('Birthday') }}</th>
            @endif
            @if($id=='expire')
                <th>{{ __('Membership') }}</th>
                <th>{{ __('Start Date') }}</th>
                <th>{{ __('Expires On') }}</th>
            @else
            <th> Joined Date </th>
            <th> Address </th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($clients as $key =>$s)
            <tr>
                <td>{{ ucfirst( $s->first_name) }}</td>
                <td>{{ ucfirst( $s->middle_name) }}</td>
                <td>{{ ucfirst( $s->last_name) }}</td>
                <td>{{ $s->email }}</td>
                <td>{{ $s->mobile }}</td>
                <td>{{ $s->gender }}</td>
                @if($id=='birthday')
                    <td>{{ $s->dob->toFormattedDateString() }}</td>
                @endif
                @if($id=='expire')
                    <td>{{ $s->title }}</td>
                    <td>{{ $s->start_date->toFormattedDateString() }}</td>
                    <td>{{ $s->expires_on->toFormattedDateString() }}</td>
                @else
                    <td>{{$s->joining_date}}</td>
                    <td>{{$s->address}}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
