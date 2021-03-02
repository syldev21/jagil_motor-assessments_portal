@include('_partials.header')
@include('_partials.navbar')
@include('_partials.sidebar')
<div id="main" style="margin-left:30px;">

    <div style="text-align: center">
        <img src="{{ asset('images/logo/jubilee_logo.png') }}" class="content-group mt-5" alt="" style="width: 120px;">
    </div>
    <h5>DATE: {{ Carbon\Carbon::now()->format('l, F d, Y') }}</h5>
    <h5>OUR REF: <span style="margin-left: 20px">{{ $claim->claimNo }}</span></h5>

    <p>
        The Workshop Manager, <br>
        {{ \App\Garage::where(['id'=>$claim->garageID])->first()->name }}
        <br>
        <u>{{ \App\Garage::where(['id'=>$claim->garageID])->first()->location }}</u>
    </p>

    <p>Dear Sir,</p>
    <p><strong>RE: ACCIDENT TO {{ $claim->vehicleRegNo }} ON {{ $claim->intimationDate }}
            <br> &nbsp; &nbsp;&nbsp; INSURED: {{ isset($claim->customer->firstName) ? $claim->customer->firstName : ''}}
            {{isset($claim->customer->middleName) ? $claim->customer->middleName : ''}}
            {{isset($claim->customer->lastName) ? $claim->customer->lastName  : ''}}</strong>
    </p>

    <p>Please release the above-mentioned vehicle to the insured and/or their authorized representative after
        re-inspection by the assessor who authorized repairs.</p>

    <p>Kindly have the insured sign a satisfaction note, which please forward to us together with the repair invoice for
        our consideration.<p>

    <p>Yours faithfully,</p>

    <p>
        <strong>{{ isset(Auth::user()->firstName) ? Auth::user()->firstName : '' }}
            {{ isset(Auth::user()->middleName) ? Auth::user()->middleName : '' }}
            {{ isset(Auth::user()->lastName) ? Auth::user()->lastName : '' }}<br />
            <u>{{ $role }}</u><br />
            <u>TEL 0709901537</u><br />
        </strong>
    </p>

    <p>
        <strong>Note:</strong><br><br>
        You will retain the scrap metal as per the agreed terms.

    </p>
    <div style="text-align: right; padding-right:10px;padding-bottom:10px;">
        <a data-id="{{\App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id']}}"
           class="waves-effect waves-light btn fetch-assessments">{{"<< Back"}}</a>
        <a id="triggerSendReleaseLetter" data-target="releaseLetter"
           class="btn teal darken-2">Release</a>
    </div>
    <div class="row">
        <div class="col s2"></div>
        <div class="col s4">
            <!-- Modal Structure -->
            <div id="releaseLetter" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="#" class="modal-action modal-close float-right"><i
                                class="material-icons">close</i></a>
                    </div>
                    <div class="modal-body clearfix">
                        <div class="row">
                            <div class="input-field col m12 s12">
                                <input placeholder="Enter garage Email" type="text" name="email" id="email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m8 s12">
                            </div>
                            <div class="input-field col m4 s12">
                                <a href="#" id="emailReleaseletter" data-id="{{$claim->id}}" class="btn blue lighten-2 waves-effect emailReleaseletter">Send</a>
                                <a href="#"
                                   class="modal-action modal-close btn red lighten-2 waves-effect">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s2"></div>
    </div>
</div>
@include('_partials.settings')
@include('_partials.footer')
