<div id="main">
    <div class="row">
        <div class="col s12 m6 l4">
            <div class="card padding-4 animate fadeRight">
                <center>
                    <h5>Motor Assessment Service</h5>
                    <div class="row">
                        <div class="col s2"></div>
                        <div class="col s8">
                            <img src="{{ asset('images/icon/Inspection.png') }}" class="responsive-img">
                            <br/>
                        </div>
                        <div class="col s2"></div>
                    </div>
                    <a href="{{ route("assessments") }}" class="btn float-right">Access Service</a>
                    <p></p>
                </center>
            </div>
        </div>
        <div class="col s12 m6 l4">
            @if($user->userTypeID == App\Conf\Config::$USER_TYPES['INTERNAL']['ID'])
            <div class="card padding-4 animate fadeLeft">
                <center>
                    <h5>Motor Valuation Service</h5>
                    <div class="row">
                        <div class="col s2"></div>
                        <div class="col s8">
                            <img src="{{ asset('images/icon/valuation.jpg') }}" class="responsive-img">
                        </div>
                        <div class="col s2"></div>
                    </div>
                    <button class="btn float-right">Access Service</button>
                    <p></p>
                </center>
            </div>
            @endif
        </div>
        <div class="col s12 m6 l4">
            @if($user->userTypeID == App\Conf\Config::$USER_TYPES['INTERNAL']['ID'])
            <div class="card padding-4 animate fadeRight">
                <center>
                    <h5>Policy Renewals Service</h5>
                    <div class="row">
                        <div class="col s2"></div>
                        <div class="col s8">
                            <img src="{{ asset('images/icon/policy.jpg') }}" class="responsive-img">
                        </div>
                        <div class="col s2"></div>
                    </div>
                    <button class="btn float-right">Access Service</button>
                </center>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col s12 m6 l4">
            @if($user->userTypeID == App\Conf\Config::$USER_TYPES['INTERNAL']['ID'])
            <div class="card padding-4 animate fadeRight">
                <center>
                    <h5>Safaricom Home Fibre Service</h5>
                    <div class="row">
                        <div class="col s2"></div>
                        <div class="col s8">
                            <img src="{{ asset('images/icon/fiber.png') }}" class="responsive-img">
                        </div>
                        <div class="col s2"></div>
                    </div>
                    <button class="btn float-right">Access Service</button>
                    <p></p>
                </center>
            </div>
            @endif
        </div>
        <div class="col s12 m6 l4">
        </div>
        <div class="col s12 m6 l4">

        </div>
    </div>
</div>
