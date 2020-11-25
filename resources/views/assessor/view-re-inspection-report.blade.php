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
                                        <h4 class="card-title float-left">Re-Inspection Report</h4>
                                    </div>
                                    <div class="col s2">
                                        <button type="button" class="btn teal float-right" onclick="printDiv()"><i class="material-icons" style="font-size: 2em;">local_printshop</i></button>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div id="printableArea">
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
                                        <h6>MOTOR RE-INSPECTION REPORT</h6>
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
                                            <li class="col s3">Adjuster: <h6>{{$adjuster->firstName." ".$adjuster->lastName}}</h6></li>
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
                                        <table class="table table-condensed table-bordered">

                                            <thead>

                                            <tr>

                                                <th class="text-uppercase"><strong>Driver Particulars</strong></th>

                                            </tr>

                                            </thead>

                                            <tbody>

                                            <tr>
                                                <td>Name of Driver: {{ $insured['fullName'] }}</td>
                                            </tr>


                                            </tbody>


                                        </table>
                                        <br/>
                                        <table class="table table-condensed table-bordered">

                                            <thead>

                                            <tr></tr>

                                            </thead>

                                            <tbody>

                                            <tr>
                                                <td>Date & Time of Intimation</td>

                                                <td>

                                                    {{ date('l jS F Y h:i:s A', strtotime($assessment['claim']['dateCreated'])) }}

                                                </td>

                                            </tr>

                                            <tr>
                                                <td>Place of Assessment</td>

                                                <td>{{ \App\Garage::where(['id'=>$assessment['claim']['location']])->first()->location }}</td>

                                            </tr>

                                            <tr>
                                                <td>Date of Allotment of Assessment</td>

                                                <td>

                                                    {{ date('l jS F Y h:i:s A', strtotime($assessment['claim']['dateCreated'])) }}

                                                </td>

                                            </tr>

                                            <tr>
                                                <td>Date & Time of Assessment</td>
                                                <td>{{ date('l jS F Y h:i:s A', strtotime($assessment['dateCreated'])) }}</td>
                                            </tr>


                                            </tbody>


                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <h6>Cause & Nature of Accident</h6>
                                        {!! $assessment['cause'] !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <ul class="list-unstyled">
                                            <li class="col s4">Sum Insured: {{$assessment['claim']['sumInsured']}}</li>
                                            <li class="col s4">PAV : {{$assessment['pav']}}</li>
                                            <li class="col s4">Excess: {{$assessment['claim']['excess']}}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <h5>Re-inspection Sheet</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <table class="table table-lg">

                                            <thead>

                                            <tr>

                                                <th class="col-sm-1 text-bold">Quantity</th>

                                                <th class="col-sm-3 text-bold">Part</th>

                                                <th class="col-sm-1 text-bold">Unit</th>

                                                <th class="col-sm-1 text-bold">Contribution %</th>

                                                <th class="col-sm-2 text-bold">Total</th>

                                                <th class="col-sm-2 text-bold">Remarks</th>

                                                <th class="col-sm-1 text-bold">Repair</th>

                                                <th class="col-sm-1 text-bold">Replace</th>

                                                <th class="col-sm-1 text-bold">CIL</th>

                                                <th class="col-sm-1 text-bold">Re-Use</th>
                                            </tr>

                                            </thead>

                                            <tbody>

                                            @foreach($assessmentItems as $assessmentItem)

                                                <tr>

                                                    <td>{{ $assessmentItem['quantity'] }}</td>

                                                    <td>{{ $assessmentItem['part']['name'] }}</td>

                                                    <td>{{ number_format($assessmentItem['cost']) }}</td>

                                                    <td>{{ $assessmentItem['contribution'] }}</td>

                                                    <td>{{ number_format($assessmentItem['total']) }}</td>

                                                    <td>{{ $assessmentItem['remark']['name'] }}</td>
                                                    <td>@if($assessmentItem['reInspectionType'] == \App\Conf\Config::$JOB_CATEGORIES['REPAIR']['ID']) <i class="material-icons" style="color: green">check</i> @else <i class="material-icons" style="color: red">clear</i> @endif</td>

                                                    <td>@if($assessmentItem['reInspectionType'] == \App\Conf\Config::$JOB_CATEGORIES['REPLACE']['ID']) <i class="material-icons" style="color: green">check</i> @else <i class="material-icons" style="color: red">clear</i> @endif</td>

                                                    <td>@if($assessmentItem['reInspectionType'] == \App\Conf\Config::$JOB_CATEGORIES['CIL']['ID']) <i class="material-icons" style="color: green">check</i> @else <i class="material-icons" style="color: red">clear</i> @endif</td>

                                                    <td>@if($assessmentItem['reInspectionType'] == \App\Conf\Config::$JOB_CATEGORIES['REUSE']['ID']) <i class="material-icons" style="color: green">check</i> @else <i class="material-icons" style="color: red">clear</i> @endif</td>

                                                </tr>

                                            @endforeach

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-bold">Total parts cost</td>
                                                <td></td>
                                                <td></td>
                                                <td>{{ number_format(\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('total')) }}</td>
                                                <td>-</td>
                                            </tr>

                                            @foreach($jobDetails as $jobDetail)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{$jobDetail['name']}}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ number_format($jobDetail['cost']) }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                            @if($assessment['assessmentTypeID'] != 2)

                                                @if($assessment['dateCreated'] > \App\Conf\Config::VAT_REDUCTION_DATE)
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-bold">Sum Total</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ number_format(($assessment['totalCost']) - round(($assessment['totalCost']*\App\Conf\Config::CURRENT_VAT)/\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE)) }}</td>
                                                        <td></td>
                                                    </tr>

                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="">{{\App\Conf\Config::CURRENT_VAT_PERCENTAGE}}
                                                            VAT
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ number_format(round(($assessment['totalCost']*App\Conf\Config::CURRENT_VAT)/\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE)) }}</td>
                                                        <td></td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-bold">Sum Total</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ number_format(($assessment['totalCost']) - round(($assessment['totalCost']*\App\Conf\Config::VAT)/\App\Conf\Config::TOTAL_PERCENTAGE)) }}</td>
                                                        <td></td>
                                                    </tr>

                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="">{{\App\Conf\Config::VAT_PERCENTAGE}} VAT</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ number_format(round(($assessment['totalCost']*\App\Conf\Config::VAT)/\App\Conf\Config::TOTAL_PERCENTAGE)) }}</td>
                                                        <td></td>
                                                    </tr>
                                                @endif
                                            @endif

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-bold">Grand Total</td>
                                                <td></td>
                                                <td></td>
                                                <td>{{ number_format($assessment['totalCost']) }}</td>
                                                <td></td>
                                            </tr>


                                            </tbody>


                                        </table>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <h5 class="underline">Notes</h5>

                                        <p>{!! $assessment['note'] !!}</p>

                                        <p>Assessed By: {{$assessor->firstName}} {{$assessor->lastName}}</p>
                                    </div>
                                </div>
                                @foreach($documents->chunk(4) as $chunk)
                                    <div class="row">
                                        @foreach($chunk as $document)
                                            <div class="col s3">
                                                <img class="responsive-img" src="{{url('documents/'.$document['name']) }}">
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('_partials.settings')
@include('_partials.footer')
