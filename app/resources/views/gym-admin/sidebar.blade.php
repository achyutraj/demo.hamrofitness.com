<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                <!-- input-group -->
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
                    </span>
                </div>
                <!-- /input-group -->
            </li>

            <li class="user-pro">
                <a href="javascript:" class="waves-effect">
                    @if($user->image =='')
                        <img src="{{asset('/fitsigma/images/').'/'.'user.svg'}}" width="36"
                             class="img-circle img-change"/>
                    @else
                        @if($gymSettings->local_storage == 0)
                            <img class="img-circle img-change" width="36" src="{{$profilePath.$user->image}}"/>
                        @else
                            <img class="img-circle img-change" width="36"
                                 src="{{asset('/uploads/profile_pic/thumb/').'/'.$user->image}}"/>
                        @endif
                    @endif
                    <span class="hide-menu">{{ucwords($user->username)}}
                        <span class="fa arrow"></span>
                    </span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ route('gym-admin.profile.index') }}"><i class="ti-user"></i> My Profile</a></li>
                    <li><a href="{{ route('logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
                </ul>
            </li>

            <li class="nav-small-cap m-t-10"> Main Menu</li>
            @if($user->is_admin == 1)
                <li>
                    <a href="{{route('gym-admin.superadmin.dashboard')}}" class="{{$superAdminMenu ?? ''}}">
                        <i class="zmdi zmdi-view-dashboard zmdi-hc-fw fa-fw"></i>
                        <span class="hide-menu">Dashboard Super</span>
                    </a>
                </li>
            @endif

            @if($user->can("view_dashboard"))
                <li>
                    <a href="{{route('gym-admin.dashboard.index')}}" class="{{$dashboardMenu ?? ''}}"><i
                            class="zmdi zmdi-view-dashboard zmdi-hc-fw fa-fw"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
            @endif

            @if($user->is_admin == 1)
                <li>
                    <a href="{{ route('gym-admin.superadmin.manage-branches') }}" class="{{$indexSuperAdmin ?? ''}}">
                        <i class=" fa fa-cogs"></i>
                        <span class="hide-menu">Manage Branch</span>
                    </a>
                </li>
            @endif

            @if($user->can("view_activity_log"))
                <li>
                    <a href="{{ route('gym-admin.activity_log') }}" class="{{$indexActivityLog ?? ''}}">
                        <i class=" fa fa-list"></i>
                        <span class="hide-menu">Activity Log</span>
                    </a>
                </li>
            @endif
            @if($user->can("view_enquiry"))
                <li>
                    <a href="{{route('gym-admin.enquiry.index')}}" class="{{$enquiryMenu ?? ''}} ">
                        <i class="icon-earphones-alt"></i>
                        <span class="hide-menu">Enquiry</span>
                    </a>
                </li>
            @endif

            @if($user->can("view_customers"))
                <li>
                    <a href="{{route('gym-admin.client.index')}}" class="{{ $customerMenu ?? '' }}"><i
                            class="icon-users"></i><span class="hide-menu">Customers</span> </a>
                </li>
            @endif
            @if($user->can("add_biometrics") && $common_details->has_device == 1)
                <li>
                    <a href="{{route('device.biometrics.index')}}" class="{{ $customerBioMenu ?? '' }}"><i
                            class="fa fa-wifi"></i>BioMetric</a>
                </li>
            @endif
            @if($user->can("view_subscriptions"))
                <li class="{{$subscriptionMenu ?? ''}}">
                    <a href="{{route('gym-admin.client-purchase.active')}}" class="nav-link  ">
                        <i class="fa fa-money"></i>
                        <span class="hide-menu">Subscriptions</span>
                    </a>
                </li>
            @endif
            @if($user->can("view_payments"))
                <li class="{{$showpaymentMenu ?? ''}}">
                    <a href="{{ route('gym-admin.membership-payment.index') }}" class="nav-link ">
                        <i class="fa fa-money"></i><span class="hide-menu">Payments</span>
                    </a>
                </li>
            @endif
            @if($user->can("view_due_payments"))
                <li class="{{$showclientDuesMenu ?? ''}}">
                    <a href="{{route('gym-admin.client-purchase.client-dues')}}" class="nav-link ">
                        <i class="fa fa-bullhorn"></i> <span class="hide-menu">Due Payments</span>
                    </a>
                </li>
            @endif
            @if($user->can("message"))
                <li>
                    <a href="javascript:" class="waves-effect">
                        <i class="fa fa-commenting-o"></i><span class="hide-menu"> SMS<span
                                class="fa arrow"></span></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{route('gym-admin.sms.index')}}" class="{{$smsMenu ?? ''}} ">
                                <i class="fa fa-comment"></i>
                                <span class="hide-menu">Send SMS</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if($user->can("view_customers") || $user->can("add_attendance") ||  $user->can("task") ||
            $user->can("view_subscriptions") || $user->can("subscription_extend_features") || $user->can("diet_plan")
            || $user->can("diet_plan") || $user->can("training_plan") || $user->can("class_schedule") || $user->can("add_biometrics") || $user->can("view_redeems")
            || $user->can("body_measurement") || $user->can("body_progress_tracker") || $user->can("view_feedback") || $user->can("view_targets") || $user->can("view_assets"))
                <li>
                    <a href="javascript:" class="waves-effect {{ $businessMenu ?? '' }}"><i
                            class="fa fa-briefcase">
                        </i> <span class="hide-menu"> Business <span class="fa arrow"></span></span></a>
                    <ul class="nav nav-second-level">
                        @if($user->can("view_customers"))
                            <li>
                                <a href="{{route('gym-admin.client.index')}}" class="{{ $customerMenu ?? '' }}"><i
                                        class="icon-users"></i>Customers</a>
                            </li>
                        @endif
                        @if($user->can("view_subscriptions"))
                            <li class="{{$subscriptionMenu ?? ''}}">
                                <a href="{{route('gym-admin.client-purchase.index')}}" class="nav-link  ">
                                    <i class="fa fa-money"></i> All Subscriptions
                                </a>
                            </li>
                        @endif
                        @if($user->can("subscription_extend_features"))
                            <li class="{{$extendsubscriptionMenu ?? ''}}">
                                <a href="{{route('gym-admin.client-purchase.extend')}}" class="nav-link  ">
                                    <i class="fa fa-expand"></i> Extend Subscriptions
                                </a>
                            </li>

                            <li class="{{$freezesubscriptionMenu ?? ''}}">
                                <a href="{{route('gym-admin.client-purchase.freezeIndex')}}" class="nav-link  ">
                                    <i class="fa fa-ban"></i> Freeze Subscriptions
                                </a>
                            </li>
                        @endif
                        @if($user->can("add_biometrics") && $common_details->has_device == 1)
                            <li>
                                <a href="{{route('device.biometrics.index')}}" class="{{ $customerBioMenu ?? '' }}"><i
                                        class="fa fa-wifi"></i>BioMetric</a>
                            </li>
                        @endif

                        @if($user->can("diet_plan"))
                            <li class="{{$dietPlanMenu ?? ''}} nav-link nav-toggle">
                                <a href="{{ route('gym-admin.diet-plans.index') }}" class="nav-link"><i class="fa fa-cutlery"></i> Diet Plan</a>
                            </li>
                        @endif
                        @if($user->can("training_plan"))
                            <li class="{{$trainingPlanMenu ?? ''}} nav-link nav-toggle">
                                <a href="{{ route('gym-admin.training-plans.index') }}" class="nav-link"><i class="fa fa-cube"></i> Training
                                    Plan</a>
                            </li>
                        @endif
                        @if($user->can("class_schedule"))
                            <li class="{{$classScheduleMenu ?? ''}} nav-link nav-toggle">
                                <a href="{{ route('gym-admin.class-schedule.index') }}" class="nav-link"><i class="fa fa-clock-o"></i> Class
                                    Schedule</a>
                            </li>
                        @endif
                        @if($user->can("add_attendance"))
                            <li class="{{$attendanceMenu ?? ''}}">
                                <a href="{{route('gym-admin.attendance.create')}}" class="nav-link"><i
                                        class="icon-plus"></i> Mark Attendance</a>
                            </li>
                        @endif
                        @if($user->can("view_redeems"))
                        <li>
                            <a href="{{route('gym-admin.redeems.index')}}" class="{{ $redeemMenu ?? '' }}"><i
                                    class="fa fa-registered"></i>Redeem Offer</a>
                        </li>
                        @endif
                        @if($user->can("task"))
                            <li class="{{$taskMenu ?? ''}} nav-link nav-toggle">
                                <a href="{{route('gym-admin.task.index')}}" class="nav-link"><i class="fa fa-tasks"></i> Task</a>
                            </li>
                        @endif
                        @if($user->can("body_measurement"))
                            <li class="{{$measurementMenu ?? ''}}">
                                <a href="{{ route('gym-admin.measurements.index') }}" class="nav-link ">
                                    <i class="fa fa-balance-scale"></i> Body Measurement
                                </a>
                            </li>
                        @endif
                        @if($user->can("body_progress_tracker"))
                            <li class="{{$trackerMenu ?? ''}}">
                                <a href="{{ route('gym-admin.measurements.progressIndex') }}" class="nav-link ">
                                    <i class="fa fa-bars"></i> Progress Tracker
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_feedback"))
                            <li>
                                <a href="{{ route('gym-admin.feedback.index') }}" class="nav-link {{$feedbackMenu ?? ''}}">
                                    <i class="fa fa-feed"></i> <span class="hide-menu"> Feedback</span>
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_targets"))
                            <li>
                                <a href="{{route('gym-admin.target.index')}}" class="{{$targetMenu ?? ''}}">
                                    <i class="fa fa-bullseye"></i> Targets
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_assets"))
                            <li>
                                <a href="{{route('gym-admin.asset-management.index')}}"
                                   class="{{$assetManagementMenu ?? ''}}">
                                    <i class="fa fa-dropbox"></i> Assets
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($user->can("view_payments") || $user->can("view_due_payments") || $user->can("view_invoice")
                || $user->can("expense") || $user->can("income") || $user->can("profit_loss_report") )
                <li>
                    <a href="javascript:" class="waves-effect {{ $paymentMenu ?? '' }}">
                        <i class="fa fa-money"></i>
                        <span class="hide-menu"> Accounts
                            <span class="fa arrow"></span>
                        </span>
                    </a>
                    <ul class="nav nav-second-level">
                        @if($user->can("view_payments"))
                            <li class="{{$showpaymentMenu ?? ''}}">
                                <a href="{{ route('gym-admin.membership-payment.index') }}" class="nav-link ">
                                    <i class="fa fa-money"></i> Payments
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_due_payments"))
                            <li class="{{$showclientDuesMenu ?? ''}}">
                                <a href="{{route('gym-admin.client-purchase.client-dues')}}" class="nav-link ">
                                    <i class="fa fa-bullhorn"></i> Due Payments
                                </a>
                            </li>
                        @endif
                         @if($user->can("add_biometrics") && $common_details->has_device == 1)
                            <li>
                                <a href="{{route('device.biometrics.index')}}" class="{{ $customerBioMenu ?? '' }}"><i
                                        class="fa fa-wifi"></i>BioMetric</a>
                            </li>
                        @endif
                        @if($user->can("view_invoice"))
                            <li>
                                <a href="{{route('gym-admin.gym-invoice.membershipIndex')}}" class="{{$invoiceMenu ?? ''}}">
                                    <i class="fa fa-file"></i> Invoice
                                </a>
                            </li>
                        @endif

                        @if($user->can("expense"))
                            <li>
                                <a href="{{ route('gym-admin.expense.index') }}" class="{{$expenseMenu ?? ''}}">
                                    <i class="fa fa-money"></i> Expenses
                                </a>

                            @endif
                            @if($user->can("income"))
                            <li>
                                <a href="{{ route('gym-admin.incomes.index') }}" class="{{ $incomeMenu ?? ''}}">
                                    <i class="fa fa-dollar"></i>Others Incomes
                                </a>
                            </li>
                        @endif

                        @if($user->can("view_bank_ledger"))
                            <li>
                                <a href="{{route('gym-admin.bankLedger.index')}}" class="{{$bankLedgerMenu ?? ''}}">
                                    <i class="fa fa-calculator"></i> Bank Ledger
                                </a>
                            </li>
                        @endif

                        @if($user->can("profit_loss_report"))
                        <li>
                            <a href="{{route('gym-admin.profit-loss-report.index')}}" class="{{$templateMenu ?? ''}} ">
                                <i class="fa fa-money"></i>
                                <span class="hide-menu"> Profit/Loss </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($user->can("view_lockers") || $user->can("add_lockers") || $user->can("edit_lockers") || $user->can("delete_lockers") ||
                $user->can("view_reservations") || $user->can("add_reservations") || $user->can("edit_reservations") || $user->can("delete_reservations"))
                <li>
                    <a href="javascript:" class="waves-effect {{ $lockerMenu ?? '' }}">
                        <i class="fa fa-lock"></i><span class="hide-menu"> Lockers <span
                                class="fa arrow"></span></span>
                    </a>
                    <ul class="nav nav-second-level">
                        @if($user->can("view_reservations"))
                        <li>
                            <a href="{{ route('gym-admin.reservations.index') }}" class="{{$reservationMenu ?? ''}}">
                                <i class="fa fa-list-alt"></i> Reservation Lists
                            </a>
                        </li>
                        @endif
                        @if($user->can("view_due_payments"))
                            <li>
                                <a href="{{route('gym-admin.reservations.dues')}}" class="{{$dueReservationMenu ?? ''}}">
                                    <i class="fa fa-cc-mastercard"></i> Due Payment
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_payments"))
                            <li class="{{$reservationPaymentMenu ?? ''}}">
                                <a href="{{ route('gym-admin.reservation-payments.index') }}" class="nav-link ">
                                    <i class="fa fa-money"></i> Payments
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_lockers"))
                            <li>
                                <a href="{{route('gym-admin.lockers.index')}}" class="{{$lockerModuleMenu ?? ''}}">
                                    <i class="fa fa-key"></i> Lockers Lists
                                </a>
                            </li>
                        @endif
                        @if($user->can("add_lockers"))
                            <li>
                                <a href="{{route('gym-admin.locker-category.index')}}" class="{{$categoryMenu ?? ''}}">
                                    <i class="fa fa-clipboard"></i> Locker Category
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endcan

            @if($user->can("view_products") || $user->can("view_sells") || $user->can("view_product_payment"))
                <li>
                    <a href="javascript:" class="waves-effect">
                        <i class=" fa fa-product-hunt"></i><span class="hide-menu"> Products<span
                                class="fa arrow"></span></span>
                    </a>
                    <ul class="nav nav-second-level">
                        @if($user->can("view_products"))
                            <li>
                                <a href="{{route('gym-admin.products.index')}}" class="{{$productMenu ?? ''}}">
                                    <i class="fa fa-product-hunt"></i> Product Lists
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_sells"))
                            <li>
                                <a href="{{route('gym-admin.sales.index')}}"
                                   class="{{$productsSellsMenu ?? ''}}">
                                    <i class="fa fa-shopping-bag"></i> Product Sells
                                </a>
                            </li>
                        @endcan
                        @if($user->can("view_sells"))
                            <li>
                                <a href="{{route('gym-admin.products.product-dues')}}"
                                class="{{$showProductDuesMenu ?? ''}}">
                                    <i class="fa fa-bullhorn"></i> Due Payment
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_product_payment"))
                            <li class="{{$showproductpaymentMenu ?? ''}}">
                                <a href="{{ route('gym-admin.product-payments.index') }}" class="nav-link ">
                                    <i class="fa fa-money"></i> Payments
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endcan


            @if($user->can("view_target_report") || $user->can("view_client_report") || $user->can("view_booking_report")
                || $user->can("view_finance_report") || $user->can("view_attendance_report") || $user->can("view_enquiry_report")
                || $user->can("balance_report") || $user->can("product_report") || $user->can("bank_report") || $user->can("reservation_report")
                || $user->can("expense_report"))
                <li>
                    <a href="javascript:" class="waves-effect {{ $reportMenu ?? '' }} ">
                        <i class=" icon-notebook"></i><span class="hide-menu"> Reports<span
                                class="fa arrow"></span></span>
                    </a>
                    <ul class="nav nav-second-level">
                        @if($user->is_admin == 1)
                            <li>
                                <a href="{{route('gym-admin.branch-renew-report.index')}}"
                                class="{{$branchreportMenu ?? ''}}">
                                    <i class="icon-home"></i> Branch Renew Report
                                </a>
                            </li>
                        @endif

                        @if($user->can("view_client_report"))
                            <li>
                                <a href="{{route('gym-admin.client-report.index')}}"
                                   class="{{$clientreportMenu ?? ''}}">
                                    <i class="icon-users"></i> Clients Report
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_booking_report"))
                            <li>
                                <a href="{{route('gym-admin.booking-report.index')}}"
                                   class="{{$bookingreportMenu ?? ''}}">
                                    <i class="icon-notebook"></i> Subscription Report
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_finance_report"))
                            <li>
                                <a href="{{route('gym-admin.finance-report.index')}}"
                                   class="{{$financialreportMenu ?? ''}}">
                                    <i class="fa fa-money"></i> Financial Report
                                </a>
                            </li>
                        @endif
                        @if($user->can("reservation_report"))
                            <li>
                                <a href="{{ route('gym-admin.reservation-report.index') }}"
                                class="{{$reservationReportMenu ?? ''}}">
                                    <i class="fa fa-list"></i> Reservation Report
                                </a>
                            </li>
                        @endif
                        @if($user->can("product_report"))
                            <li>
                                <a href="{{ route('gym-admin.product-sale-report.index') }}"
                                class="{{$productsellreportMenu ?? ''}}">
                                    <i class="fa fa-product-hunt"></i> Product Sell Report
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_attendance_report"))
                            <li>
                                <a href="{{route('gym-admin.attendance-report.index')}}"
                                class="{{$attendancereportMenu ?? ''}}">
                                    <i class="fa fa-tasks"></i> Attendance Report
                                </a>
                            </li>
                        @endif
                        @if($user->can("balance_report"))
                            <li>
                                <a href="{{ route('gym-admin.balance-report.index') }}"
                                   class="{{$balancereportMenu ?? ''}}">
                                    <i class="fa fa-balance-scale"></i> Balance Report
                                </a>
                            </li>
                        @endif
                        @if($user->can("income_report"))
                            <li>
                                <a href="{{ route('gym-admin.reports.income')}}"
                                   class="{{$collectionReportMenu ?? ''}}">
                                    <i class="fa fa-line-chart"></i> Other Incomes Report
                                </a>
                            </li>
                        @endif
                        @if($user->can("expense_report"))
                         <li>
                            <a href="{{ route('gym-admin.reports.expense') }}"
                               class="{{$expenseReportMenu ?? ''}}">
                                <i class="fa fa-dollar"></i> Expenses Report
                            </a>
                        </li>
                        @endif

                        @if($user->can("bank_report"))
                            <li>
                                <a href="{{ route('gym-admin.bank-report.index') }}"
                                   class="{{$bankreportMenu ?? ''}}">
                                    <i class="fa fa-bank"></i> Bank Ledgers Report
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_enquiry_report"))
                            <li>
                                <a href="{{route('gym-admin.enquiry-report.index')}}"
                                class="{{$enquiryreportMenu ?? ''}}">
                                    <i class="fa fa-question-circle"></i> Enquiry Report
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_target_report"))
                            <li>
                                <a href="{{route('gym-admin.target-report.index')}}"
                                   class="{{$targetreportMenu ?? ''}}">
                                    <i class="fa fa-bullseye"></i> Target Report
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($user->can("view_employs") || $user->can("view_leaves") ||
                 $user->can("task") || $user->can("employee_attendance"))
                <li>
                    <a href="javascript:" class="waves-effect">
                        <i class=" fa fa-building"></i><span class="hide-menu"> Office<span
                                class="fa arrow"></span></span>
                    </a>
                    <ul class="nav nav-second-level">
                        @if($user->can("view_employs"))
                            <li>
                                <a href="{{route('gym-admin.users.showEmployee')}}"
                                   class="{{$employeeMenu ?? ''}} nav-link nav-toggle">
                                    <i class="icon-user"></i> Employee
                                </a>
                            </li>
                        @endcan
                        @if($user->can("employee_attendance"))
                            @if(count($user->employees) > 0)
                                <li>
                                    <a href="{{route('gym-admin.employAttendance.show',$user->employees[0]->id)}}" class="">
                                        <i class="fa fa-calendar"></i> Attendance
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{route('gym-admin.employAttendance.create')}}" class="">
                                        <i class="fa fa-calendar"></i>  Attendance
                                    </a>
                                </li>
                            @endif
                        @endcan
                        @if($user->can("view_leaves"))
                            <li>
                                <a href="{{route('gym-admin.employ.showLeave')}}"
                                   class="{{$leaveMenu ?? ''}} nav-link nav-toggle">
                                    <i class="fa fa-sign-out"></i> Leave
                                </a>
                            </li>
                        @endcan
                        @if($user->can("task"))
                            <li class="">
                                <a href="{{route('gym-admin.employTaskList.index')}}" class="{{$taskMenu ?? ''}}">
                                    <i class="fa fa-tasks"></i> Tasks
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            @if($user->can("mobile_app") || $user->can("view_membership") || $user->can('my_gym') || $user->can('view_banks_and_branches') || $user->can('view_bank_account')
            || $user->can('view_suppliers'))
                <li>
                    <a href="javascript:" class="waves-effect">
                        <i class=" fa fa-gear"></i><span class="hide-menu"> Settings<span
                                class="fa arrow"></span></span>
                    </a>
                    <ul class="nav nav-second-level">
                        @if($user->can("view_membership"))
                            <li class="{{$membershipMenu ?? ''}}">
                                <a href="{{route('gym-admin.membership-plans.index')}}" class="nav-link nav-toggle">
                                    <i class="icon-badge"></i> Membership Plans
                                </a>
                            </li>
                        @endif
                        @if($user->can("mobile_app") && $user->is_admin == 1)
                            <li>
                                <a href="{{route('gym-admin.mobile-app.index')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-mobile-phone"></i> Mobile App
                                </a>
                            </li>
                        @endif
                        @if($user->can("manage_device") && $common_details->has_device == 1 && $user->is_admin == 1)
                            <li>
                                <a href="{{ route('device.branchDepartment.index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-fax"></i>
                                    <span class="hide-menu">Manage Device</span>
                                </a>
                            </li>
                        @endif
                        @if($common_details->has_device == 1)
                            <li>
                                <a href="{{ route('device.info.checkDeviceStatus') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-fax"></i>
                                    <span class="hide-menu">Check Device Status</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('device.adms.logs') }}" class="{{$admsLogMenu ?? ''}}">
                                    <i class="fa fa-cogs"></i>
                                    <span class="hide-menu">ADMS Real-time Sync</span>
                                </a>
                            </li>
                        @endif
                        @if($user->is_admin == 1)
                            <li>
                                <a href="{{route('gym-admin.notifications.index')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-envelope-o"></i> Push Notification
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_suppliers"))
                            <li>
                                <a href="{{ route('gym-admin.suppliers.index') }}" class="{{$supplierMenu ?? ''}}">
                                    <i class="fa fa-user"></i> Party
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_tutorials"))
                            <li>
                                <a href="{{ route('gym-admin.tutorials.index') }}" class="{{$tutorialMenu ?? ''}}">
                                    <i class="fa fa-user"></i> Gym Tutorial
                                </a>
                            </li>
                        @endif
                        @if($user->can("my_gym"))
                            <li>
                                <a href="{{route('gym-admin.my-gym.index')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-heartbeat"></i> My Gym
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('gym-admin.setting.activityPage') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-level-up"></i> Level and Activities
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('gym-admin.classesTrainers.index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-joomla"></i> Classes & Trainer
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('gym-admin.employ.leaveType') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-outdent"></i> Leave
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_banks_and_branches"))
                            <li>
                                <a href="{{route('gym-admin.banksBranches.index')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-institution"></i> Banks & Branches
                                </a>
                            </li>
                        @endif
                        @if($user->can("view_bank_account"))
                            <li>
                                <a href="{{route('gym-admin.banksAccount.index')}}" class="nav-link nav-toggle">
                                    <i class="fa fa-credit-card"></i> Bank Account
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
