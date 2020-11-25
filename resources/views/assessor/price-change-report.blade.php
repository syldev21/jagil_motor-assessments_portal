
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
                                    <h4 class="card-title float-left">Price Change Report</h4>
                                </div>
                                <div class="divider"></div>
                                <div class="row">
                                    <div class="col s5">

                                    </div>
                                    <div class="col s2">
                                        <img class="responsive-img" src="{{url('images/logo/jubilee_logo.png') }}">
                                    </div>
                                    <div class="col s5">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s2">

                                    </div>
                                    <div class="col s8 center-align">
                                        <h5>JUBILEE INSURANCE IN-HOUSE ASSESSORS REPORT</h5>
                                        <h6>PRIVATE AND CONFIDENTIAL</h6>
                                        <h6>MOTOR ASSESSMENT REPORT (PRICE CHANGE)</h6>
                                    </div>
                                    <div class="col s2">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12 center-align">
                                        <p style="line-height: 200%">
                                            This report is issued without prejudice, in respect of cause, nature and extent
                                            of loss/damage and subject to the terms and conditions of the Insurance Policy.
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <ul class="list-unstyled center-align">
                                            <li class="col s3">Policy Number: <h6>{{$assessment['claim']['policyNo']}}</h6>
                                            </li>
                                            <li class="col s3">Adjuster: <h6>{{isset($adjuster->firstName) ? $adjuster->firstName : ''}} {{isset($adjuster->lastName) ? $adjuster->lastName : ''}}</h6></li>
                                            <li class="col s3">Insured: <h6>{{$insured['fullName']}}</h6></li>
                                            <li class="col s3">Claim Number: <h6>{{$assessment['claim']['claimNo']}}</h6>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <table class="table table-bordered">
                                            <thead>

                                            <tr>

                                                <th class="text-uppercase"><strong>Vehicle Particulars</strong></th>

                                                <th><strong>Logbook</strong></th>

                                            </tr>

                                            </thead>

                                            <tbody>

                                            <tr>
                                                <td>Registered No.</td>

                                                <td>{{$assessment['claim']['vehicleRegNo']}}</td>

                                            </tr>

                                            <tr>
                                                <td>Year of manufacture</td>

                                                <td>{{$assessment['claim']['yom']}}</td>

                                            </tr>

                                            <tr>
                                                <td>Chassis No.</td>

                                                <td>{{$assessment['claim']['chassisNumber']}}</td>

                                            </tr>

                                            <tr>
                                                <td>Make</td>

                                                <td>{{$assessment['claim']['carMakeCode']}}</td>

                                            </tr>

                                            <tr>
                                                <td>Model</td>

                                                <td>{{$assessment['claim']['carModelCode']}}</td>

                                            </tr>

                                            </tbody>


                                        </table>
                                        <br/>

                                        <div class="row">
                                            <div class="col s12">
                                                <h5>Assessment Sheet</h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                <table class="table table-lg">

                                                    <thead>

                                                    <tr>

                                                        <th class="center-align col-sm-1 text-bold">Quantity</th>

                                                        <th class="center-align col-sm-1 text-bold">Repair</th>

                                                        <th class="col-sm-1 text-bold center-align">Replace</th>

                                                        <th class="col-sm-3 text-bold center-align">Part</th>

                                                        <th class="col-sm-1 text-bold center-align">Unit</th>

                                                        <th class="col-sm-1 text-bold center-align">Contribution %</th>

                                                        <th class="col-sm-1 text-bold center-align">Disc</th>

                                                        <th class="col-sm-2 text-bold center-align">Total</th>
                                                        <th class="col-sm-2 text-bold center-align">Price Change</th>
                                                        <th class="col-sm-2 text-bold center-align">Price Diff</th>

                                                        <th class="col-sm-2 text-bold center-align">Remarks</th>
                                                    </tr>


                                                    </thead>

                                                    <tbody>
                                                    @foreach($assessmentItems as $item)

                                                        <tr>

                                                            <td>{{ $item->quantity }}</td>

                                                            <td>@if($item['category'] == \App\Conf\Config::$JOB_CATEGORIES['REPLACE']['ID']) Y @else @endif</td>

                                                            <td>@if($item['category'] == \App\Conf\Config::$JOB_CATEGORIES['REPAIR']['ID']) Y @else @endif</td>

                                                            <td>{{ $item['part']['name'] }}</td>

                                                            <td>{{ number_format($item['cost']) }}</td>

                                                            <td>{{ $item['contribution'] }}</td>

                                                            <td>{{ $item['discount'] }}</td>

                                                            <td>{{ number_format($item['total']) }}</td>

                                                            <td>

                                                                @if($item->current != '')

                                                                    {{ number_format($item->current) }}

                                                                @endif

                                                            </td>

                                                            <td>

                                                                @if($item->current != '')

                                                                    {{ number_format($item->difference) }}

                                                                @endif

                                                            </td>

                                                            <td>{{ $item['remark']['name'] }}</td>



                                                        </tr>



                                                    @endforeach

                                                        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>

                                                        <tr><td></td><td></td><td></td><td class="text-bold">Total parts cost</td><td></td><td></td><td></td><td></td><td>{{ number_format(\App\Assessment::where('id', $assessment['id'])->sum('totalChange')) }}</td><td></td></tr>

                                                        @if($assessment['assessmentTypeID'] == \App\Conf\Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE'])
                                                            @if($assessment['dateCreated'] > App\Conf\Config::VAT_REDUCTION_DATE)

                                                            <tr><td></td><td></td><td></td><td class="text-bold">+ {{\App\Conf\Config::CURRENT_VAT_PERCENTAGE}} VAT</td><td></td><td></td><td></td><td></td><td>{{ number_format((\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('current')) * (\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE/\App\Conf\Config::INITIAL_PERCENTAGE)) }}</td><td></td></tr>
                                                            @else
                                                                <tr><td></td><td></td><td></td><td class="text-bold">+ {{App\Conf\Config::VAT_PERCENTAGE}} VAT</td><td></td><td></td><td></td><td></td><td>{{ number_format((\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('current')) * (\App\Conf\Config::TOTAL_PERCENTAGE/\App\Conf\Config::INITIAL_PERCENTAGE)) }}</td><td></td></tr>
                                                            @endif

                                                        @elseif($assessment['assessmentTypeID'] == \App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])

                                                            <tr><td></td><td></td><td></td><td class="text-bold">Less Markup</td><td></td><td></td><td></td><td></td><td>{{ number_format((\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('current')) * \App\Conf\Config::MARK_UP) }}</td><td></td></tr>

                                                        @endif

                                                        <tr><td></td><td></td><td></td><td class="text-bold">Initial Grand Total</td><td></td><td></td><td></td><td></td><td>{{ number_format($assessment['totalCost']) }}</td><td></td></tr>

                                                        <tr><td></td><td></td><td></td><td class="text-bold">Additional Amount</td><td></td><td></td><td></td><td></td><td>{{ number_format($assessment['totalChange'] - $assessment['totalCost']) }}</td><td></td></tr>

                                                        <tr><td></td><td></td><td></td><td class="text-bold">Current Grand Total</td><td></td><td></td><td></td><td></td><td>{{ number_format($assessment['totalChange']) }}</td><td></td></tr>


                                                    </tbody>


                                                </table>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                <p>Assessed By: {{$assessor->firstName}} {{$assessor->lastName}}</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                    </div>
                </div>
            </div>
        </div>
@include('_partials.settings')
@include('_partials.footer')
