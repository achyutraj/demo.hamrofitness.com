<script>
    $('#use-webcam').click(function () {
        Webcam.set({
            width: 640,
            height: 480,
            dest_width: 640,
            dest_height: 480,
            image_format: 'jpeg',
            jpeg_quality: 100,
            flip_horiz: true,
            force_flash: false
        });
        Webcam.attach( '#my_camera' );

        $('#recapture-image').hide();
        $('#my_webcam_result').hide();
        $('#my_camera').show();
        $('#webcam-modal').modal('show');
        $('#capture-image').show();
        $('#save-webcam-image').attr('disabled', 'disabled');

    });

    $('#capture-image').click(function () {
        Webcam.snap( function(data_uri) {
            $('#my_camera').hide();
            document.getElementById('my_webcam_result').innerHTML = '<img src="'+data_uri+'"/>';
        } );
        $('#my_webcam_result').fadeIn();
        $('#capture-image').hide();
        $('#recapture-image').show();
        $('#save-webcam-image').removeAttr('disabled');
    });

    $('#recapture-image').click(function () {
        $('#recapture-image').hide();
        $('#my_camera').show();
        $('#my_webcam_result').hide();
        $('#capture-image').show();
        $('#save-webcam-image').attr('disabled', 'disabled');
    });

    $('#webcam-modal').on('hidden.bs.modal', function () {
        Webcam.reset();
    });

    $('#save-webcam-image').click(function () {
        var data_uri = $('#my_webcam_result img').attr('src');
        Webcam.on( 'uploadProgress', function(progress) {
            // Upload in progress
            // 'progress' will be between 0.0 and 1.0
        } );

        Webcam.on( 'uploadComplete', function(code, res) {
            var obj = jQuery.parseJSON(res);
            $('#webcam-modal').modal('hide');
            $("#img_name").val(res);
            $('#changeProfile').attr('src', "{{ $profileHeaderPath }}" + obj.image);
            $('#changeMainProfile').attr('src', "{{ $profileHeaderPath }}" + obj.image);
        } );

        var uploadUrl = '{{ route("gym-admin.client.save-webcam-image", [ $client->id ]) }}';
        $.easyAjax({
            url : uploadUrl,
            type: 'POST',
            data: {webcam:data_uri},
            success: function(res){
                console.log('Image Save successfully');
            }
        })
    });

</script>
