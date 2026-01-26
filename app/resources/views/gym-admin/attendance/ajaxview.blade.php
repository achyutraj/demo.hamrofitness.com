<div class="row">
    <div class="col-md-12 attendance-row">

        <a href="{{route('gym-admin.client.show',$row->id)}}" class="col-md-2 col-xs-12">
            @if($row->image == '')
                <img class="img-responsive" src="{{asset('/fitsigma/images/').'/'.'user.svg'}}" />
            @else
                <img class="img-responsive" src="{{$imageURL.'/'.$row->image}}"  />
            @endif
        </a>

        <div class="search-content col-md-4 col-xs-12" >
            <h2 >
                <a href="{{route('gym-admin.client.show',$row->id)}}">{{ ucwords($row->first_name.' '.$row->middle_name.' '.$row->last_name) }}</a>
            </h2>
            <p class="search-desc">
                <a href="javascript:;" class="btn green btn-xs"><i class="icon-like"></i> Member Since {{ $row->joining_date->format('d-M-y') }}</a>
            </p>
        </div>

        <div class="col-md-3 col-xs-12 hidden-xs total_checkin_count">
            <div class="widget-thumb wtext-uppercase">
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-green icon-pointer"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{ $row->total_checkin }}">{{ $row->total_checkin }}</span>
                        <span class="widget-thumb-subtitle uppercase"> Check In</span>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-md-3 col-xs-12 text-right checkin_btns" id="check-in-btns-{{ $row->id }}">
            @if($row->status != 'arrived')
                <button href="javascript:;"  data-client-id="{{ $row->id }}" class="mark-check-in btn-block btn btn-lg green-jungle " > <i class="icon-check"></i>
                    Check In
                </button>
            @else
                <?php
                $currentTime = \Carbon\Carbon::now();
                ?>
                @if(is_null($row->check_out) && $currentTime->diffInSeconds($row->check_in) > 300)
                    <button href="javascript:;" data-client-id="{{ $row->id }}" data-row-id="{{ $row->checkin_id }}"
                            class="mark-check-out btn-block btn btn-lg warning "><i class="icon-logout"></i>
                        Check Out
                    </button>
                @endif

                <button  data-delete-id="{{$row->checkin_id}}" data-client-id="{{$row->id}}"   class="btn-block delete-button margin-top-10 btn btn-lg red-flamingo"> <i class="icon-close"></i>
                    Remove
                </button>
            @endif

            @if(!is_null($row->check_in) && !is_null($row->check_out))
                <a href="javascript:;" class="btn btn-sm blue-ebonyclay btn-block"> <i class="icon-pointer"></i>
                    Checked In at {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$row->check_in)->format('d-M h:i A')  }}
                </a>
                <br>
                <a href="javascript:;" class="btn btn-sm blue-ebonyclay btn-block"> <i class="icon-pointer"></i>
                    Checked Out at {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$row->check_out)->format('d-M h:i A')  }}
                </a>
            @endif

        </div>
    </div>

</div>
