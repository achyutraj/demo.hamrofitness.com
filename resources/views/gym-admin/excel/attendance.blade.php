
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('First Name') }}</th>
            <th>{{ __('Middle Name') }}</th>
            <th>{{ __('Last Name') }}</th>
            <th>{{ __('Gender') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Mobile') }}</th>
            <th>{{ __('CheckIn') }}</th>
            <th>{{ __('CheckOut') }}</th>
            <th>{{ __('Status') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($attendance as $key =>$s)
            <tr>
                <td>{{ ucfirst( $s->first_name) }}</td>
                <td>{{ ucfirst( $s->middle_name) }}</td>
                <td>{{ ucfirst( $s->last_name) }}</td>
                <td>{{ $s->gender }}</td>
                <td>{{ $s->email }}</td>
                <td>{{ $s->mobile }}</td>
                <td>{{ $s->check_in ?? '---' }}</td>
                <td>{{ $s->check_out ?? '---' }}</td>
                <td>{{ $s->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
