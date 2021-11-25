
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
                                        <h4 class="card-title float-left">PTV Report</h4>
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
                                            <h5>JUBILEE ALLIANZ INSURANCE PTV REPORT</h5>
                                            <h6>MOTOR RE-INSPECTION REPORT</h6>
                                        </div>
                                        <div class="col s2">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12">
                                            <div class="col s12">
                                                <table id="page-length-option" class="display">
                                                    <thead>

                                                    <tr>

                                                        <th><strong>Claim Number</strong></th>

                                                        <th><strong>Insured</strong></th>

                                                        <th><strong>Vehicle Registration</strong></th>

                                                        <th><strong>Date</strong></th>

                                                    </tr>

                                                    </thead>
                                                    <tbody>

                                                    <tr>

                                                        <td>{{ $assessment->claim->claimNo }}</td>

                                                        <td>{{ $insuredFullName }}</td>

                                                        <td>{{ $assessment->claim->vehicleRegNo }}</td>

                                                        <td>{{ date('l jS F Y') }}</td>

                                                    </tr>


                                                    </tbody>

                                                </table>

                                                <p>

                                                    With all the considerations in place the PTV values for the mentioned vehicle is <span style="font-weight: bold;">Ksh. {{number_format($assessment->PTV)}}</span>

                                                    {{ date('l jS F Y', strtotime($assessment->assessedAt)) }}.

                                                </p>

                                                <p>

                                                    {{auth()->user()->name}}, <br>

                                                    Claims Department, <br>

                                                    Jubilee Allianz Insurance Company.

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if((Auth::user()->hasRole(\App\Conf\Config::$ROLES["HEAD-ASSESSOR"]) && $assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['id']) || (Auth::user()->hasRole(\App\Conf\Config::$ROLES["ASSESSMENT-MANAGER"]) && $assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id']))
                <div class="row">
                    <div class="col s4">
                        <a id="triggerChangeRequests" data-target="changeRequest" class="btn orange darken-2">Request
                            Changes</a>
                    </div>
                    <div class="col s4">
                        <!-- Modal Trigger -->
                        <button id="triggerApprove" data-target="approve" class="btn blue lighten-2 btn">
                            Approve/Halt/Cancel
                        </button>

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
                                            @hasrole(\App\Conf\Config::$ROLES["HEAD-ASSESSOR"])
                                            <a href="#" class="btn blue lighten-2 waves-effect" id="review-head-assessor-assessment">Submit</a>
                                            @endhasrole
                                            @hasrole(\App\Conf\Config::$ROLES["ASSESSMENT-MANAGER"])
                                            <input type="hidden" name="grandTotal" value="0" id="grandTotal">
                                            <input type="hidden" name="pav" value="0" id="pav">
                                            <input name="subrogation" type="checkbox"
                                                   class="subrogation" value="" id="subrogation" style="display: none;"/>
                                            <input name="company" type="hidden" value="0" id="company"/>
                                            <a href="#" class="btn blue lighten-2 waves-effect" id="reviewAssessment">Submit</a>
                                            @endhasrole
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
                                            @hasrole(\App\Conf\Config::$ROLES["HEAD-ASSESSOR"])
                                            <a href="#" class="btn blue lighten-2 waves-effect" id="head-assessor-request-change">Submit</a>
                                            @endhasrole
                                            @hasrole(\App\Conf\Config::$ROLES["ASSESSMENT-MANAGER"])
                                            <a href="#" class="btn blue lighten-2 waves-effect" id="assessment-manager-request-change">Submit</a>
                                            @endhasrole
                                            <a href="#" class="modal-action modal-close btn red darken-2 waves-effect">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s2"></div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@include('_partials.settings')
@include('_partials.footer')
