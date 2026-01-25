<div class="row">
    @foreach($clients as $client)

        <div class="col-xs-12 col-md-3 col-print-4">
            <div class="i-card-container">
                <div class="row">
                    <div class="col-xs-12 qr-code-container">
                        @if ($client->image != '')
                            <img class="img img-thumbnail" height="100" width="100" src="{{ asset('/uploads/profile_pic/master/'.$client->image)}}" alt="" />
                        @else
                            <img class="img img-thumbnail" height="100" width="100" src="{{ asset('fitsigma/images/user.svg')}}" alt="" />
                        @endif
                    </div>
                    <div class="col-xs-12 i-card-contact">
                        <strong style="font-size: 15px" class="text-center">{{ ucwords($common_details->title) }}</strong>
                        <div class="col-xs-8">
                            <p class="i-card-user-detail">
                                <i class="fa fa-user"></i> {{ $client->fullname }} <br>
                                <em>(Member)</em><br>
                                <i class="fa fa-phone"></i> {{ $client->mobile }} <br>
                                <i class="fa fa-envelope-o"></i> {{ $client->email }} <br>
                            </p>
                        </div>
                        <div class="col-xs-4">
                            <?php
                                // Generate encrypted check in url
                                $encryptedParameter = rand(1111111,9999999).$client->id.'-'.rand(1111,9999).\Str::random(19);
                                $encryptedParameter = \Illuminate\Support\Facades\Crypt::encrypt($encryptedParameter);
                                $url = route('gym-admin.gym-qr-check-in', [$encryptedParameter]);
                            ?>
                            <p class="i-card-user-detail">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(60)->generate($url) !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endforeach

</div>
