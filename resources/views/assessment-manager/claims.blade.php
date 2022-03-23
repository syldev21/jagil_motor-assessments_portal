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
                                            <button class="btn cyan waves-effect waves-light" type="submit" data-id="{{isset($claimStatusID) ? $claimStatusID : ''}}"
                                                    name="action" id="filter-assessment-manager-claims">
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
                                            <th>Intimation Date</th>
                                            <th>Registration Number</th>
                                            <th>Adjuster</th>
                                            @if($claimStatusID != \App\Conf\Config::$STATUSES['CLAIM']['UPLOADED']['id'])
                                                <th class="center-align">Assessor</th>
                                            @endif
                                            <th>Status</th>
                                            <th>Sum Insured</th>
                                            <th>Created</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($claims as $claim)
                                            <form class="assignForm">
                                                <tr @if($claim->changed == 1) style="padding: 14px;border-left: 2px solid #ee6e73;" @else style="padding: 14px;border-left: 2px solid #37ad52;" @endif>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>
                                                        <a href="#" data-id="{{$claim['id']}}" id="claimDetails">{{$claim['claimNo']}}</a>
                                                    </td>
                                                    <td>{{$claim['intimationDate']}}</td>
                                                    <td>{{$claim['vehicleRegNo']}}</td>
                                                    <td>{{isset($claim['adjuster']->name) ? $claim['adjuster']->name : ''}}</td>
                                                    @if($claimStatusID != \App\Conf\Config::$STATUSES['CLAIM']['UPLOADED']['id'])
                                                        <?php
                                                        $assessment=\App\Assessment::where('claimID',$claim['id'])->first();
                                                        $assessor = \App\User::where(['id'=>isset($assessment->assessedBy) ? $assessment->assessedBy : ''])->first();
                                                        ?>
                                                        <td>{{isset($assessor->name) ? $assessor->name : ''}}</td>
                                                    @endif

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
                                                           id="claimID{{$loop->iteration}}"
                                                           value="{{$claim['claimID']}}" class="claimID">
                                                    <td>
                                                        {{$claim['sumInsured']}}
                                                    </td>
                                                    <td>
                                                        {{\Carbon\Carbon::parse($claim['dateCreated'])->diffForHumans()}}
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
                                                            @if(isset($claimForm->name))
                                                                <li>
                                                                    <a href="{{asset('documents/'.$claimForm->name)}}" download><i
                                                                            class="material-icons">file_download</i> Claim Form</a></li>
                                                            @endif
                                                            @if($claim->changed == 1)
                                                                <li><a href="#" data-id="{{$claim['id']}}" id="claimExceptionDetail"><i
                                                                            class="material-icons">picture_as_pdf</i>
                                                                        Exception Report</a></li>
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

