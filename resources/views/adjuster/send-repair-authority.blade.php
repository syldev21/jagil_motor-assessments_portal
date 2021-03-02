<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>


        ul {
            list-style: none;
            align-content: center;
            text-align: center;
            line-height: 50%;
            text-align:justify;
            align-content: center;
            align-items: center;
        }

        li {
            float:left;
            margin-left: 50px;

            margin-right: 50px;
            font-size: 16px





        }
        #cssTable td
        {
        text-align: center;
        vertical-align: middle;
        }

        table {
            width: 100%
        }

        table td {
            height: 25px;
            border-collapse: collapse;
            border-top: 1px solid black;

        }
        /* #trLast td {
             border-collapse: collapse;
            border-top: 1px solid rgb(8, 8, 8);
             } */
    </style>
</head>

<body>


    <div >



        <div style="text-align: center">
            <img src="{{ public_path('images/logo/jubilee_logo.png') }}" class="content-group mt-10" alt=""
                style="width: 120px;">
        </div>
        <div style="text-align: center; line-height: 50%;">
            <p>JUBILEE INSURANCE IN-HOUSE ASSESSORS REPORT</p>
            <p>PRIVATE AND CONFIDENTIAL</p>
            <p>MOTOR ASSESSMENT REPORT</p>
        </div>

        <div style="text-align: center">
            <p style="line-height: 200%">
                This report is issued without prejudice, in respect of cause, nature and
                extent
                of loss/damage and subject to the terms and conditions of the Insurance
                Policy.
            </p>
        </div>


        <ul text-align: center>
            <li>Policy Number: <h6>
                    {{$assessment['claim']['policyNo']}}</h6>
            </li>
            <li>Adjuster: <h6>
                    {{$adjuster->firstName." ".$adjuster->lastName}}</h6>
            </li>
            <li>Insured: <h6>{{$insured['fullName']}}</h6>
            </li>
            <li>Claim Number: <h6>{{$assessment['claim']['claimNo']}}
                </h6>
            </li>
        </ul>

        <table style="margin-left: auto; margin-right:auto;">
            <thead>

                <tr>

                    <th style="padding-right:150px;">VEHICLE
                        PARTICULARS</th>

                    <th style="padding-right:200px;">LOGBOOK</th>

                </tr>

            </thead>

            <tbody>

                <tr>
                    <td style="padding-left:300px;">Registered No.</td>

                    <td>{{$assessment['claim']['vehicleRegNo']}}</td>

                </tr>

                <tr>
                    <td style="padding-left:300px;">Year of manufacture </td>

                    <td>{{$assessment['claim']['yom']}} </td>

                </tr>

                <tr>
                    <td style="padding-left:300px;">Chassis No. </td>

                    <td> {{$assessment['claim']['chassisNumber']}} </td>

                </tr>

                <tr>
                    <td style="padding-left:300px;">Make</td>

                    <td>{{$carDetail->makeName}} </td>

                </tr>

                <tr>
                    <td style="padding-left:300px;">Model</p>
                    </td>

                    <td>{{$carDetail->modelName}} </td>

                </tr>

            </tbody>


        </table>
        <br />
        <table style="margin-left: 20px;">

            <thead>

                <tr>

                    <th class="text-uppercase"><strong>Driver
                            Particulars</strong></th>

                </tr>

            </thead>

            <tbody>

                <tr>
                    <td>Name of Driver: {{ $insured['fullName'] }}</td>
                </tr>


            </tbody>


        </table>
        <br />
        <table id="cssTable" style="margin-left: 20px; margin-right:20px;">

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

                    <td>{{ \App\Garage::where(['id'=>$assessment['claim']['garageID']])->first()->location }}
                    </td>

                </tr>

                <tr>
                    <td>Date of Allotment of Assessment</td>

                    <td>

                        {{ date('l jS F Y h:i:s A', strtotime($assessment['claim']['dateCreated'])) }}

                    </td>

                </tr>

                <tr>
                    <td>Date & Time of Assessment</td>
                    <td>{{ date('l jS F Y h:i:s A', strtotime($assessment['dateCreated'])) }}
                    </td>
                </tr>


            </tbody>


        </table>


        <div style="margin-left: 50px;">
            <h6 style="font-size: 15px;">Cause & Nature of Accident</h6>
            {!! $assessment['cause'] !!}
        </div>


        <ul class="list-unstyled">
            <li class="col s4">Sum Insured: {{$assessment['claim']['sumInsured']}}
            </li>
            <li class="col s4">PAV : {{$assessment['pav']}}</li>
            <li class="col s4">Excess: {{$assessment['claim']['excess']}}</li>
        </ul>

    </div>
    <div style="margin-left: 200px;">

        <h5>Assessment Sheet</h5>

    </div>
    <div class="row">
        <div class="col s12">
            <table id="cssTable" style="width:1000px; margin-left: 20px;">

                <thead>

                    <tr>

                        <th  class="col-sm-1 text-bold">Quantity</th>

                        <th class="col-sm-1 text-bold">Repair</th>

                        <th class="col-sm-1 text-bold">Replace</th>

                        <th class="col-sm-3 text-bold">Part</th>

                        <th class="col-sm-1 text-bold">Unit</th>

                        <th class="col-sm-1 text-bold">Contribution %</th>

                        <th class="col-sm-1 text-bold">Disc</th>

                        <th class="col-sm-2 text-bold">Total</th>

                        <th class="col-sm-2 text-bold">Remarks</th>
                    </tr>


                </thead>

                <tbody>

                    @foreach($assessmentItems as $assessmentItem)

                    <tr id="trLast">

                        <td>{{ $assessmentItem['quantity'] }}</td>

                        <td>@if($assessmentItem['category'] ==
                            \App\Conf\Config::$JOB_CATEGORIES['REPAIR']['ID']) Y @else
                            @endif</td>

                        <td>@if($assessmentItem['category'] ==
                            \App\Conf\Config::$JOB_CATEGORIES['REPLACE']['ID']) Y @else
                            @endif</td>

                        <td>{{ $assessmentItem['part']['name'] }}</td>

                        <td>{{ number_format($assessmentItem['cost']) }}</td>

                        <td>{{ $assessmentItem['contribution'] }}</td>

                        <td>{{ $assessmentItem['discount'] }}</td>

                        <td>{{ number_format($assessmentItem['total']) }}</td>

                        <td>{{ $assessmentItem['remark']['name'] }}</td>

                    </tr>

                    @endforeach

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-bold">Total parts cost</td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format(\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('total')) }}
                        </td>
                        <td></td>
                    </tr>
                    {{--
                                                    @if($assessment['assessmentTypeID'] == \App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td style="font-weight: bold;">Less Markup</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{ number_format(round(\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('total') * \App\Conf\Config::MARK_UP)) }}
                    </td>
                    </tr>
                    @endif --}}
                    <?php
                                                                $jobValue = 0;
                                                                ?>
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
                        <td></td>
                    </tr>
                    <?php
                                                                $jobValue += $jobDetail['cost'];

                                                                ?>
                    @endforeach

                    @if($assessment['assessmentTypeID'] != 2)

                    @if($assessment['dateCreated'] >
                    \App\Conf\Config::VAT_REDUCTION_DATE)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-bold">Sum Total</td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format(($assessment['totalCost']) - round(($assessment['totalCost']*\App\Conf\Config::CURRENT_VAT)/\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE)) }}
                        </td>
                        <td></td>
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
                        <td>{{ number_format(round(($assessment['totalCost']*App\Conf\Config::CURRENT_VAT)/\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE)) }}
                        </td>
                        <td></td>
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
                        <td>{{ number_format(($assessment['totalCost']) - round(($assessment['totalCost']*\App\Conf\Config::VAT)/\App\Conf\Config::TOTAL_PERCENTAGE)) }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="">{{\App\Conf\Config::VAT_PERCENTAGE}} VAT</td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format(round(($assessment['totalCost']*\App\Conf\Config::VAT)/\App\Conf\Config::TOTAL_PERCENTAGE)) }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif
                    @endif

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        @if($assessment['assessmentTypeID'] ==
                        App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])
                        <td class="text-bold">Subtotal</td>
                        @else
                        <td class="text-bold">Grand Total</td>
                        @endif
                        <td></td>
                        <td></td>
                        @if($assessment['assessmentTypeID'] ==
                        App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])
                        <td>{{ number_format(round((\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('total')*0.9)+$jobValue)) }}

                            @else
                        <td>
                            @if(isset($assessment['totalChange']) &&
                            isset($priceChange->finalApprovedAt))
                            {{ number_format($assessment['totalChange']) }}
                            @else
                            {{ number_format($assessment['totalCost']) }}
                            @endif
                        </td>
                        @endif
                        <td></td>
                        <td></td>
                    </tr>
                    @if($assessment['assessmentTypeID'] ==
                    App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="">Scrap</td>
                        <td></td>
                        <td></td>
                        <td>{{ $assessment['scrapValue'] }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="">Grand Total</td>
                        <td></td>
                        <td></td>
                        <td> {{ number_format(round((\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('total')*0.9)+$jobValue - $assessment['scrapValue']))}}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif


                </tbody>


            </table>

        </div>
    </div>
    <div class="row">
        <div class="col s12">
            @if($assessment['assessmentTypeID'] == 1)

            <div class="">

                <h5 style="margin-left: 20px; font-size:15px;">Any Other Comments</h5>

                <div style="padding-left: 20px;">
                    <p>We :
                        {{ \App\Garage::where(['id'=>$assessment['claim']['garageID']])->first()->name }}
                        agree to abide by the above estimate/assessment report.</p>

                    <p>No further charges will be raised unless agreed in writing by
                        the assessor.</p>

                    <p>Should further damages be seen during repairs assessor will be
                        notified immediately. </p>

                    <p>Upon completion of the repairs the vehicle will be released
                        subject to re-inspection.</p>
                </div>

                <div style="padding-left: 20px;">
                    <p>COMPLETION SUBJECT TO AVAILABILITY OF PARTS</p>

                    <p>For: JUBILEE INS. CO</p>

                    {{-- <p>Assessor: {{ \App\User::where('id', $assessment['userID'])->first()->name }}
                    </p>--}}

                    <p>Date: {{ date('l jS F Y', strtotime($assessment['dateCreated'])) }}
                    </p>
                </div>


            </div>




            @else

            <div class="col-md-12 content-group" style="padding-left: 20px;">

                <p>For: JUBILEE INS. CO</p>

                {{-- <p>Assessor: {{ \App\User::where('id', $assessment['userID'])->first()->name }}
                </p>--}}

                <p>Date: {{ date('l jS F Y', strtotime($assessment['dateCreated'])) }}
                </p>



            </div>

            @endif
            @if($assessment['totalLoss'] != '')

            <div class="col-md-12 content-group" style="padding-left: 20px;">

                <h4>PAV: {{ number_format($assessment['pav']) }}</h4>

                <h4>Salvage: {{ number_format($assessment['salvage']) }}</h4>

                <h4>Total Loss: {{ number_format($assessment['totalLoss']) }}</h4>



            </div>

            @endif
        </div>
    </div>
    <div class="row" style="padding-left: 20px;">
        <div class="col s12">
            <br />
            <p><b>Assessor : {{$assessor->firstName}} {{$assessor->lastName}}</b></p>
        </div>
    </div>
    <div class="row" style="padding-left: 20px;">
        <div class="col s12">
            <h5 class="underline">Notes</h5>

            <p>{!! $assessment['note'] !!}</p>

            <p>Assessed By: {{$assessor->firstName}} {{$assessor->lastName}}</p>
        </div>
    </div>
    <div style="padding-left:20px;">
        @foreach($documents->chunk(4) as $chunk)
        <div class="row">
            @foreach($chunk as $document)
            <?php
              if ($document['isResized'] == 1) {
                   $path = 'thumbnail';
                      } else {
                          $path = 'documents';
                               }
                  ?>
            <div style="margin-left: 5px; display:inline">
                <a href="{{url($path.'/'.$document['name']) }}">
                    <img width="200px;" class="responsive-img" src="{{public_path($path.'/'.$document['name']) }}">
                </a>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>


    </div>
</body>

</html>
