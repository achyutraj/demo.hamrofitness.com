<script>
    function forImage(task) {

        $('#task').val($(task).attr('rel'));
        $('#image').val('');
        if ($('#task').val() == "upload") {
            $("#deleteProfileImage").hide();
        } else {
            $("#deleteProfileImage").removeAttr('style');
        }
        $('#uploadImage').modal('show');
    }

    function readImageURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#choose > img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
        $('#cropImage').modal('show');
        $('#uploadImage').modal('hide');
    }

    $(document).ready(function () {
        $('#cropImage').on('shown.bs.modal', function () {
            $('#choose > img').cropper({
                autoCropArea: 0.8,
                viewMode: 2,
                aspectRatio: 4 / 3,
                dragMode: 'move',
                guides: true,
                highlight: true,
                dragCrop: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                mouseWheelZoom: true,
                touchDragZoom: false,
                rotatable: false,
                checkOrientation: false,
                crop: function (e) {
                    var imageDataCrops = $(this).cropper('getImageData');
                    $('#xCoordOne').val(e.x);
                    $('#yCoordOne').val(e.y);
                    $('#profileImageWidth').val(e.width);
                    $('#profileImageHeight').val(e.height);
                },
                cropmove: function (e) {
                    var cropBoxData = $(this).cropper('getCropBoxData');
                    var cropBoxWidth = cropBoxData.width;
                    var cropBoxHeight = cropBoxData.height;

                    if (cropBoxWidth < 208) {
                        $(this).cropper('setCropBoxData', {
                            width: 200
                        });
                    }
                    if (cropBoxHeight < 208) {
                        $(this).cropper('setCropBoxData', {
                            height: 200
                        });
                    }
                }
            });
        }).on('hidden.bs.modal', function () {
            advertCropBoxData = $('#choose > img').cropper('getCropBoxData');
            advertCanvasData = $('#choose > img').cropper('getCanvasData');
            $('#choose > img').cropper('destroy');
        });

        $("#advertImageCropButton").click(function () {
            uploadImage();
            $('#cropImage').modal('hide');
        });

    });

    function uploadImage() {

        var image = $('#image')[0];
        var xCoordinate = $('#xCoordOne').val();
        var yCoordinate = $('#yCoordOne').val();
        var profileImageWidth = $('#profileImageWidth').val();
        var profileImageHeight = $('#profileImageHeight').val();
        var formData = new FormData();
        formData.append('xCoordOne', xCoordinate);
        formData.append('yCoordOne', yCoordinate);
        formData.append('profileImageWidth', profileImageWidth);
        formData.append('profileImageHeight', profileImageHeight);
        formData.append('file', image.files[0]);
        $.ajax({
            type: 'post',
            url: "{{ route('gym-admin.mobile-app.uploadimage') }}",
            cache: false,
            processData: false,
            contentType: false,
            data: formData,
            success: function (response) {
                var obj = jQuery.parseJSON(response);
                $(".profile-img-container_before").hide();
                $('.profile-img-container').removeAttr('style');
                $(".profile-img-container").wrap("<div class='imageDelete'></div>");
                $('#uploadImage').modal('hide');
                $('#changeProfile').attr('src', "{{ $profileHeaderPath }}" + obj.image);
                var data = '<div class="profile-big-container"> <img src="{{ $profileHeaderPath }}' + obj.image + '" class="profile-img-big"><span rel="change" class="change-photo" onclick="forImage(this)">Change Photo</span></div>';
                $('.changeAfterProfile').attr('src', "{{ $profilePath }}" + obj.image);
                $('.img-change').attr('src', "{{ $profileHeaderPath }}" + obj.image);
                profile = '<img src="{{ $profilePath }}' + obj.image + '">';
                $('.popover ').hide();
            },
            error: function (error) {
                var message = JSON.parse(error.responseText);
                $('#error-msg').show();
                $('#error-msg').text(message.errors.file[0]);
            }
        });
    }
</script>
