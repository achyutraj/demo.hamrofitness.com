

<div class="table-responsive">
    @if($type == 'expense')
        <table class="table table-bordered table-striped" id="users">
            <thead>
            <tr>
                <th>{{ __('Item Name') }}</th>
                <th>{{ __('Purchase At') }}</th>
                <th>{{ __('Supplier') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Payment Status') }}</th>
                <th>{{ __('Payment Source') }}</th>
                <th>{{ __('Remarks') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $key =>$s)
                <tr>
                    <td>{{ ucfirst( $s->category->title ?? $s->item_name) }}</td>
                    <td>{{ date('Y-m-d',strtotime($s->purchase_date)) }}</td>
                    <td>{{ $s->supplier->name }}</td>
                    <td>NPR {{ $s->price }}</td>
                    <td>{{ ucfirst($s->payment_status )}}</td>
                    <td>
                        {{getPaymentTypeForReport($s->payment_source)}}
                    </td>
                    <td>{{ $s->remarks }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @elseif($type == 'income')
        <table class="table table-bordered table-striped" id="users">
            <thead>
            <tr>
                <th>{{ __('Item Category') }}</th>
                <th>{{ __('Purchase At') }}</th>
                <th>{{ __('Paid By') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Payment Source') }}</th>
                <th>{{ __('Remarks') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $key =>$s)
                <tr>
                    <td>{{ ucfirst( $s->category->title ?? null) }}</td>
                    <td>{{ date('Y-m-d',strtotime($s->purchase_date)) }}</td>
                    <td>{{ $s->supplier->name }}</td>
                    <td>NPR {{ $s->price }}</td>
                    <td>
                        {{getPaymentTypeForReport($s->payment_source)}}
                    </td>
                    <td>{{ $s->remarks }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @elseif($type == 'payroll')
        <table class="table table-bordered table-striped" id="users">
            <thead>
            <tr>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Employ Name') }}</th>
                <th>{{ __('Salary') }}</th>
                <th>{{ __('Allowance') }}</th>
                <th>{{ __('Deduction') }}</th>
                <th>{{ __('Net Pay') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $d)
                <tr>
                    <td>{{ $d->created_at->format('Y-m-d') }}</td>
                    <td>{{$d->employes->fullName}}</td>
                    <td>NPR {{$d->salary}}</td>
                    <td>NPR {{$d->allowance}}</td>
                    <td>NPR {{$d->deduction}}</td>
                    <td>NPR {{$d->total}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <table class="table table-bordered table-striped" id="users">
            <thead>
            <tr>
                <th>{{ __('First Name') }}</th>
                <th>{{ __('Middle Name') }}</th>
                <th>{{ __('Last Name') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Source') }}</th>
                <th>{{ __('Payment Date') }}</th>
                <th>{{ __('Payment Id') }}</th>
                @if($type =='membership')
                    <th>{{ __('Payment For') }}</th>
                @endif
                @if($type =='product')
                    <th>{{ __('Product') }}</th>
                @endif
                @if($type =='locker')
                    <th>{{ __('Locker') }}</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($data as $key =>$s)
                <tr>
                    <td>{{ ucfirst( $s->first_name) }}</td>
                    <td>{{ ucfirst( $s->middle_name) }}</td>
                    <td>{{ ucfirst( $s->last_name) }}</td>
                    <td>NPR {{ $s->payment_amount }}</td>
                    <td>
                        {{getPaymentTypeForReport($s->payment_source)}}
                    </td>
                    <td>{{ $s->payment_date->format('Y-m-d') }}</td>
                    <td>{{ $s->payment_id }}</td>
                    @if($type =='membership')
                        <td>{{ $s->title }}</td>
                    @endif
                    @if($type =='locker')
                        <td>{{ $s->locker_num }}</td>
                    @endif
                    @if($type =='product')
                        @php
                            $arr['product_name'] = json_decode($s->product_name,true);
                            $j= count($arr['product_name']);
                        @endphp
                        <td>
                            @for($i=0;$i<$j;$i++)
                                    <?php
                                    $product_name = App\Models\Product::find($arr['product_name'][$i]);
                                    ?>
                                {{ ucfirst($product_name->name) }} <br>
                            @endfor
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
