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
        </h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
        data-menu="menu-navigation" data-collapsible="menu-accordion">

        <li class="navigation-header">
            <a class="navigation-header-text">Travel Quick Operations</a>
            <i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        <li class="">
            <a href="#"
               class="listUsers sidenav-link"
            >
                <i class="material-icons">list</i>
                <span data-i18n="Invoice List">All Policies</span>
            </a>
        </li>
        <li class="">
            <a href="#"
               class="fetch-travel-policies sidenav-link" data-id="{{App\Conf\Config::TRAVEL_DISPLAY_STATUSES['PROCESSED']['ID']}}"
            >
                <i class="material-icons">done_all</i>
                <span data-i18n="Invoice List">Processed Policies</span>
            </a>
        </li>
        <li class="">
            <a href="#"
               class="fetch-travel-policies sidenav-link" data-id="{{App\Conf\Config::TRAVEL_DISPLAY_STATUSES['PENDING']['ID']}}"
            >
                <i class="material-icons">done</i>
                <span data-i18n="Invoice List">Pending Policies</span>
            </a>
        </li>
        <li class="">
            <a href="#"
               class="fetch-travel-policies sidenav-link" data-id="{{App\Conf\Config::TRAVEL_DISPLAY_STATUSES['FAILED']['ID']}}"
            >
                <i class="material-icons">info_outline</i>
                <span data-i18n="Invoice List">Failed Policies</span>
            </a>
        </li>
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
