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
                                    <h4 class="card-title float-left">Assessment Report</h4>
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

                                        <thead>

                                        <tr>

                                            <th class="col-sm-1 text-bold">Quantity</th>

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

                                            <tr>

                                                <td>{{ $assessmentItem['quantity'] }}</td>

                                                <td>@if($assessmentItem['category'] == \App\Conf\Config::$JOB_CATEGORIES['REPAIR']['ID']) Y @else @endif</td>

                                                <td>@if($assessmentItem['category'] == \App\Conf\Config::$JOB_CATEGORIES['REPLACE']['ID']) Y @else @endif</td>

                                                <td>{{ $assessmentItem['part']['name'] }}</td>

                                                <td>{{ number_format($assessmentItem['cost']) }}</td>

                                                <td>{{ $assessmentItem['contribution'] }}</td>

                                                <td>{{ $assessmentItem['discount'] }}</td>

                                                <td>{{ number_format($assessmentItem['total']) }}</td>

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
                                            <td></td>
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

                                            @if($assessment['claim']->intimationDate > \App\Conf\Config::VAT_REDUCTION_DATE && $assessment['claim']->intimationDate <= \App\Conf\Config::VAT_END_DATE)
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
                                            <?php
                                            $grandTotal = 0
                                            ?>
                                            @if($assessment['assessmentTypeID'] == App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])
                                                <td>{{ number_format(round(($sumOfTotalItems * $markup)+$jobValue-$scrapValue)) }}
                                                <?php
                                                $grandTotal = ($sumOfTotalItems * $markup)+$jobValue-$scrapValue
                                                ?>

                                            @else
                                                <td>
                                                    @if(isset($assessment['totalChange']) && isset($priceChange->finalApprovedAt))
                                                        {{ number_format($assessment['totalChange']-$scrapValue) }}
                                                        <?php
                                                        $grandTotal = $assessment['totalChange']-$scrapValue
                                                        ?>
                                                    @else
                                                        {{ number_format($assessment['totalCost']-$scrapValue) }}
                                                        <?php
                                                        $grandTotal= $assessment['totalCost']-$scrapValue
                                                        ?>
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
                                    <br/>
                                    <p><b>Assessor : {{$assessor->firstName}} {{$assessor->lastName}}</b></p>
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
                                        <?php
                                        if ($document['isResized'] == 1) {
                                            $path = 'thumbnail';
                                        } else {
                                            $path = 'documents';
                                        }
                                        ?>
                                        <div class="col s3">
                                            <a href="{{url($path.'/'.$document['name']) }}" data-lightbox="gallery">
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
                @if(isset($assessment['approvedBy']))
                    <div class="row">
                        <div class="col s4">
                            <a id="triggerChangeRequests" data-target="changeRequest" class="btn orange darken-2">Request
                                Changes</a>
                        </div>
                        <div class="col s4">
                            <!-- Modal Trigger -->
                            @if($grandTotal <= auth()->user()->maxAmount )
                                <button id="triggerApprove" data-target="approve" class="btn blue lighten-2 btn">
                                    Approve/Halt/Cancel
                                </button>
                            @else
                                <button id="" class="btn red lighten-2 btn">
                                    Amount Exceeded your approval limit
                                </button>
                            @endif

                        </div>
                        <div class="col s4">
                            <a id="triggerDiscount" data-target="discount" class="btn teal darken-2">Apply Discount</a>
                        </div>
                    </div>
                @endif
            <div class="row">
                <div class="col s2"></div>
                <div class="col s8">
                    <!-- Modal Structure -->
                    <div id="approve" class="modal">
                        <div class="modal-content">
                            <div class="modal-body clearfix">
                                <div class="row">
                                    <div class="col s12">
                                        <p>Review Assessment</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" name="assessmentID" id="assessmentID" value="{{$assessment->id}}">
                                        <div class="col m4">
                                            <label>
                                                <input name="assessmentReviewType" type="radio"
                                                       class="with-gap assessmentReviewType" value="{{\App\Conf\Config::APPROVE}}"/>
                                                <span>Approve</span>
                                            </label>
                                            <input type="hidden" value="{{$assessment['pav']}}" id="pav" name="pav">
                                            <input type="hidden" value="{{$grandTotal}}" id="grandTotal" name="grandTotal">
                                            <input type="hidden" value="{{$assessment['assessmentTypeID']}}" id="assessmentTypeID" name="assessmentTypeID">
                                        </div>
                                        <div class="col m4">
                                            <label>
                                                <input name="assessmentReviewType" type="radio"
                                                       class="with-gap assessmentReviewType" value="{{App\Conf\Config::HALT}}"/>
                                                <span>Halt</span>
                                            </label>
                                        </div>
                                </div>
                                @include('common.generic-subrogation-form')
                                <div class="row">
                                    <div class="input-field col m12 s12">
                                        <div class="row">
                                            <div class="col s12">
                                                <span>Report</span>
                                            </div>
                                        </div>
                                        <textarea id="report" class="materialize-textarea">

                                        </textarea>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function(event) {
                                                CKEDITOR.replace('report', {
                                                language: 'en',
                                                uiColor: '',
                                                height: $(this).attr('height')
                                            });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m8 s12">
                                    </div>
                                    <div class="input-field col m4 s12">
                                        <a href="#" class="btn blue lighten-2 waves-effect" id="reviewAssessment">Submit</a>
                                        <a class="modal-action modal-close btn red lighten-2 waves-effect">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s2"></div>
            </div>

            <div class="row">
                <div class="col s2"></div>
                <div class="col s8">
                    <!-- Modal Structure -->
                    <div id="discount" class="modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
                            </div>
                            <div class="modal-body clearfix">
                                <div class="row">
                                    <div class="input-field col m12 s12">
                                        <input type="text" name="discount" id="discount">
                                        <label for="discount" class="active">Apply Discount</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m8 s12">
                                    </div>
                                    <div class="input-field col m4 s12">
                                        <a href="#" class="btn blue lighten-2 waves-effect">Submit</a>
                                        <a href="#" class="modal-action modal-close btn red lighten-2 waves-effect">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s2"></div>
            </div>

            <div class="row">
                <div class="col s2"></div>
                <div class="col s8">
                    <!-- Modal Structure -->
                    <div id="changeRequest" class="modal">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="input-field col m12 s12">
                                        <div class="row">
                                            <div class="col s12">
                                                <span class="float-left">Request Changes On Report</span>
                                                <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
                                            </div>
                                        </div>
                                        <textarea name="changes" id="changes" class="materialize-textarea clearfix">

                                        </textarea>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function(event) {
                                                CKEDITOR.replace('changes', {
                                                language: 'en',
                                                uiColor: ''
                                            });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m8 s12">
                                    </div>
                                    <div class="input-field col m4 s12">
                                        <a href="#" class="btn blue lighten-2 waves-effect" id="assessment-manager-request-change">Submit</a>
                                        <a href="#" class="modal-action modal-close btn red lighten-2 waves-effect">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s2"></div>
            </div>
            <br/>
        </div>
    </div>
</div>
</div>
@include('_partials.settings')
@include('_partials.footer')



