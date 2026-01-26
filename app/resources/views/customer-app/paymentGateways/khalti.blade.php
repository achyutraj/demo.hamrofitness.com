@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Khalti Payments
@endsection

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Add Payment</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li class="active">Add Payment</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box p-l-20 p-r-20">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-body">
                            <input value="{{ $data['amt'] }}" name="amount" id="amount" type="hidden">
                            <input value="{{ $data['pid'] }}" name="pid" id="pid" type="hidden">

                            <div class="form-group text-center">
                                <button class="btn btn-primary waves-effect button-space" id="payment-btn">Pay With Khalti</button>
                               @if($data['type'] == "product")
                                    <a href="{{ route('customer-app.product-payments.create',$data['pid']) }}" class="btn btn-default waves-effect">Back</a>
                                @elseif($data['type'] == "locker")
                                    <a href="{{ route('customer-app.locker-payments.create',$data['pid']) }}" class="btn btn-default waves-effect">Back</a>
                                @else
                                    <a href="{{ route('customer-app.payments.create',$data['pid']) }}" class="btn btn-default waves-effect">Back</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('JS')
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
<script>
    var config = {
        // replace the publicKey with yours
        "publicKey": "{{ $data['public'] }}",
        "productIdentity": "{{ $data['pid'] }}",
        "productName": "{{ $data['pname'] }}",
        "productUrl": "{{ $data['purl'] }}",
        "paymentPreference": [
            "KHALTI", "EBANKING", "MOBILE_BANKING", "CONNECT_IPS", "SCT",
        ],
        "eventHandler": {
            onSuccess (payload) {
                $.ajax({
                    type: 'POST',
                    @if($data['type'] == "product")
                    url : "{{ route('customer-app.product-payments.success',$data['pid']) }}",
                    @elseif($data['type'] == "locker")
                    url : "{{ route('customer-app.locker-payments.success',$data['pid']) }}",
                    @else
                    url: '{{ route('customer-app.payments.success',$data['pid']) }}',
                    @endif
                    data:{
                        "_token": "{{csrf_token()}}",
                        token: payload.token,
                        amount: payload.amount,
                    },
                    success: function (res){
                        $.ajax({
                            type : "POST",
                            @if($data['type'] == "product")
                                url : "{{ route('customer-app.product-payments.store') }}",
                            @elseif($data['type'] == "locker")
                                url : "{{ route('customer-app.locker-payments.store') }}",
                            @else
                                url : "{{ route('customer-app.payments.store') }}",
                            @endif
                            data : {
                                response : res,
                                "amount" : $('#amount').val(),
                                "pid" : $('#pid').val(),
                                "_token" : "{{ csrf_token() }}"
                            }, success: function(res){
                                window.location =  res.redirect_url;
                                $('.text-muted').text(res.message);
                            }
                        });
                    }
                });
            },
            onError (error) {
                console.log(error);
            },
            onClose () {
                console.log('widget is closing');
            }
        }
    };

    var checkout = new KhaltiCheckout(config);
    var btn = document.getElementById("payment-btn");
    var totalAmt = {{ $data['amt'] }} * 100;
    btn.onclick = function () {
        // minimum transaction amount must be 10, i.e 1000 in paisa.
        checkout.show({amount: totalAmt});
    }
</script>
@endsection

