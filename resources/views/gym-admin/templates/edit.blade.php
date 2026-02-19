@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/ladda/ladda-themeless.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
@stop

@section('content')
    <div class="container-fluid">
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('gym-admin.dashboard.index') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('gym-admin.templates.index') }}">SMS Templates</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Edit</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="row">
                <div class="col-md-7 col-xs-12">

                    <div class="portlet light portlet-fit">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus font-red"></i><span class="caption-subject font-red bold uppercase">Edit Templates</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- BEGIN FORM-->
                            {{ html()->form->open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}

                            <div class="form-body">
                                <div class="form-group form-md-line-input ">
                                    <input type="text" class="form-control" name="name"
                                           value="{{ $template->name }}"
                                           id="name">
                                    <label for="form_control_1">Template Name <span class="required" aria-required="true"> * </span></label>
                                    <div class="form-control-focus"></div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <select class="form-control edited" id="tags" name="tags" required>
                                        <option value="" >Select Tags</option>
                                        @foreach($tags as $tag)
                                            <option value="{{$tag}}">{{ ucfirst(str_replace('_',' ',$tag)) }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tags">Tags</label>
                                </div>

                                <div class="form-group form-md-line-input ">
                                             <textarea name="message" class="form-control"
                                                       id="message"
                                                       cols="30" rows="6">{{ $template->message }}</textarea>
                                    <span class="" id="remaining">160 Character remaining</span>
                                    <span class="" id="messages">1 Message(s)</span>
                                    <label for="form_control_1">Message</label>
                                    <div class="form-control-focus"></div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <div class="form-group form-md-radios">
                                        <div class="md-radio-inline">
                                            <div class="md-radio">
                                                <input type="radio" id="status-1"
                                                       @if($template->status == 1) checked @endif name="status" value="1" class="md-radiobtn">
                                                <label for="status-1">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Active </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="status-2" @if($template->status == 0) checked @endif name="status"
                                                       class="md-radiobtn" value="0">
                                                <label for="status-2">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions" style="margin-top: 70px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn dark mt-ladda-btn ladda-button"
                                                data-style="zoom-in" id="save-form">
                                            <span class="ladda-label"><i class="fa fa-save"></i> SAVE</span>
                                        </button>
                                        <button type="reset" class="btn default">Reset</button>
                                    </div>
                                </div>
                            </div>
                            {{ html()->form->close() !!}
                            <!-- END FORM-->
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
@stop

@section('footer')
    <script src="{{ asset("admin/global/plugins/ladda/spin.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/ladda/ladda.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/ui-buttons.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/global/scripts/sms-counter.js") }}"></script>

    <script>
        $(document).ready(function(){
            let get_msg = $('#message'),
                remaining = $('#remaining'),
                messages = remaining.next(),
                merge_state = $('#tags');

            merge_state.on('change',function(){
                const ch_pos = get_msg[0].selectionStart;
                const textAreaVal = get_msg.val();
                let addTag = this.value;
                if(addTag){
                    addTag = '{'+addTag+'}';
                }
                get_msg.val(textAreaVal.substring(0,ch_pos) + addTag + textAreaVal.substring(ch_pos));
            });

            function get_character(){
                if(get_msg[0].value !== null){
                    let data = SmsCounter.count(get_msg[0].value, true);
                    remaining.text(data.remaining + "characters remaining");
                    messages.text(data.messages + "Message" + '(s)');
                }
            }
            get_msg.keyup(get_character);
        })

        $('#save-form').click(function () {
            $.easyAjax({
                url: "{{route('gym-admin.templates.update',$template->uuid)}}",
                container: '#storePayments',
                type: "PUT",
                data: $('#storePayments').serialize(),
                formReset: true,
                success: function (response) {
                    //
                }
            })
        });

    </script>

@stop
