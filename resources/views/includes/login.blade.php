
<!-- Start Page Loading -->
<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>
<!-- End Page Loading -->

<div id="login-page" class="row">
    <div class="col s12 z-depth-4 card-panel">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="row">
                <div class="input-field col s12 center">
                    <img src="{{ asset('logins/images/img_avatar.png') }}" alt="" class="circle responsive-img valign profile-image-login">
                    <p class="center login-form-text text-blue">Welcome to Jubilee-Allianz</p>
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                    <i class="mdi-social-person-outline prefix"></i>
                    <input id="email" type="email" name="email"  class="@error('email') invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <label for="email" class="center-align">{{ __('E-Mail Address') }}</label>
                    @error('email')
                    <span class="helper-text" data-error="{{$message}}"></span>
                    @enderror
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                    <i class="mdi-action-lock-outline prefix"></i>
                    <input id="password" type="password" name="password" class="@error('password') is-invalid @enderror" required autocomplete="current-password">
                    <label for="password">{{ __('Password') }}</label>
                    @error('password')
                    <span class="helper-text" data-error="wrong" data-success="right">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m12 l12  login-text">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}/>
                    <label for="remember">{{ __('Remember Me') }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <button type="submit" class="btn waves-effect waves-light col s12">{{ __('Login') }}</button>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6 l6">
                    <p class="margin medium-small"><a href="{{ route('password.verifyEmail') }}">{{ __('Forgot Password?') }}</a></p>
                </div>
                <div class="input-field col s6 m6 l6">
                    <p class="margin right-align medium-small"></p>
                </div>
            </div>

        </form>
    </div>
</div>
