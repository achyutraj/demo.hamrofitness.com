
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('First Name') }}</th>
            <th>{{ __('Middle Name') }}</th>
            <th>{{ __('Last Name') }}</th>
            <th>{{ __('Birthday') }}</th>
            <th>{{ __('Blood Group') }}</th>
            <th>{{ __('Gender') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Mobile') }}</th>
            <th>{{ __('Emergency Contact') }}</th>
            <th>{{ __('Joining Date') }}</th>
            <th>{{ __('Height') }}</th>
            <th>{{ __('Weight') }}</th>
            <th>{{ __('Marital Status') }}</th>
            <th>{{ __('Address') }}</th>
        </tr>
        </thead>
        <tbody>
            @foreach($customers as $key =>$s)
                <tr>
                    <td>{{ ucfirst( $s->first_name) }}</td>
                    <td>{{ ucfirst( $s->middle_name) }}</td>
                    <td>{{ ucfirst( $s->last_name) }}</td>
                    <td>{{ $s->dob ?? '---' }}</td>
                    <td>{{ strtoupper($s->blood_group) }}</td>
                    <td>{{ $s->gender }}</td>
                    <td>{{ $s->email }}</td>
                    <td>{{ $s->mobile }}</td>
                    <td>{{ $s->emergency_contact }}</td>
                    <td>{{ $s->joining_date }}</td>
                    <td>{{ $s->height_feet. $s->height_inches }}</td>
                    <td>{{ $s->weight }}</td>
                    <td>{{ $s->marital_status }}</td>
                    <td>{{ $s->address }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
