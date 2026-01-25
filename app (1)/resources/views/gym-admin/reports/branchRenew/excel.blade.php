<table>
    <thead>
        <tr>
            <th>Branch Name</th>
            <th>Owner Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Start Date</th>
            <th>Has Device</th>
            <th>Package Offered</th>
            <th>Package Amount</th>
            <th>Renew Created Date</th>
            <th>Renew Start Date</th>
            <th>Renew End Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($branches as $branch)
            <tr>
                <td>{{ $branch->title }}</td>
                <td>{{ $branch->owner_incharge_name }}</td>
                <td>{{ $branch->email }}</td>
                <td>{{ $branch->phone }}</td>
                <td>{{ $branch->address }}</td>
                <td>{{date('Y-m-d',strtotime($branch->start_date))}}</td>
                <td>{{ $branch->has_device ? 'Yes' : 'No' }}</td>
                <td>{{ $branch->package_offered }} Months</td>
                <td>{{ $branch->package_amount }}</td>
                <td>{{ $branch->created_at->format('Y-m-d') }}</td>
                <td>{{ $branch->renew_start_date->format('Y-m-d') }}</td>
                <td>{{ $branch->renew_end_date->format('Y-m-d') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
