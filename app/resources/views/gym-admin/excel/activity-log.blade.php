<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Log Name</th>
            <th>Event</th>
            <th>Performed By</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
    @foreach($activities as $activity)
        <tr>
            <td>{{ $activity->created_at->format('d-M-Y h:i A') }}</td>
            <td>{{ $activity->log_name }}</td>
            <td>{{ $activity->event }}</td>
            <td>{{ optional($activity->causer)->username ?? 'System' }}</td>
            <td>{{ $activity->description }}</td>
        </tr>
    @endforeach
    </tbody>
</table> 