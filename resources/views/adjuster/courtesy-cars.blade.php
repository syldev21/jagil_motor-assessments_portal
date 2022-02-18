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
                                <h4 class="card-title float-left">Courtesy Car</h4>
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
                                            <th>Vendor</th>
                                            <th>Claim Number</th>
                                            <th>Policy Number</th>
                                            <th>Number of Days</th>
                                            <th>Return Date</th>
                                            <th>Status</th>
                                            <th>Charge</th>
                                            <th>Total Charge</th>
                                            <th>Process Status</th>
                                            <th>Created By</th>
                                            <th>Date Created</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($courtesyCars as $courtesyCar)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$courtesyCar->vendor->fullName}}</td>
                                                <td>{{$courtesyCar->claim->claimNo}}</td>
                                                <td>{{$courtesyCar->claim->policyNo}}</td>
                                                <td>{{$courtesyCar->numberOfDays}}</td>
                                                <td>{{$courtesyCar->returnDate}}</td>
                                                <td>{{$courtesyCar->status}}</td>
                                                <td>{{$courtesyCar->charge}}</td>
                                                <td>{{$courtesyCar->totalCharge}}</td>
                                                <td>{{$courtesyCar->processStatus}}</td>
                                                <td>{{$courtesyCar->createdBy}}</td>
                                                <td>{{$courtesyCar->dateCreated}}</td>

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
