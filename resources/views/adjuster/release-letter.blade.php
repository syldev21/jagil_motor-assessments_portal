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
    <div style="text-align: right; width: 90%; margin-right: 0%">
        <img src="{{ asset('images/logo/jubilee_logo.png') }}" class="content-group mt-5" alt="" style="width: 170px; height: 50px;">
        <h4 style="text-align: right; color: blue"><b>Allianz Insurance Company of Kenya Limited</b></h4>
    </div>
    <h5>{{ Carbon\Carbon::now()->format('l, F d, Y') }}</h5>


    <h5>
        The Workshop Manager, <br>
        {{ \App\Garage::where(['id'=>$claim->garageID])->first()->name }}
        <br>
        <u>{{ \App\Garage::where(['id'=>$claim->garageID])->first()->location }}</u>
    </h5>

    <p>Dear Sir,</p>
        <h5>OUR CLAIM No. {{ $claim->claimNo }}</h5>
        <h5>REPAIRS TO M/V {{ $claim->vehicleRegNo }}</h5>
        <h5>INSURED: {{isset($claim->customer->firstName) ? $claim->customer->firstName : ''}}
            {{isset($claim->customer->middleName) ? $claim->customer->middleName : ''}}
            {{isset($claim->customer->lastName) ? $claim->customer->lastName  : ''}}</h5>

        <p>Please release the above vehicle to the insured or her authorized representative when the repairs are complete but subject to the following conditions;</p>
        <div style="margin-left: 7%; font-weight: bold">
        <p>That the vehicle is repaired as per the assessor’s report and the assessors have re-inspected it.</p>
        <p>The client feed back form here below is filled, signed  by the client and sent to us with your invoice and satisafaction note.</p>
        </div>
        <p>On a scale of 1 (Unsatisfied/Bad) to 10 (Very satisfied/ Good), I would rate Allianz Insurance company of Kenya Limited services as:</p>
        <div class="container" style="width: 90%">
            <table style="border:1px solid black; margin-right: 5%">
            <tr>
                <th colspan="2" style="border:1px solid black"></th>
                <th style="border:1px solid black">1</th>
                <th style="border:1px solid black">2</th>
                <th style="border:1px solid black">3</th>
                <th style="border:1px solid black">4</th>
                <th style="border:1px solid black">5</th>
                <th style="border:1px solid black">6</th>
                <th style="border:1px solid black">7</th>
                <th style="border:1px solid black">8</th>
                <th style="border:1px solid black">9</th>
                <th style="border:1px solid black">10</th>
            </tr>
            <tr>
                <td style="border:1px solid black">a</td>
                <td style="border:1px solid black">Overall speed in claims processing</td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
            </tr>
            <tr>
                <td style="border:1px solid black">b</td>
                <td style="border:1px solid black">Prompt and regular updates on the progress of the claim</td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
            </tr>
            <tr>
                <td style="border:1px solid black">c</td>
                <td style="border:1px solid black">How would you recommend our company to someone </td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black"></td>
            </tr>
        </table>
        </div>
        <div style="margin-left: 7%">
            <p>
                I express the following comments on the services of Allianz Insurance Company of Kenya Limited:
                ……………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………
                ……………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………
                ……………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………
            </p>
        </div>

        <p>
            Do send us your repair bill (less KShs 3,000.00 for the scrap metal) to us for settlement noting to quote our above reference.
        </p>
        <p>
            Do send us your repair bill (less KShs 3,000.00 for the scrap metal) to us for settlement noting to quote our above reference.
        </p>
        <p>
            Please expect your settlement within thirty (30) working days from the date we receive the repair invoice or the re-inspection report, whichever is later.
        </p>

        <p>Yours faithfully,</p>
        <div class="container" style="width: 100%">
            <div style="width: 33%; float: left;">
                <img src="{{'images/e_signatures/'.auth()->user()->signature }}" width="20%">
    <p>
        <strong>{{ isset(Auth::user()->firstName) ? Auth::user()->firstName : '' }}
            {{ isset(Auth::user()->middleName) ? Auth::user()->middleName : '' }}
            {{ isset(Auth::user()->lastName) ? Auth::user()->lastName : '' }}<br />
            <u>{{ $role }}</u><br />
            Claims Department
            <br />
        </strong>
    </p>
            </div>
            <div style="width: 34%; float: left;">
                <b>Signature.............................</b>
    <br>
    <br>
    <p>
        <strong>
            Garage Representative
        </strong>
    </p>
            </div>
            <div style="width: 33%; float: right;">
                <b>Signature.............................</b>
    <br>
    <br>
    <p>
        <strong>
            Insured Representative
        </strong>
    </p>
            </div>
        </div>
    </div>
    <?php
    $garageEmail = \App\Garage::where(['id'=>$claim->garageID])->first()->email
    ?>
    <div style="text-align: right; padding-right:10px;padding-bottom:10px;">
        <a data-id="{{\App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id']}}"
           class="waves-effect waves-light btn fetch-assessments"><i class="material-icons">arrow_back</i>Back</a>
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
                                <input value="{{$garageEmail}}" type="text" name="email" id="email" disabled>
                                <label for="email" class="active">Confirm Email</label>
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
