<div id="mySidenav" class="sidenav">
    <div class="logo">
        @if(is_null($gymSettings))
            {!! HTML::image(asset('/fitsigma/images/').'/'.'fitness-plus.png', 'Logo',array("class" => "logo-default img-responsive image-change")) !!}
        @else
            @if($gymSettings->image != '')
                {!! HTML::image($gymSettingPath.$gymSettings->image, 'Logo',array("class" => "logo-default img-responsive image-change", "style" => "height:51px")) !!}
            @else
                {!! HTML::image(asset('/fitsigma/images/').'/'.'fitness-plus.png', 'Logo',array("class" => "logo-default img-responsive image-change")) !!}
            @endif
        @endif
    </div>
    <ul class="nav sidebar-nav">
        @if($user->is_admin == 1)
            <li class="{{$superAdminMenu or ''}}">
                <a href="{{route('gym-admin.superadmin.dashboard')}}"><i class=" fa fa-dashboard"></i> Super Admin Dashboard
                </a>
            </li>
        @endif
        @if($user->can("view_dashboard"))
            <li class="{{$dashboardMenu or ''}}">
                <a href="{{route('gym-admin.dashboard.index')}}"><i class=" fa fa-dashboard"></i> Dashboard
                </a>
            </li>
        @endif
        @if($user->can("view_enquiry"))
            <li class="{{$enuriryMenu or ''}} ">
                <a href="{{route('gym-admin.enquiry.index')}}" class="nav-link">
                    <i class=" icon-earphones-alt"></i> Leads
                </a>
            </li>
        @endif
        @if($user->can("view_customers") || $user->can("add_attendance") ||  $user->can("task"))
            <li class="menu-dropdown mega-menu-dropdown {{$manageMenu or ''}} hasChild">
                <a href="javascript:;"><i class=" fa fa-gear"></i> Business <i class="fa fa-angle-down hidden-xs hidden-sm"></i>
                    <span class="arrow"></span>
                </a>
                <ul class="submenu hide">
                    <li>
                        <ul class="mega-menu-submenu ">
                            @if($user->can("view_customers"))
                                <li class="{{$customerMenu or ''}}">
                                    <a href="{{route('gym-admin.client.index')}}" class="nav-link  ">
                                        <i class="icon-users"></i> Customers
                                    </a>
                                </li>
                            @endif
                            <li class="{{$gymMenu or ''}} ">
                                <a href="/gym-admin/client-purchase" class="nav-link  ">
                                    <i class="fa {{$gymSettings->currency->symbol}}"></i> Subscriptions
                                </a>
                            </li>
                            @if($user->can("add_attendance"))
                                <li class="{{$attendanceMenu or ''}} ">
                                    <a href="{{route('gym-admin.attendance.create')}}" class="nav-link  ">
                                        <i class="icon-plus"></i> Mark Attendance
                                    </a>
                                </li>
                            @endif

                            <li class="{{$gymMenu or ''}} ">
                                <a href="/gym-admin/diet-plans" class="nav-link  ">
                                    <i class="fa fa-cutlery"></i> Diet Plan
                                </a>
                            </li>
                            <li class="{{$gymMenu or ''}} ">
                                <a href="/gym-admin/training-plans" class="nav-link">
                                    <i class="fa fa-cube"></i> Training Plan
                                </a>
                            </li>
                            <li class="{{$gymMenu or ''}} ">
                                <a href="/gym-admin/class-schedule" classfgy="nav-link">
                                    <i class="fa fa-clock-o"></i> Class Schedule
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        @endif
        @if($user->can("view_payments") || $user->can("view_due_payments") || $user->can("view_due_payments")
            || $user->can("view_invoice") || $user->can("expense"))
            <li class="menu-dropdown classic-menu-dropdown {{$paymentMenu or ''}} hasChild">
                <a href="javascript:;"><i class=" fa {{ $gymSettings->currency->symbol }}"></i> Accounts <i
                            class="fa fa-angle-down hidden-xs hidden-sm"></i>
                    <span class="arrow"></span>
                </a>
                <ul class="submenu hide">
                    <li>
                        <ul class="mega-menu-submenu">
                            @if($user->can("view_payments"))
                                <li class="{{$showpaymentMenu or ''}}">
                                    <a href="{{ route('gym-admin.membership-payment.index') }}" class="nav-link ">
                                        {{ $gymSettings->currency->acronym }} Payments
                                    </a>
                                </li>
                            @endif
                            @if($user->can("view_due_payments"))
                                <li class="{{$paymentreminderMenu or ''}}">
                                    <a href="{{route('gym-admin.client-purchase.paymentreminder')}}" class="nav-link ">
                                        <i class="fa fa-bullhorn"></i> Due Payments
                                    </a>
                                </li>
                            @endif
                            @if($user->can("view_due_payments"))
                                <li class="{{$paymentreminderHistoryMenu or ''}}">
                                    <a href="{{route('gym-admin.client-purchase.reminder-history')}}" class="nav-link ">
                                        <i class="fa fa-list"></i> Payment Reminder History
                                    </a>
                                </li>
                            @endif
                            @if($user->can("view_invoice"))
                                <li class="{{$invoiceMenu or ''}}">
                                    <a href="{{route('gym-admin.gym-invoice.index')}}" class="nav-link  ">
                                        <i class="fa fa-file"></i> Invoice
                                    </a>
                                </li>
                            @endif
                            @if($user->can("expense"))
                                <li class="{{$expenseMenu or ''}}">
                                    <a href="{{ route('gym-admin.expense.index') }}" class="nav-link">
                                        <i class="fa fa-money"></i> Expenses
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </li>
        @endif
        @if($user->can("view_target_report") || $user->can("view_client_report") || $user->can("view_booking_report")
            || $user->can("view_finance_report") || $user->can("view_attendance_report") || $user->can("view_enquiry_report")
            || $user->can("balance_report"))
            <li class="menu-dropdown mega-menu-dropdown {{$reportMenu or ''}}  hasChild">
                <a href="javascript:;"><i class=" icon-notebook"></i> Reports <i class="fa fa-angle-down hidden-xs hidden-sm"></i>
                    <span class="arrow"></span>
                </a>
                <ul class="submenu hide">
                    <li>
                        <ul class="mega-menu-submenu">

                            <li class="">
                                <a href="{{route('gym-admin.target-report.index')}}" class="nav-link  ">
                                    <i class="fa fa-bullseye"></i> Due Payments
                                </a>
                            </li>
                            @if($user->can("view_target_report"))
                                <li class="{{$targetreportMenu or ''}}">
                                    <a href="{{route('gym-admin.target-report.index')}}" class="nav-link  ">
                                        <i class="fa fa-bullseye"></i> Target Report
                                    </a>
                                </li>
                            @endif
                            @if($user->can("view_client_report"))
                                <li class="{{$clientreportMenu or ''}}">
                                    <a href="{{route('gym-admin.client-report.index')}}" class="nav-link  ">
                                        <i class="icon-users"></i> Clients Report
                                    </a>
                                </li>
                            @endif
                            @if($user->can("view_booking_report"))
                                <li class="{{$bookingreportMenu or ''}}">
                                    <a href="{{route('gym-admin.booking-report.index')}}" class="nav-link  ">
                                        <i class="icon-notebook"></i> Subscription Report
                                    </a>
                                </li>
                            @endif
                            @if($user->can("view_finance_report"))
                                <li class="{{$financialreportMenu or ''}}">
                                    <a href="{{route('gym-admin.finance-report.index')}}" class="nav-link  ">
                                        <i class="fa fa-money"></i> Financial Report
                                    </a>
                                </li>
                            @endif
                            @if($user->can("view_attendance_report"))
                                <li class="{{$attendancereportMenu or ''}}">
                                    <a href="{{route('gym-admin.attendance-report.index')}}" class="nav-link  ">
                                        <i class="fa fa-tasks"></i> Attendance Report
                                    </a>
                                </li>
                            @endif
                            @if($user->can("view_enquiry_report"))
                                <li class="{{$enquiryreportMenu or ''}}">
                                    <a href="{{route('gym-admin.enquiry-report.index')}}" class="nav-link  ">
                                        <i class="fa fa-question-circle"></i> Enquiry Report
                                    </a>
                                </li>
                            @endif
                            @if($user->can("balance_report"))
                                <li class="{{$balancereportMenu or ''}}">
                                    <a href="{{ route('gym-admin.balance-report.index') }}" class="nav-link  ">
                                        <i class="fa fa-balance-scale"></i> Balance Report
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </li>
        @endif
        @if($user->can("view_previous_promotions") || $user->can("message"))
            <li class="menu-dropdown classic-menu-dropdown  {{$promotionMenu or ''}} hasChild">
                <a href="javascript:;"><i class=" icon-paper-plane"></i> Communication <i class="fa fa-angle-down hidden-xs hidden-sm"></i>
                    <span class="arrow"></span>
                </a>
                <ul class="submenu hide">
                    <li>
                        <ul class="mega-menu-submenu">

                            @if($user->can("message"))
                                <li class="{{$messageMenu or ''}} ">
                                    <a href="{{route('gym-admin.message.index')}}" class="nav-link">
                                        <i class="fa fa-comments"></i> Chat
                                    </a>
                                </li>
                            @endif
                            @if($user->can("message"))
                                <li class="{{$messageMenu or ''}} ">
                                    <a href="{{route('gym-admin.sms.index')}}" class="nav-link">
                                        <i class="fa fa-comment"></i> SMS
                                    </a>
                                </li>
                            @endif
                            @if($user->can("message"))
                                <li class="{{$messageMenu or ''}} ">
                                    <a href="{{route('gym-admin.email.index')}}" class="nav-link">
                                        <i class="fa fa-at"></i> Email
                                    </a>
                                </li>
                            @endif
                            @if($user->can("view_previous_promotions"))
                                <li class="{{$promotionEmailMenu or ''}}">
                                    <a href="{{ route('gym-admin.email-promotion.index') }}" class="nav-link ">
                                        <i class="icon-paper-plane"></i> Email Promotion </a>
                                </li>
                            @endif
                            @if($user->can("view_previous_promotions"))
                                <li class="{{$promotionDbMenu or ''}}">
                                    <a href="{{ route('gym-admin.promotion-db.index') }}" class="nav-link ">
                                        <i class="fa fa-database"></i> Promotional Database </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </li>
        @endif

         @if($user->can("view_previous_promotions") && $user->is_admin !== 1)
            <li class="menu-dropdown classic-menu-dropdown  {{$promotionMenu or ''}} hasChild">
                <a href="javascript:;"><i class=" fa fa-building"></i> Office <i class="fa fa-angle-down hidden-xs hidden-sm"></i>
                    <span class="arrow"></span>
                </a>
                <ul class="submenu hide">
                    <li>
                        <ul class="mega-menu-submenu">
                            <li class="">
                                <a href="{{route('gym-admin.users.showEmployee')}}" class="nav-link ">
                                    <i class="icon-user"></i> Employee
                                </a>
                            </li>
                            <li class="">
                                <a href="{{route('gym-admin.employAttendance.create')}}" class="nav-link ">
                                    <i class="fa fa-calendar"></i> Attendance
                                </a>
                            </li>
                            <li class="">
                                <a href="{{route('gym-admin.employ.showLeave')}}" class="nav-link ">
                                    <i class="fa fa-sign-out"></i> Leave
                                </a>
                            </li>
                            <li class="">
                                <a href="{{route('gym-admin.employPayroll.index')}}" class="nav-link ">
                                    <i class="fa fa-money"></i> Payroll/Salary
                                </a>
                            </li>
                            <li class="">
                                <a href="{{route('gym-admin.employTaskList.index')}}" class="nav-link ">
                                    <i class="fa fa-tasks"></i> Tasks
                                </a>
                            </li>
                            @if($user->can("view_targets"))
                                <li class="{{$targetMenu or ''}}">
                                    <a href="{{route('gym-admin.target.index')}}" class="nav-link  ">
                                        <i class="fa fa-bullseye"></i> Targets
                                    </a>
                                </li>
                            @endif
                            <li class="">
                                <a href="{{route('gym-admin.assetManagement.index')}}" class="nav-link ">
                                    <i class="fa fa-dropbox"></i> Asset Management
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        @endif
        {{--
                @if($user->can("view_software_updates"))
                    <li class="menu-dropdown mega-menu-dropdown {{$updatesMenu or ''}}  ">
                        <a href="{{route('gym-admin.upcoming.index')}}">
                            @if(!is_null($gymSwUpdates) &&  (\Carbon\Carbon::now('Asia/Kathmandu')->diffInDays($gymSwUpdates->date, false) >= -3))
                                <i class="font-yellow-crusta fa fa-magic faa-tada animated"></i>
                            @else
                                <i class=" fa fa-magic"></i>
                            @endif
                            S/W Updates
                        </a>
                    </li>
                @endif  --}}
        @if($user->is_admin == 1)
            <li class="menu-dropdown mega-menu-dropdown {{$indexSuperAdmin or ''}}  ">
                <a href="{{ route('gym-admin.superadmin.index') }}">
                    <i class=" fa fa-cogs"></i>
                    Manage Branch
                </a>
            </li>
        @endif
        @if($user->can("view_membership") || $user->can('my_gym'))
            <li class="menu-dropdown mega-menu-dropdown {{$manageMenu or ''}} hasChild">
                <a href="javascript:;"><i class=" fa fa-gear"></i> Settings <i class="fa fa-angle-down hidden-xs hidden-sm"></i>
                    <span class="arrow"></span>
                </a>
                <ul class="submenu hide">
                    <li>
                        <ul class="mega-menu-submenu ">
                            @if($user->can("view_membership"))
                                <li class="{{$membershipMenu or ''}}">
                                    <a href="{{route('gym-admin.membership.index')}}" class="nav-link nav-toggle">
                                        <i class="icon-badge"></i> Membership Plans
                                    </a>
                                </li>
                            @endif
                            @if($user->can("my_gym"))
                                <li class="{{$gymMenu or ''}} ">
                                    <a href="{{route('gym-admin.my-gym.index')}}" class="nav-link  ">
                                        <i class="fa fa-heartbeat"></i> Gym Settings
                                    </a>
                                </li>
                                @if($user->is_admin !== 1)
                                    <li class="{{$gymMenu or ''}}">
                                        <a href="/gym-admin/setting/activities" class="nav-link  ">
                                            <i class="fa fa-level-up"></i> Level and Activities
                                        </a>
                                    </li>
                                    <li class="{{$gymMenu or ''}} ">
                                        <a href="/gym-admin/classesTrainers" class="nav-link  ">
                                            <i class="fa fa-person-booth"></i> Classes & Trainer
                                        </a>
                                    </li>
                                    <li class="{{$gymMenu or ''}} ">
                                        <a href="/gym-admin/employee/leaveType" class="nav-link  ">
                                            <i class="fa fa-person-booth"></i> Leave
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </li>
                </ul>
            </li>
        @endif
    </ul>
