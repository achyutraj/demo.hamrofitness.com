
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th class="all"> Client </th>
            <th class="all"> Locker </th>
            <th class="min-tablet"> Paid Amount </th>
            <th class="min-tablet"> Start Date </th>
            <th class="min-tablet"> Expire On </th>
        </tr>
        </thead>
        <tbody>
        @foreach($booking as $key =>$d)
            <tr>
                <td>{{$d->client->fullName  ?? ''}} </td>
                <td>{{$d->locker->locker_num ?? '' }} </td>
                <th> NPR {{$d->paid_amount}} </th>
                <th> {{$d->start_date->format('Y-m-d')}} </th>
                <th> {{$d->end_date->format('Y-m-d')}} </th>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
