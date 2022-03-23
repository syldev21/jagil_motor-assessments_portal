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
                                <h4 class="card-title float-left">{{\App\Conf\Config::DISPLAY_ASSESSMENT_TYPES[$assessmentTypeID]}} Assessments</h4>
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
                                                    name="action" id="filter-claims-by-type" data-id="{{isset($assessmentTypeID) ? $assessmentTypeID : ''}}">
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
                                            <th>Insured</th>
                                            <th>Registration Number</th>
                                            <th>Loss Date</th>
                                            <th>FNOL date</th>
                                            <th>Claim Close date</th>
                                            @if($assessmentTypeID == App\Conf\Config::ASSESSMENT_TYPES['TOTAL_LOSS'])
                                            <th>Location</th>
                                            <th>Sold date</th>
                                            <th>Salvage Amount</th>
                                            <th>Retained</th>
                                            <th>Salvage Booked date</th>
                                            <th>Auctioned</th>
                                            <th>Documenting</th>
                                            <th>Salvage Reserve</th>
                                            @endif
                                            <th>Chassis Number</th>
                                            <th>Engine Number</th>
                                            <th>Make</th>
                                            <th>Model</th>
                                            <th>Sum Insured</th>
                                            @if($assessmentStatusID == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                <th>Approver</th>
                                                <th>Final Approver</th>
                                            @endif
                                            <th>Assessor</th>
                                            <th>Status</th>
                                            <th>{{\App\Conf\Config::$DISPLAY_STATUSES["ASSESSMENT"][$assessmentStatusID]}}</th>
                                            <th>Type</th>
                                            <th>PAV</th>
                                            <th>Amount</th>
                                            <th>Assessed At</th>
                                            <th>Operation</th>
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
                                                    <?php
                                                    $customer = \App\CustomerMaster::where(['customerCode'=>$assessment['claim']['customerCode']])->first();
                                                    $carMakeModel = \App\CarModel::where(["makeCode"=>$assessment['claim']['carMakeCode'],"modelCode"=>$assessment['claim']['carModelCode']])->first();
                                                    ?>
                                                    <td>{{$customer->fullName}}</td>
                                                    <?php $date = ''?>
                                                    <td>{{$assessment['claim']['vehicleRegNo']}}</td>
                                                    <td>{{$assessment['claim']['loseDate']}}</td>
                                                    <td>{{$assessment['claim']['intimationDate']}}</td>
                                                    <td>N/A</td>
                                                    @if($assessmentTypeID == App\Conf\Config::ASSESSMENT_TYPES['TOTAL_LOSS'])
                                                        <?php
                                                        $salvageRegister = \App\SalvageRegister::where(['claimID'=>$assessment['claim']['id']])->first();
                                                        ?>
                                                        <td>{{isset($salvageRegister->location) ? $salvageRegister->location : ''}}</td>
                                                        <td>{{isset($salvageRegister->dateModified) ? $salvageRegister->dateModified : ''}}</td>
                                                        <td>{{isset($salvageRegister->cost) ? $salvageRegister->cost : ''}}</td>
                                                        <td>
                                                            @if(isset($salvageRegister->insuredInterestedWithSalvage))
                                                            @if($salvageRegister->insuredInterestedWithSalvage == App\Conf\Config::YES_OR_NO['YES']['ID'])
                                                                <b class="green-text text-darken-3">{{App\Conf\Config::YES_OR_NO['YES']['TEXT']}}</b>
                                                            @else
                                                                <b class="red-text text-darken-3">{{App\Conf\Config::YES_OR_NO['NO']['TEXT']}}</b>
                                                            @endif
                                                            @endif
                                                        </td>
                                                        <td>{{isset($salvageRegister->dateCreated) ? $salvageRegister->dateCreated : ''}}</td>
                                                        <td>
                                                            @if(isset($salvageRegister->cost))
                                                                <b class="green-text text-darken-3">{{App\Conf\Config::YES_OR_NO['YES']['TEXT']}}</b>
                                                            @else
                                                                <b class="red-text text-darken-3">{{App\Conf\Config::YES_OR_NO['NO']['TEXT']}}</b>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($salvageRegister->recordsReceived))
                                                            @if($salvageRegister->recordsReceived == App\Conf\Config::YES_OR_NO['YES']['ID'])
                                                                <b class="green-text text-darken-3">{{App\Conf\Config::YES_OR_NO['YES']['TEXT']}}</b>
                                                            @else
                                                                <b class="red-text text-darken-3">{{App\Conf\Config::YES_OR_NO['NO']['TEXT']}}</b>
                                                            @endif
                                                            @endif
                                                        </td>
                                                        <td>{{$assessment['salvage']}}</td>
                                                    @endif
                                                    <td>{{$assessment['claim']['chassisNumber']}}</td>
                                                    <td>{{$assessment['claim']['engineNumber']}}</td>
                                                    <td>{{isset($carMakeModel->makeName) ? $carMakeModel->makeName : ''}}</td>
                                                    <td>{{isset($carMakeModel->modelName) ? $carMakeModel->modelName : ''}}</td>
                                                    <td>{{$assessment['claim']['sumInsured']}}</td>
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
                                                    @endif
                                                    <td>
                                                        {{\Carbon\Carbon::parse($date)->diffForHumans()}}
                                                    </td>
                                                    <td>
                                                        {{ isset($assessment['assessmentTypeID'])  ?  \App\Conf\Config::DISPLAY_ASSESSMENT_TYPES[$assessment['assessmentTypeID']] : ''}}
                                                    </td>
                                                    <td>{{ number_format($assessment['pav']) }}</td>
                                                    <td>
                                                        @if(isset($assessment['totalCost']))
                                                            {{isset($assessment['totalChange']) ? number_format($assessment['totalChange']) : number_format($assessment['totalCost']) }}
                                                        @elseif(isset($assessment['totalLoss']))
                                                            {{isset($assessment['totalChange']) ? number_format($assessment['totalChange']) : number_format($assessment['totalCost']) }}
                                                        @endif
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
                                                        $claimForm =\App\Document::where(['claimID'=>$assessment['claimID'],"documentType"=>\App\Conf\Config::$DOCUMENT_TYPES['PDF']['ID']])->first();
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
                                                            @if($assessment['assessmentStatusID'] == \App\Conf\Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                                                                    <li><a href="#!"><i
                                                                                class="material-icons">compare_arrows</i>View
                                                                            Re-inspection</a></li>
                                                                    <li><a href="{{url('/adjuster/re-inspection-letter/'.$assessment['id'])}}" target="_blank"><i
                                                                        class="material-icons">picture_as_pdf</i>View
                                                                    Re-inspection Letter</a></li>
                                                            <li><a href="{{url('/adjuster/send-release-letter/'.$assessment['claimID'])}}" data-id="{{$assessment['claimID']}}" id=""><i
                                                                        class="material-icons">picture_as_pdf</i>
                                                                    Release Letter</a></li>
                                                                @can(App\Conf\Config::PERMISSIONS['PROCESS_SALVAGE'])
                                                                    <?php
                                                                        $salvageProcessed = isset($assessment['claim']['salvageProcessed']) ? $assessment['claim']['salvageProcessed'] : 0;
                                                                    ?>
                                                                    @if($salvageProcessed != \App\Conf\Config::ACTIVE)
                                                                    <li><a href="#" data-id="{{$assessment['claimID']}}" id="processSalvage"><i
                                                                                class="material-icons">attach_money</i>
                                                                            Process Salvage</a></li>
                                                                    @endif
                                                                @endcan
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
            <div class="row">
                <div class="col s2"></div>
                <div class="col s8">
                    <!-- Modal Structure -->
                    <div id="processSalvageModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="input-field col m12 s12">
                                        <div class="row">
                                            <div class="col s12">
                                                <span class="float-left" style="font-size: 1.6em">Initiate Salvage for Processing</span>
                                                <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <select id="logbookReceived" required name="logbookReceived">
                                            <option value="0">NO</option>
                                            <option value="1">YES</option>
                                        </select>
                                        <label for="logbookReceived">Logbook Received</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <select id="documentsReceived" required name="documentsReceived">
                                            <option value="0">NO</option>
                                            <option value="1">YES</option>
                                        </select>
                                        <label for="logbookReceived">Documents Received</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <input type="text" id="location" name="location">
                                        <label for="location">Location</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <input type="text" id="dateRecovered" name="dateRecovered" class="datepicker">
                                        <label for="dateRecovered">Date Recovered</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <select id="insuredInterestedWithSalvage" required name="insuredInterestedWithSalvage">
                                            <option value="0">NO</option>
                                            <option value="1">YES</option>
                                        </select>
                                        <label for="insuredInterestedWithSalvage">Insured interested with salvage</label>
                                    </div>
                                    <div class="input-field col m6 s12">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m8 s12">
                                    </div>
                                    <div class="input-field col m4 s12">
                                        <input type="hidden" id="claimID" >
                                        <a href="#" class="btn blue lighten-2 waves-effect" id="submitSalvageRequest">Submit</a>
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


