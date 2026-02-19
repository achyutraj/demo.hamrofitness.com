
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('Locker Category') }}</th>
            <th>{{ __('Locker Number') }}</th>
            <th>{{ __('Monthly Price') }}</th>
            <th>{{ __('Quarterly Price') }}</th>
            <th>{{ __('Half-Year Price') }}</th>
            <th>{{ __('Yearly Price') }}</th>
            <th>{{ __('Status') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($lockers as $key =>$locker)
            <tr>
                <td>{{ ucfirst($locker->lockerCategory->name) }}</td>
                <td>{{$locker->locker_num ?? ''}}</td>
                <td>NPR {{$locker->lockerCategory->price ?? 0}}</td>
                <td>NPR {{$locker->lockerCategory->three_month_price ?? 0}}</td>
                <td>NPR {{$locker->lockerCategory->six_month_price ?? 0}}</td>
                <td>NPR {{$locker->lockerCategory->one_year_price ?? 0}}</td>
                <td>{{$locker->status}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
