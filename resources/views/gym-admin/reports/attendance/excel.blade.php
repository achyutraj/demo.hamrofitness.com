
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th class="all">First Name</th>
            <th class="all">Middle Name</th>
            <th class="all">Last Name</th>
            <th class="min-tablet"> Email</th>
            <th class="min-tablet"> Mobile</th>
            <th class="min-tablet"> Gender</th>
            <th class="min-tablet"> Check In</th>
            <th class="min-tablet"> Check Out</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $key =>$d)
            <tr>
                <td>{{ ucfirst( $d->first_name) }}</td>
                <td>{{ ucfirst( $d->middle_name) }}</td>
                <td>{{ ucfirst( $d->last_name) }}</td>
                <th> {{$d->email}} </th>
                <th> {{$d->mobile}} </th>
                <th> {{$d->gender}} </th>
                <td> {{ date('M d , Y  H:i:s a', strtotime($d->check_in))}} </td>
                <td> @if($d->check_out != null){{ date('M d , Y  H:i:s a', strtotime($d->check_out))}} @endif</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
