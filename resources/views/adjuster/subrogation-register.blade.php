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
                                <h4 class="card-title float-left">Subrogate Register</h4>
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
                                            <button class="btn cyan waves-effect waves-light fetch-claims" type="submit" data-id=""
                                                    name="action" >
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
                                            <th>S/N</th>
                                            <th>Claim Number</th>
                                            <th>Registration Number</th>
{{--                                            <th>Make</th>--}}
{{--                                            <th>Model</th>--}}
{{--                                            <th>Chassis Number</th>--}}
{{--                                            <th>Third Party Insurer</th>--}}
                                            <th>Third Party Driver</th>
                                            <th>Third Party Registration Number</th>
                                            <th>Amount</th>
                                            <th>PAV</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($subrogationClaims as $subrogationClaim)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$subrogationClaim->claimNo}}</td>
                                                <td>{{$subrogationClaim->claim->vehicleRegNo}}</td>
{{--                                                <td>{{$subrogationClaim->makeName}}</td>--}}
{{--                                                <td>{{$subrogationClaim->modelName}}</td>--}}
{{--                                                <td>{{$subrogationClaim->chassisNumber}}</td>--}}
{{--                                                <td></td>--}}
                                                <td>{{$subrogationClaim->thirdPartyDriver}}</td>
                                                <td>{{$subrogationClaim->thirdPartyVehicleRegNo}}</td>
                                                <td>{{$subrogationClaim->totalCost}}</td>
                                                <td>{{$subrogationClaim->pav}}</td>
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
        </div>
    </div>
</div>
