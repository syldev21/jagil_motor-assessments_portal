<!DOCTYPE html>
<html class="loading"
      lang="de"
      data-textdirection="ltr">
<!-- BEGIN: Head-->


<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Jubilee Kenya</title>
    <link rel="apple-touch-icon" href="{{ public_path('images/favicon/jubilee_favicon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href=" {{ public_path('images/favicon/jubilee_favicon.png') }} ">


    <link rel="stylesheet" type="text/css" href=" {{ public_path('vendors/vendors.min.css') }} " media="all">

    <link rel="stylesheet" type="text/css" href=" {{ public_path('css/pages/login.css') }} " media="all">

    <link rel="stylesheet" type="text/css" href=" {{ public_path('css/style.css') }} " media="all">
    <link rel="stylesheet" type="text/css" href=" {{ public_path('css/image-zoom.min.css') }} " media="all">
    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href=" {{ public_path('vendors/animate-css/animate.css') }} " media="all">
    <link rel="stylesheet" type="text/css" href=" {{ public_path('vendors/chartist-js/chartist.min.css') }} " media="all">
    <link rel="stylesheet" type="text/css" href=" {{ public_path('vendors/chartist-js/chartist-plugin-tooltip.css') }} " media="all">
    <!-- END: VENDOR CSS-->
    <!-- BEGIN: Page Level CSS-->
    <!-- Compiled and minified CSS -->
    {{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" media="all">--}}
    <link rel="stylesheet" type="text/css"
          href=" {{ public_path('css/themes/vertical-modern-menu-template/materialize.css') }} ">
    <link rel="stylesheet" type="text/css"
          href=" {{ public_path('css/themes/vertical-modern-menu-template/style.css') }} " media="all">


    <link rel="stylesheet" type="text/css" href=" {{ public_path('css/pages/dashboard-modern.css') }} " media="all">
    <link rel="stylesheet" type="text/css" href=" {{ public_path('css/pages/intro.css') }} " media="all">

    <!-- END: Page Level CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href=" {{ public_path('css/laravel-custom.css') }} " media="all">
    <link rel="stylesheet" type="text/css" href=" {{ public_path('css/custom/custom.css') }} " media="all">
    <link rel="stylesheet" type="text/css" href=" {{ public_path('css/image-uploader.min.css') }} " media="all">
    <link rel="stylesheet" type="text/css" href=" {{ public_path('css/page-loader.css') }} ">
    <!-- END: Custom CSS-->

    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ public_path('vendors/flag-icon/css/flag-icon.min.css') }}" media="all">
    <!-- END: VENDOR CSS-->
    <style type="text/css">
        body {
            font-family: Futura New Bold, sans-serif !important;
            font-size: small !important;
        }

        .btn-flat {
            color: #0a6aa1 !important;
        }
        .ahover{
            background-color: #f1f1f1 !important;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }
        .border-fields {
            border: 1px solid teal !important;
            border-radius: 3px !important;
        }
        #partsTable td,th {
            padding: 0px 5px !important;
            margin: 0px 5px !important;
        }
        #partsTable td input{
            margin:0 !important;
            height:2em !important;
        }
        #totallosetable td,th {
            padding: 0px 5px !important;
            margin: 0px 5px !important;
        }
        #totallosetable tr {
            padding: 0px 5px !important;
            margin: 0px 5px !important;
        }
        #totallosetable td input{
            margin:0 !important;
            height:2em !important;
        }
        .image-uploader{
            border-radius: 5px;
        }
        .image-uploader .uploaded .uploaded-image {
            display: inline-block;
            width: calc(33.3333333% - 1rem); !important;
            padding-bottom: calc(33.3333333% - 1rem); !important;
            height: 0;
            position: relative;
            margin: .5rem;
            background: red; !important;
            cursor: default;
        }
        .hideTotalLose,.hideTotals{
            display: none;
        }
        #authorityToGarage{

        }
        ul.stepper.horizontal .step .step-content .step-actions{
            position: absolute !important;
            bottom:auto !important;
            left: auto !important;
        }
        .table-lg td,th{
            text-align: center !important;
        }
        .spinner-blue-only{
            border-color: white !important;
        }
        .showLoadingButton{
            display: block !important;
        }
        .showActionButton{
            display: block !important;
        }
        .hideLoadingButton{
            display: none !important;
        }
        .hideActionButton{
            display: none !important;
        }
        h5{
            font-size: 1rem !important;
        }
        h6{
            font-size: 0.8rem !important;
        }
        .error{
            color: red !important;
        }
        .hideSubrogation{
            display: none !important;
        }
        ul.sidenav.sidenav-fixed:hover { overflow-y: auto; !important; }
        .collapsible-sub li{
            margin-left: 10px;
            border-left: 3px solid #a71a3b;
        }
        /* label color */
        .input-field label.invalid {
            color: #F44336;
        }
        /* Force PDF page breaks between rows, not mid-row */
        .table tr {
            page-break-inside: avoid;
        }
    </style>
