@extends('layouts.customer-app.basic')

@section('title')
    HamroFitness | Customer Feedback
@endsection

@section('CSS')
    {!! HTML::style('fitsigma_customer/bower_components/datatables/jquery.dataTables.min.css') !!}
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
                @if(session()->has('message'))
                    <div class="alert alert-message alert-success">
                        {{session()->get('message')}}
                    </div>
                @endif

                <div class="portal-title">
                    <div class="pull-left">
                    <h3 class="box-title m-b-0"><i class="fa fa-feed"></i> Feedback</h3>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-sm btn-success waves-effect" href="#" data-toggle="modal" data-target="#addReviewModal"><i class="zmdi zmdi-plus zmdi-hc-fw fa-fw"></i>Add Review</a>
                    </div>
                    <p class="text-muted m-b-30"></p>
                </div>

                <div class="table-responsive">
                    <table id="paymentTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Review</th>
                            <th>Total Comment</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>{{ $review->review_text }}</td>
                                    <td>{{ $review->comments_count }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="{{ route('customer-app.feedback.show',$review->uuid) }}">View <i class="fa fa-eye"></i></a>

                                        <a class="btn btn-sm btn-primary" data-toggle="modal"
                                           data-target="#editReviewModal-{{$review->id}}">Edit <i class="fa fa-edit"></i></a>

                                        <a class="btn btn-sm btn-danger delete-button" data-review-id="{{ $review->uuid }}">Delete
                                            <i class="fa fa-trash"></i></a>

                                        <div class="modal fade bs-modal-md in" id="editReviewModal-{{$review->id}}" role="dialog" aria-labelledby="editReviewModal-{{$review->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-md" id="modal-data-application">
                                                <form action="{{route('customer-app.feedback.update', $review->uuid)}}"
                                                      method="post">
                                                    {{csrf_field()}} {{ method_field('PUT') }}
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"><b>Edit Review</b></span>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <textarea name="review_text" id="review_text" cols="70" rows="10">{{ $review->review_text }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-success btn-sm" type="submit">Update</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                                </form>
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-modal-md in" id="addReviewModal" role="dialog" aria-labelledby="addReviewModal" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            {!! Form::open(['id'=>'storePayments','class'=>'ajax-form form-horizontal','method'=>'POST']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"><b>Add Review</b></span>
                </div>
                <div class="modal-body">
                   <div class="form-group">
                       <textarea name="review_text" id="review_text" cols="70" rows="10"  required placeholder="Please Enter Your Review to Fitness"></textarea>
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    <button class="btn btn-success btn-sm" id="add-review">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
            {!! Form::close() !!}
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('JS')
    {!! HTML::script('fitsigma_customer/bower_components/datatables/jquery.dataTables.min.js') !!}
    <script>
        $('#add-review').click(function(){
            var url = "{{route('customer-app.feedback.store')}}";
            $.easyAjax({
                url: url,
                container:'#storePayments',
                type: "POST",
                data:$('#storePayments').serialize(),
                formReset:true,
                success:function(response){
                    if(response.status == 'success'){
                        $('#addReviewModal').modal('hide');
                        load_dataTable();
                    }
                }
            })
        });

        var UIBootbox = function () {
            var o = function () {
                $(".delete-button").click(function () {
                    var memID = $(this).data('review-id');

                    bootbox.confirm({
                        message: "Do you want to delete this review?",
                        buttons: {
                            confirm: {
                                label: "Yes",
                                className: "btn-primary"
                            }
                        },
                        callback: function(result){
                            if(result){

                                var url = "{{route('customer-app.feedback.destroy',':id')}}";
                                url = url.replace(':id',memID);
                                $.easyAjax({
                                    url: url,
                                    type: "DELETE",
                                    data: {memID: memID,_token: '{{ csrf_token() }}'},
                                    success: function(){
                                        $('#review-'+memID).fadeOut();
                                    }
                                });
                            }
                            else {
                                console.log('cancel');
                            }
                        }
                    })

                })
            };
            return {
                init: function () {
                    o()
                }
            }
        }();
        jQuery(document).ready(function () {
            UIBootbox.init()
        });
    </script>
@endsection
