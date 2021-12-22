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
                                <h4 class="card-title float-left"></h4>
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
                                        <input id="policyNumber" type="text" class="validate">
                                        <input id="assessmentStatusID" type="hidden" value="">
                                        <input id="status" type="hidden" value="{{isset($policyStatus) ? $policyStatus : ''}}">
                                        <label for="policyNumber">Policy No</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light" type="submit" id="filter-travel-policies"
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
                                            <th>STS Policy Number</th>
                                            <th>KRA Pin</th>
                                            <th>InsuredName</th>
                                            <th>PassportNo</th>
                                            <th>Underwriter</th>
                                            <th>DepartureDate</th>
                                            <th>Returndate</th>
                                            <th>SumInsured</th>
                                            <th>Status</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($policies as $policy)
                                            <?php $payload = json_decode($policy['sts_payload']); ?>
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$policy['sts_policy_number']}}</td>
                                                <td>{{isset($payload->KRA_Pin) ? $payload->KRA_Pin : ''}}</td>
                                                <td>{{isset($payload->InsuredName) ? $payload->InsuredName : ''}}</td>
                                                <td>{{isset($payload->TravelPassportNo) ? $payload->TravelPassportNo : ''}}</td>
                                                <td>{{isset($payload->Underwriter) ? $payload->Underwriter : ''}}</td>
                                                <td>{{isset($payload->TravelDepartureDate) ? $payload->TravelDepartureDate : ''}}</td>
                                                <td>{{isset($payload->TravelReturndate) ? $payload->TravelReturndate : ''}}</td>
                                                <td>{{isset($payload->SumInsured) ? number_format($payload->SumInsured) : ''}}</td>
                                                <td>
                                                    @if($policy['status'] == App\Conf\Config::TRAVEL_DISPLAY_STATUSES['PROCESSED']['ID'])
                                                        <button
                                                            class="btn green lighten-2">{{App\Conf\Config::TRAVEL_STATUSES[$policy['status']]['TITLE']}}</button>
                                                    @elseif($policy['status'] == App\Conf\Config::TRAVEL_DISPLAY_STATUSES['PENDING']['ID'])
                                                        <button
                                                            class="btn orange lighten-2">{{App\Conf\Config::TRAVEL_STATUSES[$policy['status']]['TITLE']}}</button>
                                                    @elseif($policy['status'] == App\Conf\Config::TRAVEL_DISPLAY_STATUSES['FAILED']['ID'])
                                                        <button
                                                            class="btn red lighten-2">{{App\Conf\Config::TRAVEL_STATUSES[$policy['status']]['TITLE']}}</button>
                                                    @endif
                                                </td>
                                                <td></td>
                                            </tr>
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
