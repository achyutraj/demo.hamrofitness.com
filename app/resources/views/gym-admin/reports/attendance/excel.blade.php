
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th class="all">First Name</th>
            <th class="all">Middle Name</th>
            <th class="all">Last Name</th>
            <th class="min-tablet">Email</th>
            <th class="min-tablet">Mobile</th>
            <th class="min-tablet">Gender</th>
            @if(isset($id) && ($id == 'regular_active_client' || $id == 'high_attendance'))
                <th class="min-tablet">Present Days</th>
                <th class="min-tablet">Last Attendance</th>
                <th class="min-tablet">Attendance Status</th>
            @elseif(isset($id) && $id == 'irregular_active_client')
                <th class="min-tablet">Present Days</th>
                <th class="min-tablet">Absent Days</th>
                <th class="min-tablet">Total Sub Days</th>
                <th class="min-tablet">Last Attendance</th>
                <th class="min-tablet">Attendance Status</th>
            @else
                <th class="min-tablet">Check In</th>
                <th class="min-tablet">Check Out</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($data as $key =>$d)
            <tr>
                <td>{{ ucfirst($d->first_name) }}</td>
                <td>{{ ucfirst($d->middle_name) }}</td>
                <td>{{ ucfirst($d->last_name) }}</td>
                <td>{{ $d->email }}</td>
                <td>{{ $d->mobile }}</td>
                <td>{{ ucfirst($d->gender) }}</td>
                @if(isset($id) && ($id == 'regular_active_client' || $id == 'high_attendance'))
                    <td>{{ $d->present_days ?? 0 }}</td>
                    <td>{{ $d->last_attendance ? date('M d, Y', strtotime($d->last_attendance)) : 'N/A' }}</td>
                    <td>
                        @php
                            $presentDays = $d->present_days ?? 0;
                            if ($presentDays >= 20) {
                                echo 'Regular';
                            } elseif ($presentDays >= 10) {
                                echo 'Moderate';
                            } else {
                                echo 'Irregular';
                            }
                        @endphp
                    </td>
                @elseif(isset($id) && $id == 'irregular_active_client')
                    <td>{{ $d->present_days ?? 0 }}</td>
                    <td>{{ $d->absent_days ?? 0 }}</td>
                    <td>{{ $d->total_subscription_days ?? 0 }}</td>
                    <td>{{ $d->last_attendance ? date('M d, Y', strtotime($d->last_attendance)) : 'N/A' }}</td>
                    <td>
                        @php
                            $totalDays = $d->total_subscription_days ?? 1;
                            $presentDays = $d->present_days ?? 0;
                            $rate = ($presentDays / $totalDays) * 100;
                            if ($rate >= 70) {
                                echo 'Regular';
                            } elseif ($rate >= 40) {
                                echo 'Moderate';
                            } else {
                                echo 'Irregular';
                            }
                        @endphp
                    </td>
                @else
                    <td>{{ isset($d->check_in) ? date('M d, Y  H:i:s a', strtotime($d->check_in)) : '' }}</td>
                    <td>{{ isset($d->check_out) && $d->check_out != null ? date('M d, Y  H:i:s a', strtotime($d->check_out)) : '' }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
