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
                                <h4 class="card-title float-left">{{\App\Conf\Config::$DISPLAY_STATUSES["ASSESSMENT"][$assessmentStatusID]}} Assessments</h4>
                                {{--                                    <a href="{{ url('adjuster/claim-form') }}" class="float-right btn cyan waves-effect waves-effect waves-light"><i class="material-icons left">add_circle_outline</i> Add Claim</a>--}}
                            </div>
                            <div class="row">
                                <div class="row">
                                    <div class="input-field col m3 s6">
                                        <i class="material-icons prefix">access_time</i>
                                        <input id="from_date" type="text" class="validate datepicker">
                                        <label for="from_date">From Date</label>
                                    </div>
                                    <div class="input-field col m3 s6">
                                        <i class="material-icons prefix">access_time</i>
                                        <input id="to_date" type="text" class="validate datepicker">
                                        <label for="to_date">To Date</label>
                                    </div>
                                    <div class="input-field col m3 s6">
                                        <i class="material-icons prefix">vpn_key</i>
                                        <input id="vehicle_reg_no" type="text" class="validate">
                                        <label for="vehicle_reg_no">Reg No</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light" type="submit"
                                                    name="action">
                                                <i class="material-icons left">search</i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <table id="data-table-simple" class="display">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Claim Number</th>
                                            <th>Registration Number</th>
                                            @if($assessmentStatusID == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                <th>Approved By</th>
                                                <th>Final Approver</th>
                                            @endif
                                            @if($assessmentStatusID != \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'])
                                                <th>Assessed By</th>
                                            @endif
                                            <th>Status</th>
                                            <th>{{\App\Conf\Config::$DISPLAY_STATUSES["ASSESSMENT"][$assessmentStatusID]}}</th>
                                            <th>Type</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($assessments as $assessment)
                                            <form class="assignForm">
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td><a href="#" data-id="{{$assessment['claim']['id']}}" id="claimDetails">{{$assessment['claim']['claimNo']}}</a></td>
                                                    <td>{{$assessment['claim']['vehicleRegNo']}}</td>
                                                    <?php $date = ''?>
                                                    @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                        <td>{{isset($assessment->approver->firstName) ? $assessment->approver->firstName : ''}} {{isset($assessment->approver->lastName) ? $assessment->approver->lastName : ''}}</td>
                                                        <td>{{isset($assessment->final_approver->firstName) ? $assessment->final_approver->firstName : ''}} {{isset($assessment->final_approver->lastName) ? $assessment->final_approver->lastName : ''}}</td>
                                                    @endif
                                                    @if($assessment['assessmentStatusID'] != \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'])
                                                        <td>{{isset($assessment->assessor) ? $assessment->assessor->firstName.' '.$assessment->assessor->lastName : ''}}</td>
                                                    @endif
                                                    @if($assessment['assessmentStatusID']  == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn red lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['text'] }}</button>
                                                        </td>
                                                        <?php $date = $assessment['dateCreated']; ?>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'])
                                                        <td>
                                                            <button
                                                                class="btn orange lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['text']}}</button>
                                                        </td>
                                                        <?php $date = $assessment['assessedAt']; ?>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn orange lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['text']}}</button>
                                                        </td>
                                                        <?php $date = $assessment['assessedAt']; ?>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'])
                                                        <td>
                                                            <button
                                                                class="btn orange lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['text']}}</button>
                                                        </td>
                                                        <?php $date = $assessment['approvedAt']; ?>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn green lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['text']}}</button>
                                                        </td>
                                                        <?php $date = $assessment['finalApprovedAt']; ?>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id'])
                                                        <td>
                                                            <button
                                                                class="btn red lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['text']}}</button>
                                                        </td>
                                                        <?php $date = $assessment['dateModified']; ?>
                                                    @endif
                                                    <td>
                                                        {{\Carbon\Carbon::parse($date)->diffForHumans()}}
                                                    </td>
                                                    <td>
                                                        {{ isset($assessment['assessmentTypeID'])  ?  \App\Conf\Config::DISPLAY_ASSESSMENT_TYPES[$assessment['assessmentTypeID']] : ''}}
                                                    </td>
                                                    <input type="hidden" name="claimID{{$loop->iteration}}"
                                                           id="claimID{{$loop->iteration}}"
                                                           value="{{$assessment['claimID']}}" class="claimID">

                                                    <td>
                                                        <!-- Dropdown Trigger -->
                                                        <a class='dropdown-trigger' href='#'
                                                           data-target='{{$loop->iteration}}'
                                                           data-activates="{{$loop->iteration}}"><i
                                                                class="Medium material-icons">menu</i><i
                                                                class="Medium material-icons">expand_more</i></a>

                                                        <!-- Dropdown Structure -->
                                                        <?php
                                                        $claim='claim';
                                                        $claimForm =\App\Document::where(['claimID'=>$assessment['claimID'],"documentType"=>\App\Conf\Config::$DOCUMENT_TYPES['PDF']['ID']])->where('name','like','%' .$claim. '%')->first();

                                                        $invoice='invoice';
                                                        $invoiceDoc =\App\Document::where(['claimID'=>$assessment['claimID'],"documentType"=>\App\Conf\Config::$DOCUMENT_TYPES['PDF']['ID']])->where('name','like','%' .$invoice. '%')->first();
                                                        ?>
                                                        <ul id='{{$loop->iteration}}' class='dropdown-content'>
                                                            @if(isset($claimForm->name))
                                                                <li><a href="{{asset('documents/'.$claimForm->name)}}" download><i
                                                                            class="material-icons">file_download</i>Claim Form</a></li>
                                                            @endif
                                                                @if(isset($invoiceDoc->name))
                                                                    <li><a href="{{asset('documents/'.$invoiceDoc->name)}}" download><i
                                                                                class="material-icons">file_download</i>Invoice</a></li>
                                                                @endif
                                                            @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'])
                                                                <li>
                                                                    <a href="#" id="fillAssessmentReport"
                                                                       data-id="{{$assessment['id']}}" data-claimid="{{$assessment['claimID']}}"><i
                                                                            class="material-icons">insert_drive_file</i>Submit
                                                                        Assessment </a>
                                                                </li>
                                                            @endif
                                                                @if(($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['id']) || ($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id']))
                                                                <li>
                                                                    <a href="#" id="view-assessor-assessment-report"
                                                                       data-id="{{$assessment['id']}}"><i
                                                                            class="material-icons">edit</i>Edit Assessment </a>
                                                                </li>


                                                            @endif
                                                            @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'])
                                                                <li>
                                                                    <a href="#" id="fillAssessmentReport"
                                                                       data-id="{{$assessment['id']}}" data-claimid="{{$assessment['claimID']}}"><i
                                                                            class="material-icons">insert_drive_file</i>Fill
                                                                        Report </a>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a href="#" id="assessor-assessment-report"
                                                                       data-id="{{$assessment['id']}}"><i
                                                                            class="material-icons">insert_drive_file</i>View
                                                                        Assessment Report </a>
                                                                </li>
                                                            @endif
                                                            @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                                <li>
                                                                    <a href="#" id="fillReInspectionReport"
                                                                       data-id="{{$assessment['id']}}"><i
                                                                            class="material-icons">insert_drive_file</i>Re-Inspection
                                                                        Report</a></li>
                                                                        <li>
                                                                            <a href="#"
                                                                               id="assessor-view-re-inspection-report"
                                                                               data-id="{{$assessment['id']}}"><i
                                                                                    class="material-icons">insert_drive_file</i>View
                                                                                Re-Inspection
                                                                                Report</a></li>
                                                                    <li>
                                                                        <a   href="#"
                                                                             id="assessor-price-change"
                                                                             data-id="{{$assessment['id']}}"><i
                                                                                class="material-icons">compare_arrows</i>Price Change</a>
                                                                    </li>
                                                                @if($assessment->priceChange > 0)
                                                                    <li>
                                                                        <a   href="#"
                                                                             id="assessor-view-price-change"
                                                                             data-id="{{$assessment['id']}}"><i
                                                                                class="material-icons">compare_arrows</i>View Price Change Report</a>
                                                                    </li>
                                                                @endif
                                                                @if($assessment['assessmentTypeID'] != \App\Conf\Config::ASSESSMENT_TYPES['TOTAL_LOSS'])
                                                                <li><a href="#!" id="fillSupplementaryReport" data-id="{{$assessment['id']}}"><i
                                                                            class="material-icons">insert_drive_file</i>Add
                                                                        Supplementary Report</a></li>
                                                                    @foreach($asmts as $asmt )

                                                                        @if($asmt->assessmentID==$assessment->id)

                                                                            <li><a href="#!" id="assessment-manager-assessment-report" data-id="{{$asmt['id']}} "><i
                                                                                        class="material-icons">insert_drive_file</i>
                                                                                    Supplementary</a>
                                                                            </li>
                                                                            @endif

                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        </ul>

                                                    </td>
                                                </tr>
                                            </form>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
