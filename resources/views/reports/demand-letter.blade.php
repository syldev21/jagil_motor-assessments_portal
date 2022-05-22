<html>


<div class="">

</div>
<div>
    <hr style="position: relative; top: 10px; line-height: 0">
    <img   src="{{ public_path('images/logo/jubilee_logo.png') }}" style="width: 150px; height: 50px; position: relative; margin-left: 40%; margin-top: 1px">
    {{--                                <img src="{{ public_path("storage/images/".$user->profile_pic) }}" alt="" style="width: 150px; height: 150px;">--}}
</div>
<div class="">

</div>
<div class="">
    <div class="">
        <h3 style="font-weight: bold;">“WITHOUT PREJUDICE”</h3><br/>
        <h4><span style="font-weight: bold;">OUR REF: </span>{{$claim->claimNo}}</h4>
        <h4><span style="font-weight: bold;">YOUR REF: </span>TBA</h4>
        <p>
            <?php
            echo date('d/m/Y');
            ?>
        </p>
        {{--                                <br/>--}}
        {{--                                <h5>{{$company->name}}</h5>--}}
        {{--                                <h5>{{$company->building}}</h5>--}}
        {{--                                <h5>{{$company->stricty}}</h5>--}}
        <h5>{{$company->city}}.</h5>
        <br/>
        <h6>Dear Sirs,</h6>
        <h5><u>ACCIDENT ON {{explode(" ", $claim->loseDate)[0]}}</u></h5>
        <h5><u>YOUR INSURED/INSURED’S DRIVER: {{$claim->thirdPartyDriver}} REG
                NO. {{$claim->thirdPartyVehicleRegNo}}</u></h5>
        <h5><u>OUR INSURED: {{$claim->customer->fullName}} REG.
                NO. {{$claim->vehicleRegNo}}</u></h5>
    </div>

</div>
<div class="">
    <div class="">
        <p style="line-height: 200%">
            We refer to the above accident. We presume by now your insured has reported
            the same to you. Your insured’s driver was the cause of this accident and
            the police blamed him for the same.
        </p>
        <p style="line-height: 100%">
            We hold you liable for the loss incurred as a result of the said accident
            which we expended sums in making good the damages suffered by our insured to
            the tune of Ksh.
            @if(isset($assessment->totalCost))
                {{isset($assessment->totalChange) ? number_format($assessment->totalChange) : number_format($assessment->totalCost) }}
            @elseif(isset($assessment->totalLoss))
                {{isset($assessment->totalChange) ? number_format($assessment->totalChange) : number_format($assessment->totalCost) }}
            @endif

            /-
        </p>
        <p style="line-height: 100%">
            We hold you liable for the loss we suffered in making good damages sustained
            by our insured. Kindly let us have your admission of liability within 14
            days from the date hereof.
        </p>
        <p style="line-height: 100%">
            In default we shall assume you are not interested in an amicable settlement
            and continue to advise our lawyers to serve your insured with summons.
        </p>

        <h5>Yours faithfully,</h5>
        <img src="{{ public_path('images/e_signatures/'.auth::user()->signature) }}" width="10%">
        <h5>{{auth()->user()->name}}</h5>
        <h5>Claims Adjuster-Claims Department</h5>
        <h5>CC. Miriam Maina</h5>
        <h5>RECOVERY OFFICER - JUBILEE ALLIANZ GENERAL INSURANCE (K) LIMITED</h5>
    </div>

</html>
