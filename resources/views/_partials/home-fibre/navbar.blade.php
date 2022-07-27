<body
    class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu 2-columns  "
    data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

<!-- BEGIN: Header-->
<header class="page-topbar" id="header">
    <div class="navbar navbar-fixed ">
        <nav
            class="navbar-main navbar-color nav-collapsible no-shadow nav-expanded sideNav-lock  navbar-dark gradient-45deg-red-pink ">
            <div class="nav-wrapper">
                <div class="header-search-wrapper hide-on-med-and-down">
                    <i class="material-icons">search</i>
                    <input class="header-search-input z-depth-2" type="text" name="Search"
                           placeholder="Explore Home Fiber Self Service Portal"
                           data-search="template-list">
                    <ul class="search-list collection display-none"></ul>
                </div>
                <ul class="navbar-list right">
                    <li class="dropdown-language">
                        <a class="waves-effect waves-block waves-light translation-button" href="#"
                           data-target="translation-dropdown">
{{--                            <span class="flag-icon flag-icon-gb"></span>--}}
                        </a>
                    </li>
                    <li class="hide-on-med-and-down">
                        <a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);">
                            <i class="material-icons">settings_overscan</i>
                        </a>
                    </li>
                    <li class="hide-on-large-only search-input-wrapper">
                        <a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);">
                            <i class="material-icons">search</i>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-block waves-light notification-button dropdown-trigger" href="javascript:void(0);"
                           data-target="notifications-dropdown">
                            <i class="material-icons">notifications_none<small class="notification-badge"></small></i>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);"
                           data-target="profile-dropdown">
            <span class="avatar-status avatar-online">
              <img src="{{ url('images/avatar/user.png') }}" alt="avatar"><i></i>
            </span>
                        </a>
                    </li>
                    <li>
                        <a class="waves-effect waves-block waves-light sidenav-trigger" href="#"
                           data-target="slide-out-right">
                            <i class="material-icons">format_indent_increase</i>
                        </a>
                    </li>
                </ul>
{{--                @if(isset($user->unreadNotifications))--}}
                <ul class="dropdown-content" id="notifications-dropdown">
                    <li>
                        <h6>NOTIFICATIONS<span class="new badge">0</span></h6>
                    </li>
                    <li class="divider"></li>
{{--                    @foreach($user->unreadNotifications as $notification)--}}
                    <li class="notification tooltipped" data-id="" data-position="top" data-tooltip="Mark as Read">
                        <a class="black-text" href="javascript:void(0)">
                            <span class="material-icons icon-bg-circle teal small">notifications_active</span>
{{--                            {{$notification->data['message']}}--}}
                        </a>
                        <time class="media-meta grey-text darken-2"> ago
                        </time>
                    </li>
{{--                    @endforeach--}}
                </ul>
{{--            @endif--}}
                <!-- profile-dropdown-->
                <ul class="dropdown-content" id="profile-dropdown">
                    <li>
                        <a class="grey-text text-darken-1" href="user-profile-page.html">
                            <i class="material-icons">person_outline</i>
                            Profile
                        </a>
                    </li>
                    <li>
                        <a class="grey-text text-darken-1" href="app-chat.html">
                            <i class="material-icons">chat_bubble_outline</i>
                            Chat
                        </a>
                    </li>
                    <li>
                        <a class="grey-text text-darken-1" href="page-faq.html">
                            <i class="material-icons">help_outline</i>
                            Help
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a class="grey-text text-darken-1" href="user-lock-screen.html">
                            <i class="material-icons">lock_outline</i>
                            Lock
                        </a>
                    </li>
                    <li>
                        <a class="grey-text text-darken-1" href="user-login.html">
                            <i class="material-icons">keyboard_tab</i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
            <nav class="display-none search-sm">
                <div class="nav-wrapper">
                    <form id="navbarForm">
                        <div class="input-field search-input-sm">
                            <input class="search-box-sm mb-0" type="search" required=""
                                   placeholder='Explore Jubilee Motor Assessment Portal' id="search"
                                   data-search="template-list">
                            <label class="label-icon" for="search">
                                <i class="material-icons search-sm-icon">search</i>
                            </label>
                            <i class="material-icons search-sm-close">close</i>
                            <ul class="search-list collection search-list-sm display-none"></ul>
                        </div>
                    </form>
                </div>
            </nav>
        </nav>
    </div>
    <!-- search ul  -->
    <ul class="display-none" id="default-search-main">
        <li class="auto-suggestion-title">
            <a class="collection-item" href="#">
                <h6 class="search-title">FILES</h6>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img src="images/icon/pdf-image.png" width="24" height="30" alt="sample image">
                        </div>
                        <div class="member-info display-flex flex-column">
            <span class="black-text">
              Two new item submitted</span>
                            <small class="grey-text">Marketing Manager</small>
                        </div>
                    </div>
                    <div class="status"><small class="grey-text">17kb</small></div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img src="images/icon/doc-image.png" width="24" height="30" alt="sample image">
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">52 Doc file Generator</span>
                            <small class="grey-text">FontEnd Developer</small>
                        </div>
                    </div>
                    <div class="status"><small class="grey-text">550kb</small></div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img src="images/icon/xls-image.png" width="24" height="30" alt="sample image">
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">25 Xls File Uploaded</span>
                            <small class="grey-text">Digital Marketing Manager</small>
                        </div>
                    </div>
                    <div class="status"><small class="grey-text">20kb</small></div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img src="images/icon/jpg-image.png" width="24" height="30" alt="sample image">
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">Anna Strong</span>
                            <small class="grey-text">Web Designer</small>
                        </div>
                    </div>
                    <div class="status"><small class="grey-text">37kb</small></div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion-title">
            <a class="collection-item" href="#">
                <h6 class="search-title">MEMBERS</h6>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img class="circle" src="images/avatar/avatar-7.png" width="30" alt="sample image"></div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">John Doe</span>
                            <small class="grey-text">UI designer</small>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img class="circle" src="images/avatar/avatar-8.png" width="30" alt="sample image">
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">Michal Clark</span>
                            <small class="grey-text">FontEnd Developer</small>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img class="circle" src="images/avatar/avatar-10.png" width="30" alt="sample image">
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">Milena Gibson</span>
                            <small class="grey-text">Digital Marketing</small>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img class="circle" src="images/avatar/avatar-12.png" width="30" alt="sample image">
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">Anna Strong</span>
                            <small class="grey-text">Web Designer</small>
                        </div>
                    </div>
                </div>
            </a>
        </li>
    </ul>
    <ul class="display-none" id="page-search-title">
        <li class="auto-suggestion-title">
            <a class="collection-item" href="#">
                <h6 class="search-title">PAGES</h6>
            </a>
        </li>
    </ul>
    <ul class="display-none" id="search-not-found">
        <li class="auto-suggestion">
            <a class="collection-item display-flex align-items-center" href="#">
                <span class="material-icons">error_outline</span>
                <span class="member-info">No results found.</span>
            </a>
        </li>
    </ul>
</header>
<!-- END: Header-->
