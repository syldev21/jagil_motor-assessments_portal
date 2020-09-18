
<!-- Start Page Loading -->
<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>
<!-- End Page Loading -->
<div id="login-page" class="row">
    <div class="col s12 z-depth-4 card-panel">
        <form class="login-form">
            {{ csrf_field() }}
            <div class="row">
                <div class="input-field col s12 center">
                    <img src="{{ asset('logins/images/img_avatar.png') }}" alt="" class="circle responsive-img valign profile-image-login">
                    <p class="center login-form-text">Forgot Password</p>
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                    <i class="mdi-social-person-outline prefix"></i>
                    <input id="email" type="text" class="validate" name="email">
                    <label for="email" class="center-align">Email</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <a href="" id="validatepasswordreset" class="btn waves-effect waves-light col s12" >Send Password Reset Link</a>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6 l6">
                    <p class="margin medium-small"><a href="{{ route('login') }}">Login</a></p>
                </div>
                <div class="input-field col s6 m6 l6">
                    <p class="margin right-align medium-small"></p>
                </div>
            </div>

        </form>
    </div>
</div>