</div>


<style>
    body {
        font-family: "Lato", sans-serif;
        transition: background-color .5s;
    }

    .sidenav > ul > li {
        position: relative;
        display: inline-block;
        padding: 10px;
    }

    .sidenav {
        font-family: "Open Sans", sans-serif;
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #fff;
        overflow-x: hidden;
        transition: 0.5s;
        border-right: 1px solid #ccc;
    }

    .sidenav > ul > li {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 15px;
        color: #818181;
        display: block;
        transition: 0.3s;
        padding: 0;
    }

    .sidenav > ul > li > a:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .sidenav .logo {
        padding: 10px 0;
        display: inline-block;
        border-bottom: 1px solid #ccc
    }

    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    #main {
        transition: margin-left .5s;
        padding: 16px;
    }

    @media screen and (max-height: 450px) {
        .sidenav {
            padding-top: 15px;
        }

        .sidenav a {
            font-size: 18px;
        }
    }

    .submenu {
        list-style: none;
        padding: 0;
    }

    .submenu > li {
        list-style: none;
        display: inline-block;
        width: 100%;
    }

    .mega-menu-submenu {
        list-style: none;
        width: 100%;
        padding: 0;
    }

    .mega-menu-submenu > li {
        width: 100%;
    }

    .mega-menu-submenu > li:hover {
        background-color: #eff3f8;
    }

    .mega-menu-submenu > li > a {
        padding: 10px 10px 10px 50px;;
        width: 100%;
        display: inline-block;
        text-decoration: none;
        color: #32c5d2;
        opacity: 0.9;
    }

    .portlet-body > * {
        overflow: -webkit-paged-x;
    }

    form {
        width: 100% !important;
    }
</style>
<script>
    var navstate = 'closed';

    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
        document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
        navstate = 'open';
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("mySidenav").style.boxShadow = "none";
        document.getElementById("main").style.marginLeft = "0";
        document.body.style.backgroundColor = "white";
        navstate = 'closed';
    }

    function toggleNav() {
        if (navstate === 'closed') {
            openNav();
        } else {
            closeNav();
        }
    }
</script>

@push('after-scripts')
    <script>

        openNav();
        $('.hasChild').click(function (event) {
            var active = $(this).data('active');
            if (active == false || active == undefined) {
                $(this).data('active', true);
                $(this).children('ul').last().removeClass('hide');
                $(this).children('ul').last().slideDown(300);
                $(this).children('ul').last().css('backgroundColor', 'rgba(250,250,250,1)');
            } else {
                $(this).data('active', false);
                $(this).children('ul').last().slideUp(300);
                $(this).children('ul').last().addClass('hide');
            }
        });
    </script>
@endpush()
