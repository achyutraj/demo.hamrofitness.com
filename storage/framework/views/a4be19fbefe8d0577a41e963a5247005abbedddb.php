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
                aspectRatio: NaN,
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
        var id = "<?php echo e($client->id); ?>";
        var image = $('#image')[0];
        var xCoordinate = $('#xCoordOne').val();
        var yCoordinate = $('#yCoordOne').val();
        var profileImageWidth = $('#profileImageWidth').val();
        var profileImageHeight = $('#profileImageHeight').val();
        var formData = new FormData();
        formData.append('id', id);
        formData.append('xCoordOne', xCoordinate);
        formData.append('yCoordOne', yCoordinate);
        formData.append('profileImageWidth', profileImageWidth);
        formData.append('profileImageHeight', profileImageHeight);
        formData.append('file', image.files[0]);
        $.ajax({
            type: 'post',
            url: "<?php echo e(route('gym-admin.gymclient.uploadimage')); ?>",
            cache: false,
            processData: false,
            contentType: false,
            data: formData
        }).done(
            function (response) {
                var obj = jQuery.parseJSON(response);
                $('#uploadImage').modal('hide');
                $('#changeProfile').attr('src', "<?php echo e($profileHeaderPath); ?>" + obj.image);
                $('#changeMainProfile').attr('src', "<?php echo e($profileHeaderPath); ?>" + obj.image);
                $('.popover ').hide();
            });
    }
</script>
<?php /**PATH /var/www/stage.hamrofitness.com/public_html/resources/views/gym-admin/gymclients/imageupload.blade.php ENDPATH**/ ?>