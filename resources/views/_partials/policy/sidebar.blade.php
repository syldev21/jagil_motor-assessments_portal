<!-- BEGIN: SideNav-->
<aside
    class="sidenav-main nav-expanded nav-lock nav-collapsible  sidenav-active-square  sidenav-light">
    <div class="brand-sidebar">
        <h1 class="logo-wrapper">
            <a class="brand-logo darken-1" href="index.html">
                <img class="hide-on-med-and-down" src="{{ url('images/logo/jubilee_logo.png') }}" alt="Jubilee logo"/>
                <img class="show-on-medium-and-down hide-on-med-and-up" src="{{ url('images/logo/jubilee_logo.png') }}"
                     alt="Jubilee logo"/>

                <span class="logo-text hide-on-med-and-down">
                    Insurance
                  </span>
            </a>
            <a class="navbar-toggler" href="javascript:void(0)"><i class="material-icons">radio_button_checked</i></a>
        </h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
        data-menu="menu-navigation" data-collapsible="menu-accordion">

        <li class="navigation-header">
            <a class="navigation-header-text">Quick Operations</a>
            <i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
{{--        @hasrole(\App\Conf\Config::$ROLES["ADJUSTER"])--}}
        <li class="bold ">
            <a class="collapsible-header sidenav-link"
               href="javascript:void(0) "
            >
                <i class="material-icons">view_list</i>
                <span class="menu-title" data-i18n="Chart">Renewals</span>
            </a>
            <div class="collapsible-body">
                <ul class="collapsible collapsible-sub" data-collapsible="accordion">

{{--                    <li class="">--}}
{{--                        <a href="#" class="sidenav-link fetch-renewals" data-id="{{\App\Conf\Config::$PERIOD['TODAY']['ID']}}">--}}
{{--                            <i class="material-icons">file_upload</i>--}}
{{--                            <span data-i18n="ChartJS">Today</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="">--}}
{{--                        <a href="#" class="sidenav-link fetch-renewals" data-id="{{\App\Conf\Config::$PERIOD['TOMORROW']['ID']}}">--}}
{{--                            <i class="material-icons">reorder</i>--}}
{{--                            <span data-i18n="ChartJS">Tomorrow</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="">--}}
{{--                        <a href="#" class="sidenav-link fetch-renewals"  data-id="{{\App\Conf\Config::$PERIOD['ONE_WEEK']['ID']}}">--}}
{{--                            <i class="material-icons">assignment_ind</i>--}}
{{--                            <span data-i18n="Chartist">One Week</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="">--}}
{{--                        <a href="#" class="sidenav-link fetch-renewals"  data-id="{{\App\Conf\Config::$PERIOD['ONE_MONTH']['ID']}}">--}}
{{--                            <i class="material-icons">assignment_turned_in</i>--}}
{{--                            <span data-i18n="Chartist">One Month</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="">--}}
{{--                        <a href="#" class="sidenav-link fetch-renewals" data-id="{{\App\Conf\Config::$PERIOD['THREE_MONTHS']['ID']}}">--}}
{{--                            <i class="material-icons">next_week</i>--}}
{{--                            <span data-i18n="Chartist">Three Months</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="">
                        <a href="#" class="sidenav-link fetch-renewals renewals-loader" data-id="{{\App\Conf\Config::$PERIOD['TWO_MONTHS']['ID']}}">
                            <i class="material-icons">next_week</i>
                            <span data-i18n="Chartist">Two Months</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
{{--        @endhasrole--}}
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
