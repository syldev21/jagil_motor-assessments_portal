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
                                <h4 class="card-title float-left">{{\App\Conf\Config::$DISPLAY_STATUSES["CLAIM"][$claimStatusID]}} Claims</h4>
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
                                                    name="action" id="searchClaim">
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
                                            <th>Status</th>
                                            <th>Garage</th>
                                            <th>Assessor</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($claims as $claim)
                                            <form class="assignForm">
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td><a href="#" data-id="{{$claim['id']}}" id="claimDetails">{{$claim['claimNo']}}</a></td>
                                                    <td>{{$claim['vehicleRegNo']}}</td>
                                                    @if($claim['claimStatusID']  == \App\Conf\Config::$STATUSES['CLAIM']['UPLOADED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn red lighten-2">{{\App\Conf\Config::$STATUSES['CLAIM']['UPLOADED']['text'] }}</button>
                                                        </td>
                                                    @elseif($claim['claimStatusID'] == \App\Conf\Config::$STATUSES['CLAIM']['ASSIGNED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn orange lighten-2">{{\App\Conf\Config::$STATUSES['CLAIM']['ASSIGNED']['text']}}</button>
                                                        </td>
                                                    @elseif($claim['claimStatusID'] == \App\Conf\Config::$STATUSES['CLAIM']['RE-INSPECTED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn green lighten-2">{{\App\Conf\Config::$STATUSES['CLAIM']['RE-INSPECTED']['text']}}</button>
                                                        </td>
                                                    @elseif($claim['claimStatusID'] == \App\Conf\Config::$STATUSES['CLAIM']['RELEASED']['id'])
                                                        <td>
                                                            <button
                                                                class="btn green darken-2">{{\App\Conf\Config::$STATUSES['CLAIM']['RELEASED']['text']}}</button>
                                                        </td>
                                                    @endif
                                                    <input type="hidden" name="claimID{{$loop->iteration}}"
                                                           id="claimID{{$loop->iteration}}" value="{{$claim['id']}}"
                                                           class="claimID">
                                                    <?php
                                                    $garage = \App\Garage::where(['id'=>isset($claim['garageID']) ? $claim['garageID'] : ''])->first();
                                                    ?>
                                                    <td>{{$garage->name}}</td>
                                                    <td>
                                                        <div class="input-field">
                                                            <select class="browser-default"
                                                                    id="assessor{{$loop->iteration}}"
                                                                    name="assessor{{$loop->iteration}}" required>
                                                                <option value="">Select Assessor</option>
                                                                @if(count($assessors)>0)
                                                                    <?php
                                                                    $assessorID =0;
                                                                    ?>
                                                                    @foreach($assessors as $assessor)
                                                                        <?php
                                                                        $assessments = $claim->assessment;
                                                                        if(count($assessments)>0)
                                                                            {
                                                                            foreach ($assessments as $assessment) {
                                                                                if ($assessment->assessmentStatusID == \App\Conf\Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id']) {
                                                                                    $assessorID = $assessment->assessedBy;
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <option
                                                                            value="{{$assessor->id}}" @if($assessor->id == $assessorID) selected @endif>{{$assessor->firstName}} {{$assessor->lastName}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <!-- Dropdown Trigger -->
                                                        <a class='dropdown-trigger' href='#'
                                                           data-target='{{$loop->iteration}}'
                                                           data-activates="{{$loop->iteration}}"><i
                                                                class="Medium material-icons">menu</i><i
                                                                class="Medium material-icons">expand_more</i></a>

                                                        <!-- Dropdown Structure -->

                                                        <?php
                                                        $claimForm =\App\Document::where(['claimID'=>$claim['id'],"documentType"=>\App\Conf\Config::$DOCUMENT_TYPES['PDF']['ID'],'pdfType'=>App\Conf\Config::PDF_TYPES['CLAIM_FORM']['ID']])->first();
                                                        ?>

                                                        <ul id='{{$loop->iteration}}' class='dropdown-content'>
                                                            @if($claim['claimStatusID'] == \App\Conf\Config::$STATUSES['CLAIM']['ASSIGNED']['id'])
                                                                <li><a href="#"
                                                                       onclick="reAssignAssessor({{$loop->iteration}})"><i
                                                                            class="material-icons">assignment_late</i>Re-assign
                                                                        Assessor</a></li>
                                                            @else
                                                                <li><a href="#"
                                                                       onclick="assignAssessor({{$loop->iteration}})"><i
                                                                            class="material-icons">assignment_ind</i>Assign
                                                                        Assessor</a></li>
                                                            @endif
                                                                @if(isset($claimForm->name))
                                                                    <li>
                                                                        <a href="{{asset('documents/'.$claimForm->name)}}" download><i
                                                                                class="material-icons">file_download</i> Claim Form</a></li>
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
