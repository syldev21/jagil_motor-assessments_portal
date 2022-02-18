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
                                            <button class="btn cyan waves-effect waves-light" type="submit" id="filter-adjuster-assessments"
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
                                                            @if($assessment['assessmentStatusID'] != \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'])
                                                                <li><a href="#" data-id="{{$assessment->id}}" id="assessmentReport"><i
                                                                            class="material-icons">picture_as_pdf</i>View
                                                                        Assessment Report</a></li>
                                                            @endif
                                                            @if(isset($claimForm->name))
                                                            <li><a href="{{asset('documents/'.$claimForm->name)}}" download><i
                                                                        class="material-icons">file_download</i> Claim Form </a></li>
                                                            @endif
                                                                @if(isset($invoiceDoc->name))
                                                                    <li><a href="{{asset('documents/'.$invoiceDoc->name)}}" download><i
                                                                                class="material-icons">file_download</i>Invoice</a></li>
                                                                @endif
                                                            @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                                    <li><a href="#!"><i
                                                                                class="material-icons">compare_arrows</i>View
                                                                            Re-inspection</a></li>
                                                                    <li><a href="{{url('/adjuster/re-inspection-letter/'.$assessment['id'])}}" target="_blank"><i
                                                                        class="material-icons">picture_as_pdf</i>View
                                                                    Re-inspection Letter</a></li>
{{--                                                            <li><a href="{{url('/adjuster/send-release-letter/'.$assessment['claimID'])}}" data-id="{{$assessment['claimID']}}" id=""><i--}}
{{--                                                                        class="material-icons">picture_as_pdf</i>--}}
{{--                                                                    Release Letter</a></li>--}}
                                                                @if($assessment['assessmentTypeID'] == App\Conf\Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE'])
                                                                    <li><a id="send-release-letter" data-id="{{$assessment['claimID']}}" id=""><i
                                                                                class="material-icons">picture_as_pdf</i>
                                                                            Release Letter</a></li>
                                                                @endif
                                                                    @if($assessment['assessmentTypeID'] == App\Conf\Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])
                                                                        <li><a id="send-discharge-voucher" data-id="{{$assessment['claimID']}}" id=""><i class="material-icons">picture_as_pdf</i>
                                                                                Discharge Voucher</a></li>
                                                                    @endif
                                                            @endif
                                                                @if(isset($assessment['supplementaries']))
                                                                    @foreach($assessment['supplementaries'] as $supplementary )

                                                                        <li><a href="#!" id="view-adjuster-supplementary-report" data-id="{{$supplementary->id}} "><i
                                                                                    class="material-icons">insert_drive_file</i>
                                                                                Supplementary {{$loop->iteration}}</a>
                                                                        </li>
                                                                    @endforeach
                                                                @endif
                                                                @if($assessment['isSubrogate'] == App\Conf\Config::ACTIVE)
                                                                    <li>
                                                                        <a href="#" id="subrogate-report" data-id="{{$assessment->id}}"><i
                                                                                class="material-icons">picture_as_pdf</i>View Subrogate Report</a>
                                                                    </li>
                                                                @endif
                                                                <li>
                                                                    <a href="#" id="fetchDMSDocuments" data-id="{{$claimNo}}" data-id2="{{$policyNo}}"><i
                                                                            class="material-icons">attachment</i>DMS</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" id="triggerNotification" data-id="{{$assessment['id']}}"><i
                                                                            class="material-icons">notifications_active</i>Send Notification </a>
                                                                </li>
                                                                <?php
                                                                $statusIds = array(\App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'],\App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id'],\App\Conf\Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'],\App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id']);
                                                                ?>
                                                                @if(in_array($assessment['assessmentStatusID'],$statusIds))
                                                                <li>
                                                                    <a href="#" id="triggeraddCourtesyCarFirmModal" data-id="{{$assessment['claim']['id']}}"><i
                                                                            class="material-icons">drive_eta</i>Process Courtesy Car</a>
                                                                </li>
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
            @include('common.generic-notification')
            @include('common.generic-courtesy-car')
        </div>
    </div>
</div>


