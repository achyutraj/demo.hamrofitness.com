
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th class="all"> Client Name </th>
            <th class="min-tablet"> Title </th>
            <th class="min-tablet"> Purchase Amount </th>
            <th class="min-tablet"> Start Date </th>
            <th class="min-tablet"> Expire Date </th>
        </tr>
        </thead>
        <tbody>
        @foreach($booking as $key =>$d)
            <tr>
                <td>{{$d->first_name }} {{ $d->middle_name }} {{ $d->last_name }}  </td>
                <th> {{$d->membership}} </th>
                <th> NPR {{$d->amount_to_be_paid}} </th>
                <td> {{date('M d, Y',strtotime($d->start_date))}} </td>
                <td> {{ ($d->expires_on != null) ? date('M d, Y',strtotime($d->expires_on)) : ''}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
