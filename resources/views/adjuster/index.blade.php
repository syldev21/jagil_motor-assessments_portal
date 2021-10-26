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
                                <h4 class="card-title float-left">Claims</h4>
                            </div>
                            <div class="row">
                                <div class="row">
                                    <div class="input-field col m3 s6">
                                        <i class="material-icons prefix">access_time</i>
                                        <input id="fromDate" type="text" class="validate datepicker">
                                        <label for="fromDate">From Date</label>
                                    </div>
                                    <div class="input-field col m3 s6">
                                        <i class="material-icons prefix">access_time</i>
                                        <input id="toDate" type="text" class="validate datepicker">
                                        <label for="toDate">To Date</label>
                                    </div>
                                    <div class="input-field col m3 s6">
                                        <i class="material-icons prefix">vpn_key</i>
                                        <input id="vehicleRegNo" type="text" class="validate">
                                        <label for="vehicleRegNo">Vehicle Reg No</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light"
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
                                            <th>Intimation Date</th>
                                            <th>Claim Type</th>
                                            <th>Sum Insured</th>
                                            {{--                                                <th>Operation</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($claims as $claim)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    <a href="#" data-id="{{json_encode($claim)}}" id="claimForm">{{$claim['CLM_NO']}}</a>
                                                </td>
                                                <td>{{$claim['VEH_REG_NO']}}</td>
                                                <td>{{$claim['CLM_INTM_DT']}}</td>
                                                <td>{{$claim['CLAIM_TYPE']}}</td>
                                                <td>{{$claim['SUM_INSURED']}}</td>
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
