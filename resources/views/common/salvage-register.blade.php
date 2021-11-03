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
                                <h4 class="card-title float-left"> Salvage Register</h4>
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
                                        <input id="assessmentStatusID" type="hidden" value="">
                                        <label for="vehicle_reg_no">Reg No</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light" type="submit" id="filter-assessor-assessments"
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
                                            <th>Buyer Name</th>
                                            <th>Buyer Email</th>
                                            <th>Buyer Phone</th>
                                            <th>Location</th>
                                            <th>Date Recovered</th>
                                            <th>Registration Number</th>
                                            <th>Claim Number</th>
                                            <th>Make</th>
                                            <th>Model</th>
                                            <th>Chassis Number</th>
                                            <th>Received Logbook</th>
                                            <th>Received Documents</th>
                                            <th>Insured Interested with Salvage</th>
                                            <th>PAV</th>
                                            <th>Salvage Estimate</th>
                                            <th>Salvage Sold</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($salvageRegisters as $salvageRegister)
                                            <form class="assignForm">
                                                <tr>
                                                    <td>
                                                        {{$loop->iteration}}
                                                    </td>
                                                    <td>
                                                        {{isset($salvageRegister->vendor->fullName) ? $salvageRegister->vendor->fullName : ''}}
                                                    </td>
                                                    <td>
                                                        {{isset($salvageRegister->vendor->email) ? $salvageRegister->vendor->email : ''}}
                                                    </td>
                                                    <td>
                                                        {{isset($salvageRegister->vendor->MSISDN) ? $salvageRegister->vendor->MSISDN : ''}}
                                                    </td>
                                                    <td>
                                                        {{isset($salvageRegister->location) ? $salvageRegister->location : ''}}
                                                    </td>
                                                    <td>
                                                        {{isset($salvageRegister->dateRecovered) ? $salvageRegister->dateRecovered : ''}}
                                                    </td>
                                                    <td>
                                                        {{$salvageRegister->vehicleRegNo}}
                                                    </td>
                                                    <td>
                                                        {{$salvageRegister->claimNo}}
                                                    </td>
                                                    <?php
                                                    $carMakeModel = \App\CarModel::where(["makeCode"=>$salvageRegister->claim->carMakeCode,"modelCode"=>$salvageRegister->claim->carModelCode])->first();
                                                    ?>
                                                    <td>
                                                        {{$carMakeModel->makeName}}
                                                    </td>
                                                    <td>
                                                        {{$carMakeModel->modelName}}
                                                    </td>
                                                    <td>
                                                        {{$salvageRegister->claim->chassisNumber}}
                                                    </td>
                                                    <td>
                                                        @if($salvageRegister->logbookReceived == App\Conf\Config::YES_OR_NO['YES']['ID'])
                                                            <b class="green-text text-darken-3 text">{{App\Conf\Config::YES_OR_NO['YES']['TEXT']}}</b>
                                                        @else
                                                            <span class="red-text text-darken-3">{{App\Conf\Config::YES_OR_NO['NO']['TEXT']}}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($salvageRegister->recordsReceived == App\Conf\Config::YES_OR_NO['YES']['ID'])
                                                            <b class="green-text text-darken-3">{{App\Conf\Config::YES_OR_NO['YES']['TEXT']}}</b>
                                                        @else
                                                            <b class="red-text text-darken-3">{{App\Conf\Config::YES_OR_NO['NO']['TEXT']}}</b>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($salvageRegister->insuredInterestedWithSalvage == App\Conf\Config::YES_OR_NO['YES']['ID'])
                                                            <b class="green-text text-darken-3">{{App\Conf\Config::YES_OR_NO['YES']['TEXT']}}</b>
                                                        @else
                                                            <b class="red-text text-darken-3">{{App\Conf\Config::YES_OR_NO['NO']['TEXT']}}</b>
                                                        @endif
                                                    </td>
                                                    <td>{{$salvageRegister->assessment->pav}}</td>
                                                    <td>{{$salvageRegister->assessment->salvage}}</td>
                                                    <td>
                                                        {{isset($salvageRegister->cost) ? $salvageRegister->cost : ''}}
                                                    </td>
                                                    <input type="hidden" name="claimID{{$loop->iteration}}"
                                                           id="claimID{{$loop->iteration}}"
                                                           value="{{$salvageRegister->claimID}}" class="claimID">

                                                    <td>
                                                        <!-- Dropdown Trigger -->
                                                        <a class='dropdown-trigger' href='#'
                                                           data-target='{{$loop->iteration}}'
                                                           data-activates="{{$loop->iteration}}"><i
                                                                class="Medium material-icons">menu</i><i
                                                                class="Medium material-icons">expand_more</i></a>

                                                        <!-- Dropdown Structure -->
                                                        <ul id='{{$loop->iteration}}' class='dropdown-content'>
                                                            @if(!isset($salvageRegister->buyerID))
                                                                @can(App\Conf\Config::PERMISSIONS['SALE_SALVAGE'])
                                                                    <li>
                                                                        <a href="#" id="triggerSaleSalvageModal" data-id="{{$salvageRegister->id}}"><i
                                                                                class="material-icons">attach_money</i>Sale Salvage</a>
                                                                    </li>
                                                                @endcan
                                                            @endif
                                                                @if(isset($salvageRegister->buyerID))
                                                                    <li>
                                                                        <a href="#" id="salvage-release-letter" data-id="{{$salvageRegister->id}}"><i
                                                                                class="material-icons">attach_file</i>salvage Release Report</a>
                                                                    </li>
                                                                @endif
                                                            <li>
                                                                <a href="#" id="triggerNotification" data-id="{{$salvageRegister->assessment->id}}"><i
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
            <div class="row">
                <div class="col s2"></div>
                <div class="col s8">
                    <!-- Modal Structure -->
                    <div id="saleSalvageModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="input-field col m12 s12">
                                        <div class="row">
                                            <div class="col s12">
                                                <span class="float-left" style="font-size: 1.6em">Sale Salvage</span>
                                                <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <select id="vendor" required name="vendor">
                                            <?php
                                            $vendors = \App\Vendor::all();
                                            ?>
                                            @foreach($vendors as $vendor)
                                                    <option value="{{$vendor->id}}">{{$vendor->fullName}}</option>
                                            @endforeach
                                        </select>
                                        <label for="vendor">Buyer</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <select id="logbookReceivedByRecoveryOfficer" required name="logbookReceivedByRecoveryOfficer">
                                            <option value="0">NO</option>
                                            <option value="1">YES</option>
                                        </select>
                                        <label for="logbookReceivedByRecoveryOfficer">Logbook Received</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <input type="text" name="cost" id="cost">
                                        <label for="location">Cost</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m8 s12">
                                    </div>
                                    <div class="input-field col m4 s12">
                                        <input type="hidden" id="salvageID" >
                                        <a href="#" class="btn blue lighten-2 waves-effect" id="submitSaleSalvageRequest">Submit</a>
                                        <a href="#" class="modal-action modal-close btn red lighten-2 waves-effect">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s2"></div>
            </div>
            @include('common.generic-notification')
        </div>
    </div>
</div>
