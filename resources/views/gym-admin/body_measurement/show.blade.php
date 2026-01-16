@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/datepicker.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') !!}
    {!! HTML::style('admin/global/plugins/ladda/ladda-themeless.min.css') !!}
@stop

@section('content')
    <div class="container-fluid">

        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{route('gym-admin.measurements.index')}}">Body Measurement</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>View</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="m-heading-1 border-green m-bordered">
                                <h3>Note</h3>
                                <p>Here you can view and edit current body measurement and previous four measurement histories.</p>
                            </div>
                        </div>
                    </div>
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-layers font-red"></i>
                                <span
                                    class="caption-subject font-red bold uppercase"> Body Measurement History of {{ $current->client->fullName }}</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li class="active">
                                            <a href="#current" data-toggle="tab" class="step active">
                                                <span class="number"> 1 </span>
                                                <span class="desc"><i class="fa fa-check"></i> Current </span>
                                            </a>
                                        </li>

                                        @if(count($history) > 0)
                                        @foreach($history as $key=>$data)
                                            <li>
                                                <a href="#nav-{{$data->uuid}}" data-toggle="tab" class="step" role="tab" aria-controls="nav-{{ $data->uuid }}" aria-selected="true">
                                                    <span class="number"> @if($data->first()) {{ $key+2 }} @else {{ $key+1 }} @endif </span>
                                                    <span class="desc"><i class="fa fa-check"></i> {{ $data->entry_date->toFormattedDateString() }} </span>
                                                </a>
                                            </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                                        <div id="current" class="text-center tab-pane fade in active">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="height_feet">Height
                                                            (Feet)</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="height_feet"
                                                                   id="height_feet" value="{{ $current->height_feet }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="height_inches">Height
                                                            (Inches)</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="height_inches"
                                                                   id="height_inches" value="{{ $current->height_inches }}"
                                                                   readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="weight">Weight (KG)</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="weight" id="weight"
                                                                   value="{{ $current->weight }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="fat">Fat</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="fat" id="fat"
                                                                   value="{{ $current->fat }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="fore_arms">Fore Arms</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="fore_arms" id="fore_arms"
                                                                   value="{{ $current->fore_arms }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="neck">Neck</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="neck" id="neck"
                                                                   value="{{ $current->neck }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="shoulder">Shoulder</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="shoulder" id="shoulder"
                                                                   value="{{ $current->shoulder }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="chest">Chest</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="chest" id="chest"
                                                                   value="{{ $current->chest }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="waist">Waist</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="waist" id="waist"
                                                                   value="{{ $current->waist }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="hip">Hip</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="hip" id="hip"
                                                                   value="{{ $current->hip }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="calves">Calves</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="calves" id="calves"
                                                                   value="{{ $current->calves }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-6 control-label" for="arms">Arms</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="arms" id="arms"
                                                                   value="{{ $current->arms }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <a href="{{ route('gym-admin.measurements.edit',$current->uuid) }}" class="btn green">Edit</a>
                                                <a href="#" class="btn btn-danger delete-button" data-measurement-id="{{ $current->uuid }}">Delete</a>
                                            </div>
                                        </div>

                                        @if(count($history) > 0)
                                        @foreach($history as $data)
                                            <div id="nav-{{ $data->uuid }}" role="tabpanel" aria-labelledby="nav-{{ $data->uuid }}-tab" class="text-center tab-pane fade">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="height_feet">Height
                                                                (Feet)</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="height_feet"
                                                                       id="height_feet" value="{{ $data->height_feet }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="height_inches">Height
                                                                (Inches)</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="height_inches"
                                                                       id="height_inches" value="{{ $data->height_inches }}"
                                                                       readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="weight">Weight (KG)</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="weight" id="weight"
                                                                       value="{{ $data->weight }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="fat">Fat</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="fat" id="fat"
                                                                       value="{{ $data->fat }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="fore_arms">Fore Arms</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="fore_arms" id="fore_arms"
                                                                       value="{{ $data->fore_arms }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="neck">Neck</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="neck" id="neck"
                                                                       value="{{ $data->neck }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="shoulder">Shoulder</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="shoulder" id="shoulder"
                                                                       value="{{ $data->shoulder }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="chest">Chest</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="chest" id="chest"
                                                                       value="{{ $data->chest }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="waist">Waist</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="waist" id="waist"
                                                                       value="{{ $data->waist }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="hip">Hip</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="hip" id="hip"
                                                                       value="{{ $data->hip }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="calves">Calves</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="calves" id="calves"
                                                                       value="{{ $data->calves }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-md-line-input">
                                                            <label class="col-md-6 control-label" for="arms">Arms</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="arms" id="arms"
                                                                       value="{{ $data->arms }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input">
                                                    <a href="{{ route('gym-admin.measurements.edit',$data->uuid) }}" class="btn green">Edit</a>
                                                    <a href="#" class="btn btn-danger delete-button" data-measurement-id="{{ $data->uuid }}">Delete</a>
                                                </div>
                                            </div>
                                        @endforeach
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
    <div class="modal fade bs-modal-md in" id="measureModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@stop

@section('footer')
    {!! HTML::script('admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
    {!! HTML::style('admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! HTML::script('admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/spin.min.js') !!}
    {!! HTML::script('admin/global/plugins/ladda/ladda.min.js') !!}
    {!! HTML::script('admin/pages/scripts/ui-buttons.min.js') !!}
    <script>
        $('.delete-button').on('click', function () {
            var uid = $(this).data('measurement-id');
            var url_modal = "{{route('gym-admin.measurements.modal',[':uuid'])}}";
            var url = url_modal.replace(':uuid',uid);
            $('#modelHeading').html('Remove Measurement');
            $.ajaxModal("#measureModal", url);
        });

        $('#measureModal').on('click', '#remove', function(){
            var lockerId = $(this).data('measurement-id');
            var url = "{{route('gym-admin.measurements.destroy',[':id'])}}";
            url = url.replace(':id',lockerId);
            $.easyAjax({
                url: url,
                container:'.modal-body',
                data: { '_token': '{{ csrf_token() }}' },
                type: "DELETE",
                success: function (response) {
                    if(response.status == 'success'){
                        $('#measureModal').modal('hide');
                        location.reload()
                    }
                }
            });
        });
    </script>
@stop
