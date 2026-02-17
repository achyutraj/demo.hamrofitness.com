@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Customer Feedback
@endsection

@section('CSS')

@endsection

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">My Feedback</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>Main Menu</li>
                <li class="active">Feedback</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">

                <div class="portal-title">
                    <div class="pull-left">
                        <h3 class="box-title m-b-0"><i class="fa fa-feed"></i>
                            {{ $review->review_text }}
                        </h3>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="paymentTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Comment</th>
                            <th>Comment By</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($review->comments as $cmt)
                                <tr>
                                    <td>{{ $cmt->comment }}</td>
                                    <td>{{ $cmt->merchant->first_name }}</td>
                                    <td>action</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


@endsection

@section('JS')

@endsection
