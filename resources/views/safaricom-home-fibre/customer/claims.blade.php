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
                                <h4 class="card-title float-left">
                                    {{isset(auth::user()->ci_code)?"My Claims":"All Claims"}}
                                </h4>

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
                                        <input id="claimNo" type="text" class="validate">
                                        <input id="claimStatusID" type="hidden" value="">
                                        <label for="claimNo">Claim No</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light" type="submit" id="filter_nhif_claims"
                                                    name="action">
                                                <i class="material-icons left">search</i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="row ">
                                <div class="col s12 ">
                                    <table id="data-table-simple" class="display">
                                        <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Customer Name</th>
                                            <th>Customer Code</th>
                                            <th>Policy Number</th>
                                            @if(auth::user()->ci_code != null)
                                                <th>Policy From Date</th>
                                                <th>Policy To Date</th>
                                            @endif
                                            <th>Claim Description</th>
                                            <th>Claim Submission Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(auth::user()->ci_code != null)
                                        @foreach($claims as $claim)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$claim->name}}</td>
                                                <td>{{isset($policies[0]["assured_code"])?$policies[0]["assured_code"]:""}}</td>
                                                <td>{{isset($policies[0]['policy_number'])?$policies[0]['policy_number']:""}}</td>
                                                <td>{{isset($policies[0]["from_date"])?$policies[0]["from_date"]:""}}</td>
                                                <td>{{isset($policies[0]["to_date"])?$policies[0]["to_date"]:""}}</td>
                                                <td>{{$claim->lossDescription}}</td>
                                                <td>{{$claim['dateCreated']}}</td>
                                            </tr>
                                        @endforeach
                                        @else
                                        @foreach($claims as $claim)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$claim->name}}</td>
                                                <td>{{$claim->assured_code}}</td>
                                                <td>{{$claim->policy_number}}</td>
                                                <td>{{$claim->lossDescription}}</td>
                                                <td>{{$claim->dateCreated}}</td>
                                            </tr>
                                        @endforeach
                                        @endif
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
