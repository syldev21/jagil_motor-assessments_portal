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
                                <h4 class="card-title float-left">Edit Claim</h4>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="claimNo" type="text" name="claimNo"
                                                   value="{{$claim->claimNo}}" disabled>
                                            <label for="claimNo" class="active">Claim Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="branch" type="text" name="branch"
                                                   value="{{$claim->branch}}" disabled>
                                            <label for="branch" class="active">Branch</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="loseDate" type="text" name="loseDate"
                                                   value="{{$claim->loseDate}}" disabled>
                                            <label for="loseDate" class="active">Lose Date</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <i class="material-icons prefix">date_range</i>
                                            <input placeholder="" id="intimationDate" type="text" name="intimationDate"
                                                   value="{{$claim->intimationDate}}" disabled>
                                            <label for="intimationDate" class="active">Intimation Date</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="policyNo" type="text" name="policyNo"
                                                   value="{{$claim->policyNo}}" disabled>
                                            <label for="policyNo" class="active">Policy Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="vehicleRegNo" type="text" name="vehicleRegNo"
                                                   value="{{$claim->vehicleRegNo}}" disabled>
                                            <label for="vehicleRegNo" class="active">Registration Number</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="excess" type="text" name="excess" value="{{$claim->excess}}">
                                            <label for="excess" class="active">Excess</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="sumInsured" type="text" name="sumInsured"
                                                   VALUE="{{$claim->sumInsured}}">
                                            <label for="sumInsured" class="active">Sum Insured</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="claimType" type="text" name="claimType"
                                                   VALUE="{{$claim->claimType}}" disabled>
                                            <label for="claimType" class="active">Claim Type</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="customerCode" type="text" name="customerCode"
                                                   value="{{$claim->customerCode}}" disabled>
                                            <label for="customerCode" class="active">Client Code</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="location" type="text" name="location"
                                                   VALUE="{{$claim->location}}">
                                            <label for="location" class="active">Location</label>
                                            <input type="hidden" value="{{$claim->id}}" name="claimID" id="claimID">
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <a href="#"
                                               class="float-right btn cyan waves-effect waves-effect waves-light"
                                               id="updateClaim"> <i class="material-icons right">send</i>Update</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
