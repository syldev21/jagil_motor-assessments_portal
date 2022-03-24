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
                                        <input id="assessmentStatusID" type="hidden" value="{{$assessmentStatusID}}">
                                        <label for="vehicle_reg_no">Reg No</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light" type="submit" id="filter-theft-assessments"
                                                    name="action" data-id="{{$assessmentStatusID}}">
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
                                            <th>Adjuster</th>
                                            @if($assessmentStatusID == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                <th>Approver</th>
                                                <th>Final Approver</th>
                                            @endif
                                            <th>Assessor</th>
                                            <th>Status</th>
                                            <th>{{\App\Conf\Config::$DISPLAY_STATUSES["ASSESSMENT"][$assessmentStatusID]}}</th>
                                            <th>Operation</th>
                                            <th>Type</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($assessments as $assessment)
                                            <form class="assignForm">
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>
                                                        <a href="#" data-id="{{$assessment->id}}" id="assessmentDetails">{{$assessment['claim']['claimNo']}}</a>
                                                    </td>
                                                    <?php $date = ''?>
                                                    <td>{{$assessment['claim']['vehicleRegNo']}}</td>
                                                    <?php
                                                    $adjuster = \App\User::where(['id'=> $assessment['claim']['createdBy']])->first();
                                                    ?>
                                                    <td>{{isset($adjuster->name) ? $adjuster->name : ''}}</td>
                                                    @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                        <td>{{isset($assessment->approver) ? $assessment->approver->firstName : ''}} {{isset($assessment->approver) ? $assessment->approver->lastName : ''}}</td>
                                                        <td>{{isset($assessment->final_approver->firstName) ? $assessment->final_approver->firstName : ''}} {{isset($assessment->final_approver->lastName) ? $assessment->final_approver->lastName : ''}}</td>
                                                    @endif
                                                    <td>{{isset($assessment->assessor) ? $assessment->assessor->firstName : ''}} {{isset($assessment->assessor) ? $assessment->assessor->lastName : ''}}</td>
                                                    @if($assessment['assessmentStatusID']  == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn red lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['text'] }}</button>
                                                        </td>
                                                        <?php $date = $assessment['dateCreated'] ?>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'])
                                                        <td>
                                                            <button
                                                                class="btn orange lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['text']}}</button>
                                                        </td>
                                                        <?php $date = $assessment['assessedAt'] ?>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn orange lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['text']}}</button>
                                                        </td>
                                                        <?php $date = $assessment['assessedAt'] ?>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'])
                                                        <td>
                                                            <button
                                                                class="btn orange lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['text']}}</button>
                                                        </td>
                                                        <?php $date = $assessment['approvedAt'] ?>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn green lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['text']}}</button>
                                                        </td>
                                                        <?php $date = $assessment['finalApprovedAt'] ?>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id'])
                                                        <td>
                                                            <button
                                                                class="btn red lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['text']}}</button>
                                                        </td>
                                                        <?php $date = $assessment['changeRequestAt'] ?>
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
                                                        $claimNo = str_replace("/","_",$assessment['claim']['claimNo']);
                                                        $policyNo = str_replace("/","_",$assessment['claim']['policyNo']);
                                                        $claimForm =\App\Document::where(['claimID'=>$assessment['claimID'],"documentType"=>\App\Conf\Config::$DOCUMENT_TYPES['PDF']['ID'],'pdfType'=>App\Conf\Config::PDF_TYPES['CLAIM_FORM']['ID']])->first();

                                                        $invoiceDoc =\App\Document::where(['claimID'=>$assessment['claimID'],"documentType"=>\App\Conf\Config::$DOCUMENT_TYPES['PDF']['ID'],'pdfType' => App\Conf\Config::PDF_TYPES['INVOICE']['ID']])->first();
                                                        ?>

                                                        <ul id='{{$loop->iteration}}' class='dropdown-content'>
                                                            @if(Auth::user()->hasRole(\App\Conf\Config::$ROLES["ASSESSOR"]) && Auth::user()->id == $assessment['assessedBy'] && ($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'] || $assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id']))
                                                                <li><a href="#" data-id="{{$assessment['id']}}" id="triggerPTVModal"><i
                                                                            class="material-icons">attach_money</i>
                                                                        @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id'])
                                                                        Edit
                                                                        @else
                                                                        Process
                                                                        @endif
                                                                        PTV
                                                                    </a></li>
                                                            @endif
                                                            @if($assessment['assessmentStatusID'] != \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'])
                                                                <li><a href="#" data-id="{{$assessment->id}}" id="PTVReport"><i
                                                                            class="material-icons">picture_as_pdf</i>View
                                                                        PTV Report</a></li>
                                                            @endif
                                                            @if(isset($claimForm->name))
                                                                <li><a href="{{asset('documents/'.$claimForm->name)}}" download><i
                                                                            class="material-icons">file_download</i> Claim Form </a></li>
                                                            @endif
                                                            <li>
                                                                <a href="#" id="fetchDMSDocuments" data-id="{{$claimNo}}" data-id2="{{$policyNo}}"><i
                                                                        class="material-icons">attachment</i>DMS</a>
                                                            </li>
                                                            <li>
                                                                <a href="#" id="triggerNotification" data-id="{{$assessment['id']}}"><i
                                                                        class="material-icons">notifications_active</i>Send Notification </a>
                                                            </li>
                                                        </ul>

                                                    </td>
                                                </tr>
                                            </form>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s2"></div>
                                <div class="col s8">
                                    <!-- Modal Structure -->
                                    <div id="PTVModal" class="modal">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="input-field col m12 s12">
                                                        <div class="row">
                                                            <div class="col s12">
                                                                <span class="float-left" style="font-size: 1.6em"></span>
                                                                <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col m6 s12">
                                                        <input type="text" id="amount" name="amount">
                                                        <label for="amount" class="active">Enter PTV Amount</label>
                                                    </div>
                                                    <div class="input-field col m6 s12">

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col m8 s12">
                                                    </div>
                                                    <div class="input-field col m4 s12">
                                                        <input type="hidden" id="assessmentID" >
                                                        <a href="#" class="btn blue lighten-2 waves-effect" id="submitPTVRequest">Submit</a>
                                                        <a href="#" class="modal-action modal-close btn red lighten-2 waves-effect">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col s2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('common.generic-notification')
        </div>
    </div>
</div>


