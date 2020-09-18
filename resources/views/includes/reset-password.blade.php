
<!-- Start Page Loading -->
<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>
<!-- End Page Loading -->

<div id="login-page" class="row">
    <div class="col s12 z-depth-4 card-panel">
        <form class="password-reset-form">
            <input type="hidden" value="{{$id}}" id="reset_token" name="reset_token">
            <div class="row">
                <div class="input-field col s12 center">
                    <img src="{{ asset('logins/images/img_avatar.png') }}" alt="" class="circle responsive-img valign profile-image-login">
                    <p class="center login-form-text">Reset Your Motor Insurance Password</p>
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                    <i class="mdi-action-lock-outline prefix"></i>
                    <input id="email" type="email">
                    <label for="email">Email</label>
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                    <i class="mdi-action-lock-outline prefix"></i>
                    <input id="password" type="password">
                    <label for="password">New Password</label>
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                    <i class="mdi-action-lock-outline prefix"></i>
                    <input id="password_confirmation" type="password">
                    <label for="password_confirmation">Confirm Password</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <a href="#" class="btn waves-effect waves-light col s12 login-btn" id="resetpassword">Reset Password</a>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6 l6">
                    <p class="margin medium-small"><a href="{{route('login')}}">Login ?</a></p>
                </div>
                <div class="input-field col s6 m6 l6">
                    <p class="margin right-align medium-small"></p>
                </div>
            </div>

        </form>
    </div>
</div>
