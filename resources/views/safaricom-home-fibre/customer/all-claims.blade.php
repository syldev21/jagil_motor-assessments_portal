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
                                    My Claims
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
                                            <th>Mobile Number</th>
                                            <th>Policy Number</th>
                                            <th>Policy No Date</th>
                                            <th>Policy expiry date Date</th>
                                            <th>Last payment date</th>
                                            <th>Claim lodged</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($customers as $customer)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$customer->name}}</td>
                                                <td>{{$customer->MSISDN}}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>{{{\App\Conf\Config::YES_OR_NO['NO']['TEXT']}}}</td>
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
