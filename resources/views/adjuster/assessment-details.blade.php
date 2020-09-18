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
                                <h4 class="card-title float-left">Assessment Details</h4>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="claimNo" type="text" name="claimNo"
                                                   value="{{$assessment->claim->claimNo}}" disabled>
                                            <label for="claimNo" class="active">Claim Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="branch" type="text" name="branch"
                                                   value="{{$assessment->claim->branch}}" disabled>
                                            <label for="branch" class="active">Branch</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="loseDate" type="text" name="loseDate"
                                                   value="{{$assessment->claim->loseDate}}" disabled>
                                            <label for="loseDate" class="active">Lose Date</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="intimationDate" type="text" name="intimationDate"
                                                   value="{{$assessment->claim->intimationDate}}" disabled>
                                            <label for="intimationDate" class="active">Intimation Date</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="policyNo" type="text" name="policyNo"
                                                   value="{{$assessment->claim->policyNo}}" disabled>
                                            <label for="policyNo" class="active">Policy Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="vehicleRegNo" type="text" name="vehicleRegNo"
                                                   value="{{$assessment->claim->vehicleRegNo}}" disabled>
                                            <label for="vehicleRegNo" class="active">Registration Number</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="excess" type="text" name="excess"
                                                   value="{{$assessment->claim->excess}}" disabled>
                                            <label for="excess" class="active">Excess</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="sumInsured" type="text" name="sumInsured"
                                                   VALUE="{{$assessment->claim->sumInsured}}" disabled>
                                            <label for="sumInsured" class="active">Sum Insured</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="claimType" type="text" name="claimType"
                                                   VALUE="{{$assessment->claim->claimType}}" disabled>
                                            <label for="claimType" class="active">Claim Type</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="location" type="text" name="location"
                                                   value="{{$assessment->claim->location}}" disabled>
                                            <label for="location" class="active">Location</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 col m12">
                                    <h4 class="card-title float-left">Insurer Details</h4>
                                </div>
                                <div class="divider"></div>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <input id="customerCode" type="text" name="customerCode"
                                               value="{{$customer->customerCode}}" disabled>
                                        <label for="customerCode" class="active">Client Code</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <input id="fullName" type="text" name="fullName" value="{{$customer->fullName}}"
                                               disabled>
                                        <label for="fullName" class="active">Full Name</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <input id="email" type="text" name="email" value="{{$customer->email}}"
                                               disabled>
                                        <label for="email" class="active">Email</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <input id="msisdn" type="text" name="msisdn" value="{{$customer->MSISDN}}"
                                               disabled>
                                        <label for="msisdn" class="active">Phone Number</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 col m12">
                                    <h4 class="card-title float-left">Asssessor Details</h4>
                                </div>
                                <div class="divider"></div>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <input id="name" type="text" name="name"
                                               value="{{$assessment->user->firstName}}" disabled>
                                        <label for="name" class="active">First Name</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <input id="lastName" type="text" name="lastName"
                                               value="{{$assessment->user->lastName}}" disabled>
                                        <label for="lastName" class="active">Last Name</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col m6 s12">
                                        <input id="MSISDN" type="text" name="MSISDN"
                                               value="{{$assessment->user->MSISDN}}" disabled>
                                        <label for="MSISDN" class="active">Phone Number</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <input id="email" type="text" name="email" value="{{$assessment->user->email}}"
                                               disabled>
                                        <label for="email" class="active">Email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 col m12">
                                    <h4 class="card-title float-left">Claim Documents</h4>
                                </div>
                                <div class="divider"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
