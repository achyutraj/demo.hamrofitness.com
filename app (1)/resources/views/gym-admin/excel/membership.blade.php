
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users">
        <thead>
        <tr>
            <th>{{ __('Title') }}</th>
            <th>{{ __('Price') }}</th>
            <th>{{ __('Duration') }}</th>
            <th>{{ __('Details') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($membership as $key =>$s)
            <tr>
                <td>{{ ucfirst( $s->title) }}</td>
                <td>{{ $s->price }}</td>
                <td>{{ $s->duration }} {{ $s->duration_type }}</td>
                <td>{{ $s->details }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