</head>
<!-- END: Head-->
<body>
<div id="main">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="row">
                    <div class="col s12">
                        <div id="printableArea">
                            <div class="row">
                                <div class="col s4">

                                </div>
                                <div class="col s4">
                                    <img class="responsive-img" src="{{ public_path('images/logo/jubilee_logo.png')}}"/>
                                </div>
                                <div class="col s4">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col s2">

                                </div>
                                <div class="col s8 center-align">
                                    <h5>JUBILEE ALLIANZ INSURANCE ASSESSORS REPORT</h5>
                                    <h6>PRIVATE AND CONFIDENTIAL</h6>
                                    <h6>MOTOR ASSESSMENT REPORT</h6>
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

                                            <td>{{$carDetail->makeName}}</td>

                                        </tr>

                                        <tr>
                                            <td>Model</td>

                                            <td>{{$carDetail->modelName}}</td>

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

                                                {{ date('l jS F Y h:i:s A', strtotime($assessment['claim']->intimationDate)) }}

                                            </td>

                                        </tr>

                                        <tr>
                                            <td>Place of Assessment</td>

                                            <td>{{ \App\Garage::where(['id'=>$assessment['claim']['garageID']])->first()->name }}</td>

                                        </tr>

                                        <tr>
                                            <td>Date of Allotment of Assessment</td>

                                            <td>

                                                {{ date('l jS F Y h:i:s A', strtotime($assessment['claim']['dateCreated'])) }}

                                            </td>

                                        </tr>

                                        <tr>
                                            <td>Date & Time of Assessment</td>
                                            <td>{{ date('l jS F Y h:i:s A', strtotime($assessment['assessedAt'])) }}</td>
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
                                    <h5>Assessment Sheet</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <table class="table table-lg">

                                        <thead style="margin-top: 20px;">

                                        <tr>

                                            <th class="col-sm-1 text-bold">Quantity</th>

                                            <th class="col-sm-1 text-bold">Repair</th>

                                            <th class="col-sm-1 text-bold">Replace</th>

                                            <th class="col-sm-3 text-bold">Part</th>

                                            <th class="col-sm-1 text-bold">Unit</th>

                                            <th class="col-sm-1 text-bold">Contribution %</th>

                                            <th class="col-sm-1 text-bold">Disc</th>

                                            <th class="col-sm-2 text-bold">Total</th>
                                            <th class="col-sm-2 text-bold">Price Change</th>

                                            <th class="col-sm-2 text-bold">Price Diff</th>

                                            <th class="col-sm-2 text-bold">Remarks</th>
                                        </tr>


                                        </thead>

                                        <tbody>
                                        @foreach($assessmentItems as $assessmentItem)

                                            <tr>

                                                <td>{{ $assessmentItem['quantity'] }}</td>

                                                <td>@if($assessmentItem['category'] == \App\Conf\Config::$JOB_CATEGORIES['REPAIR']['ID']) Y @else @endif</td>

                                                <td>@if($assessmentItem['category'] == \App\Conf\Config::$JOB_CATEGORIES['REPLACE']['ID']) Y @else @endif</td>

                                                <td>{{ $assessmentItem['part']['name'] }}</td>

                                                <td>{{ number_format($assessmentItem['cost']) }}</td>

                                                <td>{{ $assessmentItem['contribution'] }}</td>

                                                <td>{{ $assessmentItem['discount'] }}</td>

                                                <td>{{ number_format($assessmentItem['total']) }}</td>
                                                <td>
                                                    @if(isset($assessmentItem['current']))
                                                        {{ number_format($assessmentItem['current']) }}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if(isset($assessmentItem['totalDifference']))
                                                        {{ number_format($assessmentItem['totalDifference']) }}
                                                    @endif
                                                </td>

                                                <td>{{ $assessmentItem['remark']['name'] }}</td>

                                            </tr>

                                        @endforeach

                                        <?php
                                        $sumOfTotalItems = \App\Helper\GeneralFunctions::getSumOfTotalItems($assessment['id']);
                                        $markup = $assessment['assessedAt'] > \App\Conf\Config::MARK_UP_CUT_OFF_DATE ? \App\Conf\Config::NEW_MARKUP : \App\Conf\Config::MARK_UP;
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-bold">Total parts cost</td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ number_format($sumOfTotalItems) }}</td>
                                            <td>-</td>
                                        </tr>
                                        @if($assessment['assessmentTypeID'] == \App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'] && $assessment['assessedAt'] < \App\Conf\Config::MARK_UP_CUT_OFF_DATE)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="font-weight: bold;">Less Markup</td>
                                                <td></td>
                                                <td></td>
                                                <td>{{ number_format(round($sumOfTotalItems * $markup)) }}</td>
                                            </tr>
                                        @endif
                                        <?php
                                        $jobValue=0;
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
                                            </tr>
                                            <?php
                                            $jobValue += $jobDetail['cost'];

                                            ?>
                                        @endforeach

                                        @if($assessment['assessmentTypeID'] != 2)
                                            <?php
                                            if (isset($assessment['totalChange']) && isset($priceChange->finalApprovedAt)) {
                                                $totalCostSubjected = $assessment['totalChange'];
                                            } else {
                                                $totalCostSubjected = $assessment['totalCost'];
                                            }
                                            ?>

                                            @if($assessment['claim']->intimationDate >= \App\Conf\Config::VAT_REDUCTION_DATE && $assessment['claim']->intimationDate <= \App\Conf\Config::VAT_END_DATE)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-bold">Sum Total</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ number_format(($totalCostSubjected) - round(($totalCostSubjected*\App\Conf\Config::CURRENT_VAT)/\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE)) }}</td>
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
                                                    <td>{{ number_format(round(($totalCostSubjected*App\Conf\Config::CURRENT_VAT)/\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE)) }}</td>
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
                                                    <td>{{ number_format(($totalCostSubjected) - round(($totalCostSubjected*\App\Conf\Config::VAT)/\App\Conf\Config::TOTAL_PERCENTAGE)) }}</td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="">{{\App\Conf\Config::VAT_PERCENTAGE}} VAT</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ number_format(round(($totalCostSubjected*\App\Conf\Config::VAT)/\App\Conf\Config::TOTAL_PERCENTAGE)) }}</td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @endif
                                        <?php
                                        $scrapValue=0;
                                        ?>
                                        @if($assessment['assessmentTypeID'] ==
                                                        App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'] && $assessment['scrap'] == App\Conf\Config::ACTIVE)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="">Scrap</td>
                                                <td></td>
                                                <td></td>
                                                <td>{{ isset($assessment['scrapValue']) ? '('.$assessment['scrapValue'].')' : 0 }}
                                                </td>
                                                <td></td>
                                            </tr>
                                            <?php
                                            $scrapValue = isset($assessment['scrapValue']) ? $assessment['scrapValue'] : 0;
                                            ?>
                                        @endif
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-bold">Grand Total</td>
                                            <td></td>
                                            <td></td>
                                            @if($assessment['assessmentTypeID'] == App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])
                                                <td>{{ number_format(round(($sumOfTotalItems * $markup)+$jobValue-$scrapValue)) }}
                                            @else
                                                <td>
                                                    @if(isset($assessment['totalChange']) && isset($priceChange->finalApprovedAt))
                                                        {{ number_format($assessment['totalChange']-$scrapValue) }}
                                                    @else
                                                        {{ number_format($assessment['totalCost']-$scrapValue) }}
                                                    @endif
                                                </td>
                                            @endif
                                            <td></td>
                                        </tr>


                                        </tbody>


                                    </table>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    @if($assessment['assessmentTypeID'] == 1)

                                        <div class="">

                                            <h5>Any Other Comments</h5>

                                            <ul class="list-bullet">

                                                <li>We : {{ \App\Garage::where(['id'=>$assessment['claim']['garageID']])->first()->name }} agree to abide by the above estimate/assessment report.</li>

                                                <li>No further charges will be raised unless agreed in writing by the assessor.</li>

                                                <li>Should further damages be seen during repairs assessor will be notified immediately. </li>

                                                <li>Upon completion of the repairs the vehicle will be released subject to re-inspection.</li>



                                            </ul>

                                            <p>COMPLETION SUBJECT TO AVAILABILITY OF PARTS</p>

                                            <p>For: JUBILEE INS. CO</p>

                                            {{--                                            <p>Assessor: {{ \App\User::where('id', $assessment['userID'])->first()->name }}</p>--}}

                                            <p>Date: {{ date('l jS F Y', strtotime($assessment['assessedAt'])) }}</p>



                                        </div>



                                    @else

                                        <div class="col-md-12 content-group">

                                            <p>For: JUBILEE INS. CO</p>

                                            {{--                                            <p>Assessor: {{ \App\User::where('id', $assessment['userID'])->first()->name }}</p>--}}

                                            <p>Date: {{ date('l jS F Y', strtotime($assessment['assessedAt'])) }}</p>



                                        </div>

                                    @endif
                                    @if($assessment['totalLoss'] != '')

                                        <div class="col-md-12 content-group">

                                            <h4>PAV: {{ number_format($assessment['pav']) }}</h4>

                                            <h4>Salvage: {{ number_format($assessment['salvage']) }}</h4>

                                            <h4>Total Loss: {{ number_format($assessment['totalLoss']) }}</h4>



                                        </div>

                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <h5 class="underline">Notes</h5>

                                    <p>{!! $assessment['note'] !!}</p>


                                    <p>Assessed By: {{$assessor->firstName}} {{$assessor->lastName}}</p>                              </div>
                            </div>
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
                                        <div class="col s3">
                                            <a href="" data-lightbox="gallery">
                                                <img class="responsive-img" src="{{url($path.'/'.$document['name']) }}">
                                            </a>
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
</body>

</html>



