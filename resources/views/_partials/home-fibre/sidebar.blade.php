<!-- BEGIN: SideNav-->
<aside
    class="sidenav-main nav-expanded nav-lock nav-collapsible  sidenav-active-square  sidenav-light">
    <div class="brand-sidebar">
        <h1 class="logo-wrapper">
            <div class="row">
                <div class="col s1"></div>
                <div class="col s10">
                    <a class="darken-1 hide-on-med-and-down" href="{{ url('home') }}">
                        <img class="responsive-img" src="{{ url('images/logo/jubilee_logo.png') }}" alt="Jubilee logo"/>
                    </a>
                    <a class="brand-logo darken-1 show-on-medium-and-down hide-on-med-and-up" href="{{ url('home') }}">
                        <img class="" src="{{ url('images/logo/jubilee_logo.png') }}"
                             alt="Jubilee logo"/>
                    </a>
                </div>
                <div class="col s1"></div>
            </div>
{{--            <a class="navbar-toggler" href="javascript:void(0)"><i class="material-icons">radio_button_checked</i></a>--}}
        </h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
        data-menu="menu-navigation" data-collapsible="menu-accordion">

        <li class="navigation-header">
            <a class="navigation-header-text">Quick Operations</a>
            <i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        @if($user->userTypeID == \App\Conf\Config::$USER_TYPES['HOME FIBER CUSTOMER']['ID'])
            <li class="">
                <a href="#" data-id="" class="sidenav-link" id="fetchPortfolio">
                    <i class="material-icons">apps</i>
                    <span data-i18n="Chartist">My Portfolio</span>
                </a>
            </li>
            <li class="">
                <a href="#" data-id="" class="sidenav-link" id="fetchCPayments">
                    <i class="material-icons">attach_money</i>
                    <span data-i18n="Chartist">Payments</span>
                </a>
            </li>
            <li class="">
                <a href="#" data-id="" class="sidenav-link" id="fetchCClaims">
                    <i class="material-icons">assignment_returned</i>
                    <span data-i18n="Chartist">Claims</span>
                </a>
            </li>
            <li class="bold">
                <a class="collapsible-header sidenav-link" href="javascript:void(0)">
                    <i class="material-icons">assessment</i>
                    <span class="menu-title" data-i18n="Chart">Claims</span>
                </a>
                <div class="collapsible-body">
                    <ul class="collapsible collapsible-sub" data-collapsible="accordion">

                        <li class="">
                            <a href="#" data-id="" class="sidenav-link fetch-assessments">
                                <i class="material-icons">assignment_ind</i>
                                <span data-i18n="ChartJS">My Claims</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#" data-id="" class="sidenav-link fetch-assessments">
                                <i class="material-icons">assignment_ind</i>
                                <span data-i18n="ChartJS">Launch a Claim</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

        @else
        <li class="">
            <a href="#" data-id="" class="sidenav-link fetch-customers">
                <i class="material-icons">people</i>
                <span data-i18n="Chartist">Customers</span>
            </a>
        </li>
        <li class="">
            <a href="#" data-id="" class="sidenav-link fetch-payments">
                <i class="material-icons">attach_money</i>
                <span data-i18n="Chartist">Payments</span>
            </a>
        </li>
        <li class="">
            <a href="#" data-id="" class="sidenav-link">
                <i class="material-icons">help_outline</i>
                <span data-i18n="Chartist">Help</span>
            </a>
        </li>
        @endif
        <li class="bold ">
            <a class="waves-effect waves-light"
               href="{{ route('user.logout') }}"
            >
                <i class="material-icons">power_settings_new</i>
                <span class="menu-title" data-i18n="logout">Logout</span>
            </a>
        </li>

    </ul>
    <div class="navigation-background"></div>
    <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only"
       href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>  <!-- END: SideNav-->
