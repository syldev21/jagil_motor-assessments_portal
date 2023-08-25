<html>


<div class="">

</div>
<div>
    <hr style="position: relative; top: 10px; line-height: 0">
    <img   src="{{ public_path('images/logo/jubilee_logo.png') }}" style="width: 150px; height: 50px; position: relative; margin-left: 40%; margin-top: 1px">
</div>
<div class="">

</div>
<div class="">
    <div class="">
        <h6 style="font-weight: bold;">“WITHOUT PREJUDICE”</h6>
        <h7 style="font-size: 12px"><span >OUR REF: </span>{{$claim->claimNo}}</h7>
        <br>
        <h7 style="font-size: 12px"><span style="">YOUR REF: </span>TBA</h7>
        <p style="font-size: 12px">
            <?php
            echo date('d/m/Y');
            ?>
        </p>
            <br/>
            <h5 style="font-size: 12px">{{$company->name??''}}</h5>
            <h5 style="font-size: 12px">{{$company->building??''}}</h5>
            <h5 style="font-size: 12px">{{$company->stricty??''}}</h5>
        <h5>{{$company->city??''}}.</h5>
        <br/>
        <h6 style="font-size: 12px"><u>Dear Sirs,</h6>
        <h5 style="font-size: 12px"><u>ACCIDENT ON {{explode(" ", $claim->loseDate)[0]}}</u></h5>
        <h5 style="font-size: 12px"><u>YOUR INSURED/INSURED’S DRIVER: {{$claim->thirdPartyDriver}} REG
                NO. {{$claim->thirdPartyVehicleRegNo}}</u></h5>
        <h5 style="font-size: 12px"><u>OUR INSURED: {{$claim->customer->fullName}} REG.
                NO. {{$claim->vehicleRegNo}}</u></h5>
    </div>

</div>
<div class="">
    <div class="">
        <p style="line-height: 100%; ">
            We refer to the above accident. We presume by now your insured has reported
            the same to you. Your insured’s driver was the cause of this accident and
            the police blamed him for the same.
        </p>
        <p style="line-height: 100%; ">
            We hold you liable for the loss incurred as a result of the said accident
            which we expended sums in making good the damages suffered by our insured to
            the tune of Ksh.
            @if($assessment->assessmentTypeID == \App\Conf\Config::ASSESSMENT_TYPES["TOTAL_LOSS"])
                {{$assessment->pav}}
            @else
                @if(isset($assessment->totalCost))
                    {{isset($assessment->totalChange) ? number_format($assessment->totalChange) : number_format($assessment->totalCost) }}
                @elseif(isset($assessment->totalLoss))
                    {{isset($assessment->totalChange) ? number_format($assessment->totalChange) : number_format($assessment->totalCost) }}

                @endif

            @endif

            /-
        </p>
        <p style="line-height: 100%; ">
            We hold you liable for the loss we suffered in making good damages sustained
            by our insured. Kindly let us have your admission of liability within 14
            days from the date hereof.
        </p>
        <p style="line-height: 100%; ">
            In default we shall assume you are not interested in an amicable settlement
            and continue to advise our lawyers to serve your insured with summons.
        </p>

        <h5 style="font-size: 12px">Yours faithfully,</h5>
        @php
        $auth_user = auth()->user();
        @endphp
        <img src="{{ public_path('images/e_signatures/'.$auth_user->signature) }}" width="10%">
        <h5 style="font-size: 12px">{{$auth_user->name}}</h5>
        <h5 style="font-size: 12px">Claims Adjuster-Claims Department</h5>
        <h5 style="font-size: 12px">CC. Miriam Maina</h5>
        <h5 style="font-size: 12px">Recovery Officer - Jubilee Allianz General Insurance (K) Limited</h5>
    </div>

</html>
