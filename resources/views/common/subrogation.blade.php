@include('_partials.header')
@include('_partials.navbar')
@include('_partials.sidebar')
<div id="main">
    <div class="row">

        <div
            class="content-wrapper-before  gradient-45deg-red-pink">
        </div>
        <div class="col s12">
            <div class="container">
                <div class="row">
                    <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s10">
                                        <h4 class="card-title float-left">Subrogation Report</h4>
                                    </div>
                                    <div class="col s2">
                                        <button type="button" class="btn teal float-right" onclick="printDiv()"><i class="material-icons" style="font-size: 2em;">local_printshop</i></button>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div id="printableArea">
                                    <div class="row">
                                <div class="col s4">

                                </div>
                                <div class="col s4">
                                    <img class="responsive-img" src="{{url('images/logo/jubilee_logo.png') }}">
                                </div>
                                <div class="col s4">

                                </div>
                            </div>
                                    <div class="row">
                                        <div class="col s12">
                                            <h5 style="font-weight: bold;">“WITHOUT PREJUDICE”</h5><br/>
                                            <h6><span style="font-weight: bold;">OUR REF: </span>{{$claim->claimNo}}</h6>
                                            <h6><span style="font-weight: bold;">YOUR REF: </span>POL NO: {{$claim->thirdPartyPolicy }}</h6>
                                            <br/>
                                            <p>
                                            <?php
                                            echo date('d/m/Y');
                                            ?>
                                            </p>
                                            <br/>
                                            <h5>{{isset($company->name)?$company->name:""}}</h5>
                                            <h5>{{isset($company->building)?$company->building:""}}</h5>
{{--                                            <h5>{{$company->stricty}}</h5>--}}
                                            <h5>{{isset($company->city)?$company->city:""}}.</h5>
                                            <br/>
                                            <h6>Dear Sirs,</h6>
                                            <br/>
                                            <div style="text-decoration: underline;">
                                            <h5>ACCIDENT ON {{explode(" ", $claim->loseDate)[0]}}</h5>
                                            <h5>YOUR INSURED/INSURED’S DRIVER: {{$claim->thirdPartyDriver}} REG NO. {{$claim->thirdPartyVehicleRegNo}}</h5>
                                            <h5>OUR INSURED: {{$claim->customer->fullName}} REG. NO. {{$claim->vehicleRegNo}}</h5>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12">
                                            <p style="line-height: 200%">
                                                We refer to the above accident. We presume by now your insured has reported the same to you. Your insured’s driver was the cause of this accident and the police blamed him for the same.
                                            </p>
                                            <p style="line-height: 200%">
                                                We hold you liable for the loss incurred as a result of the said accident which we expended sums in making good the damages suffered by our insured to the tune of Ksh.
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
                                            <p style="line-height: 200%">
                                                We hold you liable for the loss we suffered in making good damages sustained by our insured. Kindly let us have your admission of liability within 14 days from the date hereof.
                                            </p>
                                            <p style="line-height: 200%">
                                                In default we shall assume you are not interested in an amicable settlement and continue to advise our lawyers to serve your insured with summons.
                                            </p>

                                                <h5>Yours faithfully,</h5>
                                                <img src="{{'images/e_signatures/'.auth()->user()->signature }}" width="10%">
                                                <h5>{{auth()->user()->name}}</h5>
                                                <h5>Claims Department</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($assessment->assessmentStatusID == \App\Conf\Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id']||$assessment->assessmentStatusID == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                @hasrole(\App\Conf\Config::$ROLES["ADJUSTER"])
                <div class="row">
                    @php

                        $button_text =  $assessment['demandLetterDate'] == NULL && $assessment['subrogationSender'] == NULL ? 'Send ': 'Resend ';
                    @endphp
                        <div class="col s8">
                            <a id="sendSubrogationReport" data-id="{{$assessment['id']}}"
                               class="btn green darken-2">{{$button_text. 'Demand Letter'}}
                            </a>
                        </div>
                    <div class="col s4">

                    </div>
                </div>
                @endhasrole
                @endif
            </div>
        </div>
    </div>
</div>
@include('_partials.settings')
@include('_partials.footer')
