
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
                                        <h4 class="card-title float-left">Price Change Report</h4>
                                    </div>
                                    <div class="col s2">
                                        <button type="button" class="btn teal float-right" onclick="printDiv()"><i class="material-icons" style="font-size: 2em;">local_printshop</i></button>
                                    </div>
                                </div>
                                <div id="printableArea">
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
                                                This report is issued without prejudice, in respect of cause, nature and
                                                extent
                                                of loss/damage and subject to the terms and conditions of the Insurance
                                                Policy.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12">
                                            <ul class="list-unstyled center-align">
                                                <li class="col s3">Policy Number:
                                                    <h6>{{$assessment['claim']['policyNo']}}</h6>
                                                </li>
                                                <li class="col s3">Adjuster:
                                                    <h6>{{isset($adjuster->firstName) ? $adjuster->firstName : ''}} {{isset($adjuster->lastName) ? $adjuster->lastName : ''}}</h6>
                                                </li>
                                                <li class="col s3">Insured: <h6>{{$insured['fullName']}}</h6></li>
                                                <li class="col s3">Claim Number:
                                                    <h6>{{$assessment['claim']['claimNo']}}</h6>
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

                                                            <th class="col-sm-1 text-bold center-align">Contribution %
                                                            </th>

                                                            <th class="col-sm-1 text-bold center-align">Disc</th>

                                                            <th class="col-sm-2 text-bold center-align">Total</th>
                                                            <th class="col-sm-2 text-bold center-align">Price Change
                                                            </th>
                                                            <th class="col-sm-2 text-bold center-align">Price Diff</th>

                                                            <th class="col-sm-2 text-bold center-align">Remarks</th>
                                                        </tr>


                                                        </thead>

                                                        <tbody>
                                                        @foreach($assessmentItems as $item)

                                                            <tr>

                                                                <td>{{ $item->quantity }}</td>

                                                                <td>@if($item['category'] == \App\Conf\Config::$JOB_CATEGORIES['REPLACE']['ID'])
                                                                        Y @else @endif</td>

                                                                <td>@if($item['category'] == \App\Conf\Config::$JOB_CATEGORIES['REPAIR']['ID'])
                                                                        Y @else @endif</td>

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

                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>

                                                        {{--                                                    <tr><td></td><td></td><td></td><td class="text-bold">Total parts cost</td><td></td><td></td><td></td><td></td><td>{{number_format(\App\Assessment::where('id', $assessment['id'])->sum('totalCost') + (\App\assessmentItem::where('assessmentID', $assessment['id'])->sum(\DB::raw('quantity * difference'))*\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE/\App\Conf\Config::INITIAL_PERCENTAGE))}}</td><td></td></tr>--}}
                                                        @if($assessment['assessmentTypeID'] == \App\Conf\Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE'])
                                                            @if($assessment['dateCreated'] > App\Conf\Config::VAT_REDUCTION_DATE)
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-bold">Total parts cost</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>{{number_format(\App\Assessment::where('id', $assessment['id'])->sum('totalCost') + (\App\assessmentItem::where('assessmentID', $assessment['id'])->sum(\DB::raw('quantity * difference'))*\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE/\App\Conf\Config::INITIAL_PERCENTAGE))}}</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-bold">
                                                                        + {{\App\Conf\Config::CURRENT_VAT_PERCENTAGE}}
                                                                        VAT
                                                                    </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>{{ number_format((\App\assessmentItem::where('assessmentID', $assessment['id'])->sum(\DB::raw('quantity * current'))*\App\Conf\Config::CURRENT_VAT/\App\Conf\Config::INITIAL_PERCENTAGE)) }}</td>
                                                                    <td></td>
                                                                </tr>
                                                            @else
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-bold">Total parts cost</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>{{number_format(\App\Assessment::where('id', $assessment['id'])->sum('totalCost') + (\App\assessmentItem::where('assessmentID', $assessment['id'])->sum(\DB::raw('quantity * difference'))*\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE/\App\Conf\Config::INITIAL_PERCENTAGE))}}</td>
                                                                    <td></td>
                                                                </tr>
                                                                {{--                                                                <tr><td></td><td></td><td></td><td class="text-bold">+ {{App\Conf\Config::VAT_PERCENTAGE}} VAT</td><td></td><td></td><td></td><td></td><td>{{ number_format((\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('current')) * (\App\Conf\Config::TOTAL_PERCENTAGE/\App\Conf\Config::INITIAL_PERCENTAGE)) }}</td><td></td></tr>--}}
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-bold">
                                                                        + {{App\Conf\Config::VAT_PERCENTAGE}} VAT
                                                                    </td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>{{ number_format((\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('current')) * (\App\Conf\Config::TOTAL_PERCENTAGE/\App\Conf\Config::INITIAL_PERCENTAGE)) }}</td>
                                                                    <td></td>
                                                                </tr>
                                                            @endif

                                                        @elseif($assessment['assessmentTypeID'] == \App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])

                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="text-bold">Less Markup</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ number_format((\App\AssessmentItem::where('assessmentID', $assessment['id'])->sum('current')) * \App\Conf\Config::MARK_UP) }}</td>
                                                                <td></td>
                                                            </tr>

                                                        @endif

                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-bold">Initial Grand Total</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{ number_format($assessment['totalCost']) }}</td>
                                                            <td></td>
                                                        </tr>

                                                        {{--                                                        <tr><td></td><td></td><td></td><td class="text-bold">Additional Amount</td><td></td><td></td><td></td><td></td><td>{{ number_format($assessment['totalChange'] - $assessment['totalCost']) }}</td><td></td></tr>--}}
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-bold">Additional Amount</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{ (\App\assessmentItem::where('assessmentID', $assessment['id'])->sum(\DB::raw('quantity * difference'))*\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE/\App\Conf\Config::INITIAL_PERCENTAGE) }}</td>
                                                            <td></td>
                                                        </tr>

                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-bold">Current Grand Total</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{number_format(\App\Assessment::where('id', $assessment['id'])->sum('totalCost') + (\App\assessmentItem::where('assessmentID', $assessment['id'])->sum(\DB::raw('quantity * difference'))*\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE/\App\Conf\Config::INITIAL_PERCENTAGE))}}</td>
                                                            <td></td>
                                                        </tr>


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
                        </div>
                        <div class="row">
                            <div class="col s4">
                                <a id="triggerChangeRequests" data-target="changeRequest" class="btn orange darken-2">Request Changes</a>
                            </div>
                            <div class="col s4">
                                <!-- Modal Trigger -->
                                <button id="triggerApprove" data-target="approve" class="btn blue lighten-2 btn">Approve/Halt/Cancel</button>
                            </div>

                        </div>
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
                                                </div>
                                                <div class="col m4">
                                                    <label>
                                                        <input name="assessmentReviewType" type="radio"
                                                               class="with-gap assessmentReviewType" value="{{App\Conf\Config::HALT}}"/>
                                                        <span>Halt</span>
                                                    </label>
                                                </div>
                                            </div>
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
                                                            //do work
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
                                                    <a href="#" class="btn blue lighten-2 waves-effect" id="assessment-manager-review-price-change">Submit</a>
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
                                                    <a href="#" class="btn blue lighten-2 waves-effect" id="assessment-manager-request-price-change">Submit</a>
                                                    <a href="#" class="modal-action modal-close btn red darken-2 waves-effect">Cancel</a>
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
