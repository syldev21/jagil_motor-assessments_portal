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
                                <h4 class="card-title float-left">Add new Claim</h4>
                                {{--                                    <a href="{{ url('adjuster/claims') }}" class="float-right btn cyan waves-effect waves-effect waves-light"><i class="material-icons left">visibility</i> View Claims</a>--}}
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="claimNo" type="text" name="claimNo"
                                                   value="{{$claim['CLM_NO']}}" disabled>
                                            <label for="claimNo" class="active">Claim Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="branch" type="text" name="branch"
                                                   value="{{$claim['BRANCH']}}" disabled>
                                            <label for="branch" class="active">Branch</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="loseDate" type="text" name="loseDate"
                                                   value="{{$claim['CLM_LOSS_DT']}}" disabled>
                                            <label for="loseDate" class="active">Lose Date</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <i class="material-icons prefix">date_range</i>
                                            <input placeholder="" id="intimationDate" type="text" name="intimationDate"
                                                   value="{{$claim['CLM_INTM_DT']}}" disabled>
                                            <label for="intimationDate" class="active">Intimation Date</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="policyNo" type="text" name="policyNo"
                                                   value="{{$claim['CLM_POL_NO']}}" disabled>
                                            <label for="policyNo" class="active">Policy Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="vehicleRegNo" type="text" name="vehicleRegNo"
                                                   value="{{$claim['VEH_REG_NO']}}" disabled>
                                            <label for="vehicleRegNo" class="active">Registration Number</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="excess" type="text" name="excess"
                                                   value="{{$claim['EXCESS_AMT']}}">
                                            <label for="excess" class="active">Excess</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="sumInsured" type="text" name="sumInsured"
                                                   VALUE="{{$claim['SUM_INSURED']}}">
                                            <label for="sumInsured" class="active">Sum Insured</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="claimType" type="text" name="claimType"
                                                   VALUE="{{$claim['CLAIM_TYPE']}}" disabled>
                                            <label for="claimType" class="active">Claim Type</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="customerCode" type="text" name="customerCode"
                                                   value="{{$claim['CUST_CODE']}}" disabled>
                                            <label for="customerCode" class="active">Client Code</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="location" type="text" name="location">
                                            <label for="location" class="active">Location</label>
                                        </div>
                                        <input type="hidden" name="email" id="email" value="{{$claim['CUST_EMAIL1']}}">
                                        <input type="hidden" name="fullName" id="fullName"
                                               value="{{$claim['CUST_NAME']}}">
                                        <input type="hidden" name="MSISDN" id="MSISDN"
                                               value="{{$claim['CUST_MOBILE_NO']}}">
                                        <div class="input-field col m4 s12">
                                            <a href="#"
                                               class="float-right btn cyan waves-effect waves-effect waves-light"
                                               id="addClaim"> <i class="material-icons right">send</i>Submit</a>
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
