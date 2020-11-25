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
                                                    <td>{{$assessment['claim']['claimNo']}}</td>
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
                                                    @endif
                                                    <td>
                                                        {{\Carbon\Carbon::parse($date)->diffForHumans()}}
                                                    </td>
                                                    <td>
                                                        {{\App\Conf\Config::DISPLAY_ASSESSMENT_TYPES[$assessment['assessmentTypeID']]}}
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

                                                        <ul id='{{$loop->iteration}}' class='dropdown-content'>
                                                            @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'])
                                                                <li><a href="#" data-id="{{$assessment->id}}" id="head-assessor-assessment-report"><i
                                                                            class="material-icons">picture_as_pdf</i>View
                                                                        Assessment Report</a></li>
                                                            @endif
                                                            <li><a href="#!"><i
                                                                        class="material-icons">compare_arrows</i>View
                                                                    Re-inspection</a></li>
                                                            <li><a href="#!"><i
                                                                        class="material-icons">picture_as_pdf</i>View
                                                                    Re-inspection Letter</a></li>
                                                            <li><a href="#!"><i
                                                                        class="material-icons">picture_as_pdf</i>
                                                                    Release Letter</a></li>
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
