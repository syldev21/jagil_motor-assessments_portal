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
                        <img class="" src="{{ url('images/logo/jubilee') }}"
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
            <a class="navigation-header-text">Quick Operations</a>
            <i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        <li class="bold ">
            <a class="sidenav-link" id="dashboard"
               href="#"
            >
                <i class="material-icons">dashboard</i>
                <span class="menu-title" data-i18n="dashboard">Dashboard</span>
            </a>
        </li>
        <li class="">
            <a href="#"
               class="fetch_proportions sidenav-link"
            >
                <i class="material-icons">stream</i>
                <span data-i18n="Invoice List">Follower Proportions</span>
            </a>
        </li>
        <li class="">
            <a href="#"
               class="add-claim-form sidenav-link"
            >
                <i class="material-icons">add_box</i>
                <span data-i18n="Invoice ist">Upload Claim</span>
            </a>
        </li>

        <li class="">
            <a href="#"
               class="fetch-nhif-claims sidenav-link" data-id="{{App\Conf\Config::NHIF_DISPLAY_STATUSES["SUBMITTED"]["ID"]}}">
                <i class="material-icons">assignment_turned_in</i>
                <span data-i18n="Invoice List">Submitted Claims</span>
            </a>
        </li>
        <li class="">
            <a href="#"
               class="fetch-nhif-claims sidenav-link" data-id="{{App\Conf\Config::NHIF_DISPLAY_STATUSES["DOCUMENTS_PENDING"]["ID"]}}">
                <i class="material-icons">pending_action_two_tone</i>
                <span data-i18n="Invoice List">Documents Pending</span>
            </a>
        </li>
        <li class="">
            <a href="#"
               class="fetch-nhif-claims sidenav-link" data-id="{{App\Conf\Config::NHIF_DISPLAY_STATUSES["IN_PROGRESS"]["ID"]}}"
            >
                <i class="material-icons">launch</i>
                <span data-i18n="Invoice List">In Progress</span>
            </a>
        </li>
        <li class="">
            <a href="#"
               class="fetch-nhif-claims sidenav-link" data-id="{{App\Conf\Config::NHIF_DISPLAY_STATUSES["PAID"]["ID"]}}"
            >
                <i class="material-icons">check_circle_outline</i>
                <span data-i18n="Invoice List">Paid</span>
            </a>
        </li>
        <li class="">
            <a href="#"
               class="fetch-nhif-claims sidenav-link" data-id="{{App\Conf\Config::NHIF_DISPLAY_STATUSES["CLOSED"]["ID"]}}"
            >
                <i class="material-icons">check_circle</i>
                <span data-i18n="Invoice List">Closed</span>
            </a>
        </li>
        <li class="">
            <a href="#"
               class="fetch-nhif-claims sidenav-link" data-id="{{App\Conf\Config::NHIF_DISPLAY_STATUSES["REJECTED"]["ID"]}}"
            >
                <i class="material-icons">cancel</i>
                <span data-i18n="Invoice List">Rejected</span>
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
