<div class="row">
    @foreach($clients as $client)

        <div class="col-xs-12 col-md-6 col-print-4">
            <div class="col-12 i-card-container">
                <div class="row">
                    <div class="col-xs-12 qr-code-container">
                        @if ($client->image != '')
                            <img class="img img-thumbnail" height="100" width="100" src="{{ asset('/uploads/profile_pic/master/'.$client->image)}}" alt="" />
                        @else
                            <img class="img img-thumbnail" height="100" width="100" src="{{ asset('fitsigma/images/user.svg')}}" alt="" />
                        @endif
                    </div>
                    <div class="col-xs-12 i-card-contact">
                        <?php
                        // Generate encrypted check in url
                        $encryptedParameter = rand(1111,9999).$client->id.'-'.rand(111,999).\Str::random(19);
                        $encryptedParameter = \Illuminate\Support\Facades\Crypt::encrypt($encryptedParameter);
                        ?>
                        {{ DNS1D::getBarcodeHTML($client->fullname, 'C39') }}
                    </div>
                </div>
            </div>
        </div>

    @endforeach

</div>
