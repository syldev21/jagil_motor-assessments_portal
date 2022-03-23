@include('_partials.header')
<div id="main" style="margin-left:30px;">
    <div id="printableArea">

        <div style="text-align: center">
            <img src="{{ public_path('images/logo/jubilee_logo.png') }}" class="content-group mt-5" alt="" style="width: 170px;">
        </div>
        <h5>{{ Carbon\Carbon::now()->format('l jS \of F, Y') }}</h5>
        <div style="font-weight: bold;">
            <h5>OUR REF: <span style="margin-left: 20px">{{ $claim->claimNo }},</span></h5>

            <p>
                {{$claim->garage->name}}, <br>
                {!! $claim->garage->location !!}
                <br>
            </p>
        </div>

        <p>Dear Sir,</p>
        <p STYLE="text-decoration: underline;font-weight: bold;"><strong>RE: WINDSCREEN REPLACEMENT AUTHORITY FOR {{ $claim->vehicleRegNo }} <br/> INSURED: {{$claim->customer->fullName}}</strong></p>
        <p>
            The subject motor vehicle is comprehensively insured with us. Kindly replace the Windscreen of the above Motor Vehicle and forward to us your invoice together with ETR for settlement at Ksh.{{$claim->LPOAmount}}
        </p>

        <p>Also, attach the clear photographs of the vehicle after the replacement.</p>
        <p>Call the client on {{$claim->customer->MSISDN}}</p>
        <P>Your assistance to facilitate the same will be highly appreciated.</P>
        <p>Yours faithfully,</p>
        <img src="{{'images/e_signatures/'.Auth::user()->signature }}" width="20%">

        <p>
            <br><strong>{{ isset(Auth::user()->firstName) ? Auth::user()->firstName : '' }}
                {{ isset(Auth::user()->middleName) ? Auth::user()->middleName : '' }}
                {{ isset(Auth::user()->lastName) ? Auth::user()->lastName : '' }}</strong><br/>
            Claims Adjuster<br/>
            Jubilee Allianz General Insurance (K) Limited <br/>
            Allianz Plaza, 96 Riverside Drive,
        </p>
        <br/>
        <h6>BCC: {{$claim->customer->fullName}}</h6>
        <p style="font-size: 11px;">
            Kindly follow up for reinstatement of windscreen cover as it has been utilized for this claim.
        </p>
    </div>
</div>
