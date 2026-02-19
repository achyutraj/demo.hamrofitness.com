@extends('layouts.gym-merchant.gymbasic')

@section('CSS')
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-select/css/bootstrap-select.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/dropzone/dropzone.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/dropzone/basic.min.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-datepicker/css/datepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("admin/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css") }}">
@stop

@section('content')
    <div class="container-fluid"      >
        <!-- BEGIN PAGE BREADCRUMBS -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{route('gym-admin.dashboard.index')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>My Gym</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMBS -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-bubble font-green-sharp"></i>
                                <span class="caption-subject font-green-sharp bold uppercase">My Gym</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <ul class="nav nav-pills">
                                <li class="active">
                                    <a href="#tab_2_1" data-toggle="tab"> Details </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active in" id="tab_2_1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light ">
                                                <div class="portlet-title">
                                                    <div class="caption font-dark">
                                                        <i class="icon-badge font-red"></i>
                                                        <span class="caption-subject font-red bold uppercase"> Details</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row">
                                                        {{ html()->form->open(['id'=>'myGymDetails','method'=>'post','class' => 'ajax_form']) !!}
                                                        <div class="col-md-6">
                                                            <div class="form-body">
                                                                <div class="form-group form-md-line-input">
                                                                    <input type="text" class="form-control" id="title" name="title" value="{{$common_details->title}}" >
                                                                    <label for="form_control_1">Gym Title</label>
                                                                    <span class="help-block">Please enter gym title.</span>
                                                                </div>

                                                                <div class="form-group form-md-line-input ">
                                                                    <input type="text" class="form-control" id="email" name="email" value="{{$common_details->email}}"  >
                                                                    <label for="form_control_1">Email</label>
                                                                    <span class="help-block">Please enter your email.</span>
                                                                </div>

                                                                <div class="form-group form-md-line-input ">
                                                                    <input type="text" class="form-control" id="owner_incharge_name" name="owner_incharge_name" value="{{$common_details->owner_incharge_name}}" >
                                                                    <label for="form_control_1">Owner/In-Charge</label>
                                                                    <span class="help-block">Please enter owner/In-charge.</span>
                                                                </div>

                                                                <div class="form-group form-md-line-input">
                                                                    <input type="text" class="form-control" id="owner_incharge_name2" name="owner_incharge_name2" value="{{$common_details->owner_incharge_name2}}" >
                                                                    <label for="form_control_1">Owner/In-Charge#2</label>
                                                                    <span class="help-block">Please enter other owner/In-charge.</span>
                                                                </div>

                                                                <div class="form-group form-md-line-input ">
                                                                    <textarea class="form-control" rows="3" name="address" id="address">{{$common_details->address}}</textarea>
                                                                    <label for="form_control_1">Address</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-body">
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control edited" id="longitude" name="longitude"  value="{{$common_details->longitude}}">
                                                                    <label for="form_control_1">Longitude</label>
                                                                    <span class="help-block"></span>
                                                                </div>

                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control edited" id="latitude" name="latitude"  value="{{$common_details->latitude}}">
                                                                    <label for="form_control_1">Latitude</label>
                                                                    <span class="help-block"></span>
                                                                </div>

                                                                <div class=" form-group form-md-line-input ">
                                                                    <input type="text" class="form-control" id="website" name="website" value="{{$common_details->website}}">
                                                                    <label for="form_control_1">Website</label>
                                                                    <span class="help-block">Please enter website.</span>
                                                                </div>

                                                                <div class="form-group form-md-line-input ">
                                                                    <input type="tel" class="form-control" id="phone" name="phone"  value="{{$common_details->phone}}">
                                                                    <label for="form_control_1">Office Phone</label>
                                                                    <span class="help-block">Please enter phone number.</span>
                                                                </div>

                                                                <div class="form-group form-md-line-input ">
                                                                    <input type="tel" class="form-control" id="phone2" name="phone2" value="{{$common_details->phone2}}" >
                                                                    <label for="form_control_1">Owner Phone #2</label>
                                                                    <span class="help-block">Please enter phone number.</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{ Form::hidden('updateType','details') }}
                                                        {{ html()->form->close() !!}
                                                    </div>
                                                    <div class="row">
                                                        <div class=" col-md-offset-5 col-md-2">
                                                            <button type="button" class="btn btn-primary" id="updateDetails">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
@stop

@section('footer')
    <script src="{{ asset("admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/components-bootstrap-select.min.js") }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=@if(!is_null($gymSettings->maps_api_key) && $gymSettings->maps_api_key != '') {{ $gymSettings->maps_api_key }} @endif&libraries=places"></script>
    {{--<script src="{{ asset("admin/global/plugins/gmaps/gmaps.min.js") }}"></script>--}}
    <script src="{{ asset("admin/global/plugins/dropzone/dropzone.min.js") }}"></script>
    <script src="{{ asset("admin/pages/scripts/form-dropzone.min.js") }}"></script>
    <script src="{{ asset("admin/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js") }}"></script>
    <script>
        //Get Latitude And Longitude
        var geocoder = new google.maps.Geocoder();

        function geocodePosition(pos)
        {
            geocoder.geocode(
                    {
                        latLng: pos
                    }, function(responses)
                    {
                        if (responses && responses.length > 0) {
                            updateMarkerAddress(responses[0].formatted_address);
                        } else {
                            updateMarkerAddress('Cannot determine address at this location.');
                        }
                    });
        }

        function updateMarkerStatus(str)
        {
            //document.getElementById('markerStatus').innerHTML = str;
        }

        function updateMarkerPosition(latLng)
        {
            $('#latitude').val(latLng.lat());
            $('#longitude').val(latLng.lng());
        }

        function updateMarkerAddress(str)
        {

            //  $('#currentlocation').val(str);

        }

        function initialize()
        {
            //Latitude longitude of default

            var clat = "{{ $common_details->latitude }}";
            var clong = "{{ $common_details->longitude }}";

            clat = parseFloat(clat);
            clong = parseFloat(clong);

            var latLng = new google.maps.LatLng(clat,clong);

            var mapOptions = {
                center: latLng,
                zoom: 16,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(document.getElementById('gmap_geocoding'),
                    mapOptions);

            var input = document.getElementById('gmap_geocoding_address');

            var autocomplete = new google.maps.places.Autocomplete(input);

            //autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();
            marker = new google.maps.Marker({
                map: map,
                position: latLng,
                title: 'ReferSell',
                draggable: true
            });
            updateMarkerPosition(latLng);
            geocodePosition(latLng);

            // Add dragging event listeners.
            google.maps.event.addListener(marker, 'dragstart', function() {
                updateMarkerAddress('Dragging...');
            });

            google.maps.event.addListener(marker, 'drag', function() {
                updateMarkerStatus('Dragging...');
                updateMarkerPosition(marker.getPosition());
            });

            google.maps.event.addListener(marker, 'dragend', function() {

                updateMarkerStatus('Drag ended');
                geocodePosition(marker.getPosition());
            });
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                infowindow.close();
                var place = autocomplete.getPlace();

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(10);  // Why 17? Because it looks good.
                }

                /* var image = new google.maps.MarkerImage(
                 place.icon,
                 new google.maps.Size(71, 71),
                 new google.maps.Point(0, 0),
                 new google.maps.Point(17, 34),
                 new google.maps.Size(35, 35));
                 marker.setIcon(image);*/
                marker.setPosition(place.geometry.location);
                updateMarkerPosition(place.geometry.location);

                var address = '';

            });

            // Sets a listener on a radio button to change the filter type on Places
            // Autocomplete.
            function setupClickListener(id, types) {
                var radioButton = document.getElementById(id);
                google.maps.event.addDomListener(radioButton, 'click', function() {
                    autocomplete.setTypes(types);
                });
            }

        }

    </script>
    <script>


        $('.timepicker-no-seconds').timepicker({
            autoclose: true,
            minuteStep: 5
        });
        $('#updateDetails').click(function(){
            $.easyAjax({
                url: "{{route('gym-admin.my-gym.store')}}",
                type:"Post",
                container:'#myGymDetails',
                data:$('#myGymDetails').serialize()
            })
        });

        $('#updateServices').click(function(){
            $.easyAjax({
                url: "{{route('gym-admin.my-gym.store')}}",
                type:"Post",
                container:'#myGymServices',
                data:$('#myGymServices').serialize()
            })
        });
    </script>
    <script>
        $( ".deletePic" ).click(function() {
            var id = $( this ).attr("rel");
            var url = "{{route('gym-admin.my-admin.remove.image',['#id'])}}";
            url = url.replace('#id',id);
            $.easyAjax({
                url:url,
                success:function (res) {
                    if(res.status == 'success'){
                        $('.pic-'+id).remove();
                    }
                }
            })
        });

        $( ".setMainPic" ).click(function() {
            var id = $( this ).val();
            var url = "{{route('gym-admin.my-admin.set-main.image',['#id'])}}";
            url = url.replace('#id',id);
            $.easyAjax({
                url:url,
                success:function(){

                }
            })
        });
    </script>
    <script>
        $(document).ready(function(){
            initialize();
        });
    </script>

@stop
