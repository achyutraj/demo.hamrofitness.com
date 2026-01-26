@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Esewa Payments
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
                            <form action="https://uat.esewa.com.np/epay/main" method="POST">
                                <input value="{{ $data['tAmt'] }}" name="tAmt" type="hidden">
                                <input value="{{ $data['amt'] }}" name="amt" type="hidden">
                                <input value="0" name="txAmt" type="hidden">
                                <input value="0" name="psc" type="hidden">
                                <input value="0" name="pdc" type="hidden">
                                <input value="{{ $data['scd'] }}" name="scd" type="hidden">
                                <input value="{{ $data['pid'] }}" name="pid" type="hidden">
                                <input value="{{ $data['su'] }}" type="hidden" name="su">
                                <input value="{{ $data['fu'] }}" type="hidden" name="fu">
                                <div class="form-group text-center">
                                    <button class="btn btn-success waves-effect button-space" type="submit">Pay With Esewa</button>
                                    @if($data['type'] == "product")
                                        <a href="{{ route('customer-app.product-payments.create',$data['purchaseId']) }}" class="btn btn-default waves-effect">Back</a>
                                    @elseif($data['type'] == "locker")
                                        <a href="{{ route('customer-app.locker-payments.create',$data['purchaseId']) }}" class="btn btn-default waves-effect">Back</a>
                                    @else
                                        <a href="{{ route('customer-app.payments.create',$data['purchaseId']) }}" class="btn btn-default waves-effect">Back</a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


