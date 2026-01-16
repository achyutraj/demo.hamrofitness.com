<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                <!-- input-group -->
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
            <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
            </span></div>
                <!-- /input-group -->
            </li>
            <li class="user-pro">
                <a href="javascript:;" class="waves-effect">
                    @if($customerValues->image =='')
                        <img src="{{asset('/fitsigma/images/').'/'.'user.svg'}}" class="img-circle img-change"/>
                    @else
                        <img class="img-circle img-change" src="{{$profilePath.$customerValues->image}}"/>
                    @endif<span class="hide-menu">{{ $customerValues->first_name.' '.$customerValues->middle_name.' '.$customerValues->last_name }}<span
                                class="fa arrow"></span></span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('customer-app.profile.index') }}"><i class="ti-user"></i> My Profile</a></li>
                    <li><a href="{{ route('customer-app.logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
                </ul>
            </li>
            <li class="nav-small-cap m-t-10">--- Main Menu</li>
            <li><a href="{{ route('customer-app.dashboard.index') }}" class="waves-effect {{ $dashboardMenu ?? '' }}"><i
                            class="zmdi zmdi-view-dashboard zmdi-hc-fw fa-fw"></i> <span
                            class="hide-menu"> Dashboard </span></a></li>
            <li><a href="{{ route('customer-app.manage-subscription.index') }}"
                   class="waves-effect {{$subscriptionMenu ?? ''}}"><i class="zmdi zmdi-account zmdi-hc-fw fa-fw"></i>
                    <span class="hide-menu"> Subscriptions </span></a></li>
            <li>
                <a href="javascript:" class="waves-effect {{ $paymentMenu ?? '' }}"><i class="fa fa-money"></i>
                    <span class="hide-menu gap-payments"> Membership <span class="fa arrow"></span></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('customer-app.payments.index') }}" class="{{ $paymentSubMenu ?? '' }}">Payments</a>
                    </li>
                    <li>
                        <a href="{{ route('customer-app.payments.due-payments') }}" class="{{ $duePaymentMenu ?? '' }}">Due
                            Payments</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:" class="waves-effect {{ $productPaymentMenu ?? '' }}">
                    <i class="fa fa-money"></i>
                    <span class="hide-menu gap-payments"> Product <span class="fa arrow"></span></span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('customer-app.product-payments.index') }}"
                           class="{{ $productPaymentSubMenu ?? '' }}">Payments</a>
                    </li>
                    <li><a href="{{ route('customer-app.product-payments.due-payments') }}"
                           class="{{ $productDuePaymentMenu ?? '' }}">Due Payments</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:" class="waves-effect {{ $lockerMenu ?? '' }}">
                    <i class="fa fa-key"></i>
                    <span class="hide-menu gap-payments"> Lockers <span class="fa arrow"></span></span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('customer-app.reservations.index') }}"
                           class="{{ $reservationMenu ?? '' }}">Reservation</a>
                    </li>
                    <li><a href="{{ route('customer-app.locker-payments.index') }}"
                           class="{{ $lockerPaymentMenu ?? '' }}">Payments</a>
                    </li>
                    <li><a href="{{ route('customer-app.locker-payments.dueIndex') }}"
                           class="{{ $duePaymentMenu ?? '' }}">Due Payments</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('customer-app.attendance.index') }}" class="waves-effect {{ $attendanceMenu ?? '' }}"><i
                            class="zmdi zmdi-calendar-check zmdi-hc-fw fa-fw"></i> <span
                            class="hide-menu"> Attendance </span></a></li>
            <li>
                <a href="{{ route('customer-app.message.index') }}" class="waves-effect {{ $messageMenu ?? '' }}"><i
                            class="zmdi zmdi-email zmdi-hc-fw fa-fw"></i> <span class="hide-menu"> Message </span></a>
            </li>
            <li>
                <a href="{{ route('customer-app.feedback.index') }}" class="waves-effect {{ $feedbackMenu ?? '' }}"><i
                        class="fa fa-feed"></i> <span class="hide-menu"> Feedback </span></a>
            </li>

            <li>
                <a href="{{ route('customer-app.measurements.index') }}" class="waves-effect {{ $measurementMenu ?? '' }}"><i
                        class="fa fa-balance-scale"></i> <span class="hide-menu"> Body Measurement </span></a>
            </li>
        </ul>
    </div>
</div>
