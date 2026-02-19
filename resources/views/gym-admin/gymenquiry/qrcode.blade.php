<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Enquiry Form</title>
</head>
<body>

<div class="text-center" style="margin-top: 50px;">
    <?php
    $myData = env('APP_URL').'/enquiry-form/'.$common_details->slug;
    ?>
    <div>
        <img class="center" src="data:image/png;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate($myData)) }} ">
    </div>
   <div> 
</div>

</body>
</html>