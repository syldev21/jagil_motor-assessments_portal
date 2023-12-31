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
                                <h4 class="card-title float-left">Assessments</h4>
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
                                            <button class="btn cyan waves-effect waves-light" type="submit" id="filterReInspections"
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
                                            <th>Policy Number</th>
                                            <th>Sub Class Code</th>
                                            <th>Sub Class</th>
                                            <th>Claims loss date</th>
                                            <th>Claims creation date</th>
                                            <th>Intimation Date</th>
                                            <th>Model</th>
                                            <th>Chassis</th>
                                            <th>RegNo</th>
                                            <th>Adjuster</th>
                                            <th>Approver</th>
{{--                                            <th>Final Approver</th>--}}
                                            <th>Assessor</th>
                                            <th>Status</th>
                                            <th>Time</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>Assessed At</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($assessments as $assessment)
                                            <form class="assignForm">
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td><a href="#" data-id="{{$assessment['claim']['id']}}" id="claimDetails">{{$assessment['claim']['claimNo']}}</a></td>
                                                    <td>{{$assessment['claim']['policyNo']}}</td>
                                                    <td>{{isset($assessment['claim']['subClassCode']) ? $assessment['claim']['subClassCode'] : ''}}</td>
                                                    <td>{{isset($assessment['claim']['subClassCode']) ? \App\Conf\Config::SUB_CLASSES[$assessment['claim']['subClassCode']] : ''}}</td>
                                                    <td>{{$assessment['claim']['loseDate']}}</td>
                                                    <td>{{$assessment['claim']['dateCreated']}}</td>
                                                    <td>{{$assessment['claim']['intimationDate']}}</td>
                                                    <?php
                                                    $carDetail = \App\CarModel::where(["modelCode" => isset($assessment['claim']['carModelCode']) ? $assessment['claim']['carModelCode'] : 0])->first();
                                                    $adjuster = \App\User::where(['id'=> $assessment['claim']['createdBy']])->first();
                                                    ?>
                                                    <td>{{$carDetail->modelName}}</td>
                                                    <td>{{$assessment['claim']['chassisNumber']}}</td>
                                                    <td>{{$assessment['claim']['vehicleRegNo']}}</td>
                                                    <td>{{isset($adjuster) ? $adjuster->firstName.' '.$adjuster->lastName : ''}}</td>
                                                    <td>
                                                        @if($assessment->final_approver)
                                                            {{isset($assessment->final_approver->firstName) ? $assessment->final_approver->firstName : ''}} {{isset($assessment->final_approver->lastName) ? $assessment->final_approver->lastName : ''}}
                                                        @else
                                                            {{isset($assessment->approver->firstName) ? $assessment->approver->firstName : ''}} {{isset($assessment->approver->lastName) ? $assessment->approver->lastName : ''}}
                                                        @endif
                                                    </td>
                                                    <td>{{isset($assessment->assessor) ? $assessment->assessor->firstName.' '.$assessment->assessor->lastName : ''}}</td>
                                                    @if($assessment['assessmentStatusID']  == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn red lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['text'] }}</button>
                                                        </td>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'])
                                                        <td>
                                                            <button
                                                                class="btn orange lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['text']}}</button>
                                                        </td>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn orange lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['text']}}</button>
                                                        </td>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'])
                                                        <td>
                                                            <button
                                                                class="btn orange lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['text']}}</button>
                                                        </td>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn green lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['text']}}</button>
                                                        </td>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id'])
                                                        <td>
                                                            <button
                                                                class="btn red lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['text']}}</button>
                                                        </td>
                                                    @elseif($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['UNDER-INVESTIGATION']['id'])
                                                        <td>
                                                            <button
                                                                class="btn purple lighten-2">{{explode(' ', \App\Conf\Config::$STATUSES['ASSESSMENT']['UNDER-INVESTIGATION']['text'])[1]}}
{{--                                                                class="btn purple lighten-2">{{\App\Conf\Config::$STATUSES['ASSESSMENT']['UNDER-INVESTIGATION']['text']}}--}}
                                                            </button>
                                                        </td>
                                                    @endif
                                                    <?php
                                                    if ($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id']) {
                                                        $date = $assessment['finalApprovedAt'];
                                                    } elseif ($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id']) {
                                                        $date = $assessment['approvedAt'];
                                                    } elseif ($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['id']) {
                                                        $date = $assessment['assessedAt'];
                                                    } elseif ($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id']) {
                                                        $date = $assessment['changeRequestAt'];
                                                    } elseif ($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['UNDER-INVESTIGATION']['id']) {
                                                        $date = $assessment['investigationRequestAt'];
                                                    } else {
                                                        $date = $assessment['dateCreated'];
                                                    }
                                                    ?>
                                                    <td>
                                                        {{\Carbon\Carbon::parse($date)->diffForHumans()}}
                                                    </td>
                                                    <td>
                                                        @if(isset($assessment['totalCost']))
                                                            {{isset($assessment['totalChange']) ? number_format($assessment['totalChange']) : number_format($assessment['totalCost']) }}
                                                        @elseif(isset($assessment['totalLoss']))
                                                             {{isset($assessment['totalChange']) ? number_format($assessment['totalChange']) : number_format($assessment['totalCost']) }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ isset($assessment['assessmentTypeID'])  ?  \App\Conf\Config::DISPLAY_ASSESSMENT_TYPES[$assessment['assessmentTypeID']] : ''}}
                                                    </td>
                                                    <td>
                                                        @if(isset($assessment['assessedAt']))
                                                            {{$assessment['assessedAt']}}
                                                        @endif
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
                                                        $claimForm =\App\Document::where(['claimID'=>$assessment['claimID'],"documentType"=>\App\Conf\Config::$DOCUMENT_TYPES['PDF']['ID'],'pdfType'=>App\Conf\Config::PDF_TYPES['CLAIM_FORM']['ID']])->first();

                                                        $invoiceDoc =\App\Document::where(['claimID'=>$assessment['claimID'],"documentType"=>\App\Conf\Config::$DOCUMENT_TYPES['PDF']['ID'],'pdfType' => App\Conf\Config::PDF_TYPES['INVOICE']['ID']])->first();
                                                        ?>
                                                        <ul id='{{$loop->iteration}}' class='dropdown-content'>
                                                            @if(isset($claimForm->name))
                                                                <li><a href="{{asset('documents/'.$claimForm->name)}}" download><i
                                                                            class="material-icons">file_download</i>Claim Form</a></li>
                                                            @endif
                                                            @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'])
                                                                <?php
                                                                    $userID= isset(Auth::user()->id) ? Auth::user()->id : 0;
                                                                ?>
                                                                    @if($userID == $assessment['assessedBy'])
                                                                        <li>
                                                                            <a href="#" id="fillAssessmentReport"
                                                                               data-id="{{$assessment['id']}}"
                                                                               data-claimid="{{$assessment['claimID']}}"><i
                                                                                    class="material-icons">insert_drive_file</i>Submit
                                                                                Assessment </a>
                                                                        </li>
                                                                    @endif
                                                            @endif
                                                            @if($assessment['assessmentStatusID'] != \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'])
                                                                    <li>
                                                                        <a href="#" id="assessor-assessment-report"
                                                                           data-id="{{$assessment['id']}}"><i
                                                                                class="material-icons">insert_drive_file</i>View
                                                                            Assessment Report </a>
                                                                    </li>
                                                            @endif
                                                            @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                                    @hasanyrole('Head Assessor|Assistant Head|Assessor')
                                                                <li>
                                                                    <a href="#" id="fillReInspectionReport"
                                                                       data-id="{{$assessment['id']}}"><i
                                                                            class="material-icons">insert_drive_file</i>Re-Inspection
                                                                        Report</a></li>
                                                                    @endhasanyrole
                                                                    @if(isset($assessment->reInspection))
                                                                <li>
                                                                    <a href="#"
                                                                       id="assessor-view-re-inspection-report"
                                                                       data-id="{{$assessment['id']}}"><i
                                                                            class="material-icons">insert_drive_file</i>View
                                                                        Re-Inspection
                                                                        Report</a></li>
                                                                    <li><a href="{{url('/adjuster/re-inspection-letter/'.$assessment['id'])}}" target="_blank"><i
                                                                                class="material-icons">picture_as_pdf</i>View
                                                                            Re-inspection Letter</a></li>
                                                                    @endif
                                                            @endif
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
                        </div>
                    </div>
                </div>
            </div>
            @include('common.generic-notification')
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function (){
        $("#vehicle_reg_no").on('keypress',function (e){
            if(e.which == 13){//Enter key pressed
                var fromDate = $("#from_date").val();
                var toDate = $("#to_date").val();
                var regNumber = $("#vehicle_reg_no").val();
                $.ajaxSetup({

                    headers: {

                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    }

                });
                $.ajax({

                    type: 'POST',
                    url: '/common/fetch-re-inspections',
                    data: {
                        fromDate: fromDate,
                        toDate: toDate,
                        regNumber : regNumber
                    },
                    success: function (data) {
                        $("#main").html(data);
                        $('.datepicker').datepicker();
                        $('#data-table-simple').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ],
                            "pageLength": 25
                        });
                    }

                });
            }
        });
    });
</script>
