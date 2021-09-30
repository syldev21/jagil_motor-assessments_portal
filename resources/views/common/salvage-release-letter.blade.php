@include('_partials.header')
@include('_partials.navbar')
@include('_partials.sidebar')
<div id="main" style="margin-left:30px;">
    <div class="row">
        <div class="col s8"></div>
        <div class="col s4">
            <button type="button" class="btn teal float-right" onclick="printDiv()"><i class="material-icons" style="font-size: 2em;">local_printshop</i></button>
        </div>
    </div>
    <div id="printableArea">

        <div style="text-align: center">
            <img src="{{ asset('images/logo/jubilee_logo.png') }}" class="content-group mt-5" alt="" style="width: 170px;">
        </div>
        <h5>DATE: {{ Carbon\Carbon::now()->format('l jS \of F, Y') }}</h5>
        <div style="font-weight: bold;">
        <h5>OUR REF: <span style="margin-left: 20px">{{ $salvageRegister->claim->claimNo }},</span></h5>

        <p>
            The Workshop Manager, <br>
            {{$salvageRegister->location}}
            <br>
        </p>
        </div>

        <p>Dear Sir,</p>
        <p><strong>RE: ACCIDENT INVOLVING {{ $salvageRegister->claim->vehicleRegNo }} ON {{ $salvageRegister->claim->intimationDate }}</strong></p>
        <p>The above matter refers.</p>
        <p>
            Please release the above-mentioned vehicle to : <strong>{{$salvageRegister->vendor->fullName}}, I.D NO {{$salvageRegister->vendor->idNumber}}, CELL: {{$salvageRegister->vendor->MSISDN}} </strong> or his authorized representative.
        </p>

        <p>Kindly scan back a signed copy of this letter confirming the buyer has picked the salvage.</p>

        <p><strong style="text-decoration: underline;">CLAIMS SERVICE SUPERVISOR</strong><p>
        <ul>
            <li>Logbook No</li>
            <li>Transfer No</li>
            <li>Key</li>
        </ul>
        <p>NAME:  ................................................................</p>
        <p>ID.NO: ................................................................</p>
        <p>KRA PIN NO:  ..........................................................</p>
        <p>DATE...................................................................</p>
        <p>SIGN...................................................................</p>
        <p>Yours faithfully,</p>

        <p>
            <br><strong>{{ isset(Auth::user()->firstName) ? Auth::user()->firstName : '' }}
                {{ isset(Auth::user()->middleName) ? Auth::user()->middleName : '' }}
                {{ isset(Auth::user()->lastName) ? Auth::user()->lastName : '' }}</strong><br/>
                Recoveries Officer <br/>
                Claims Department <br/>
                Jubilee Allianz General Insurance (K) Limited <br/>
                Allianz Plaza, 96 Riverside Drive,
        </p>
    </div>
</div>
@include('_partials.settings')
@include('_partials.footer')
