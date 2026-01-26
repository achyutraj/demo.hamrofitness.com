
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
            <th>{{ __('Goal') }}</th>
            <th>{{ __('Source') }}</th>
            <th>{{ __('Enquiry Date') }}</th>
            <th>{{ __('Next Follow Up') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($enquiry as $key =>$s)
            <tr>
                <td>{{ ucfirst($s->customer_name) }}</td>
                <td>{{ ucfirst($s->customer_mname) }}</td>
                <td>{{ ucfirst($s->customer_lname) }}</td>
                <td>{{ $s->email }}</td>
                <td>{{ $s->mobile }}</td>
                <td>{{ $s->sex }}</td>
                <td>{{ $s->customer_goal }}</td>
                <td>{{ $s->come_to_know}}</td>
                <td>{{ $s->enquiry_date }}</td>
                <td>{{ $s->next_follow_up }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
