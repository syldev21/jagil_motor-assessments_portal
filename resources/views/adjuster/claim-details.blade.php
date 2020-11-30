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
                                <h4 class="card-title float-left">Claim Details</h4>
                                {{--                                    <a href="{{ url('adjuster/uploadClaims') }}" class="float-right btn cyan waves-effect waves-effect waves-light"><i class="material-icons left">visibility</i> View Claims</a>--}}
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
                                            <input placeholder="" id="carMake" type="text" name="carMake"
                                                   value="{{$carDetails->makeName}}" disabled>
                                            <input type="hidden" name="carMakeCode" id="carMakeCode" value="{{$claim->carMakeCode}}">
                                            <label for="carMake" class="active">Car Make</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="carModel" type="text" name="carModel"
                                                   value="{{$carDetails->modelName}}" disabled>
                                            <input type="hidden" name="carModelCode" id="carModelCode" value="{{$claim->carModelCode}}">
                                            <label for="carModel" class="active">Car Model</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="yom" type="text" name="yom"
                                                   value="{{$claim->yom}}" disabled>
                                            <label for="yom" class="active">Year Of Make</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="engineNumber" type="text" name="engineNumber"
                                                   value="{{$claim->engineNumber}}" disabled>
                                            <label for="engineNumber" class="active">Engine Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="chassisNumber" type="text" name="chassisNumber"
                                                   value="{{$claim->chassisNumber}}" disabled>
                                            <label for="chassisNumber" class="active">Chassis Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="claimType" type="text" name="claimType"
                                                   VALUE="{{$claim->claimType}}" disabled>
                                            <label for="claimType" class="active">Claim Type</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="excess" type="text" name="excess" value="{{$claim->excess}}"
                                                   disabled>
                                            <label for="excess" class="active">Excess</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="sumInsured" type="text" name="sumInsured"
                                                   VALUE="{{$claim->sumInsured}}" disabled>
                                            <label for="sumInsured" class="active">Sum Insured</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="location" type="text" name="location"
                                                   value="{{\App\Location::where(["id"=>$claim->location])->first()->name }}" disabled>
                                            <label for="location" class="active">Location</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if(isset($claim->customer))
                                        <input type="hidden" name="email" id="email"
                                               value="{{$claim->customer->email}}">
                                        <input type="hidden" name="fullName" id="fullName"
                                               value="{{$claim->customer->fullName}}">
                                        <input type="hidden" name="MSISDN" id="MSISDN"
                                               value="{{$claim->customer->MSISDN}}">
                                        <div class="input-field col m4 s12">
                                        @endif
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col s12">
                                        <h4 class="card-title float-left">Insurer Details</h4>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="row">
                                        <div class="input-field col m6 s12">
                                            <input id="customerCode" type="text" name="claimType"
                                                   VALUE="{{$claim->customerCode}}" disabled>
                                            <label for="customerCode" class="active">Client Code</label>
                                        </div>
                                        <div class="input-field col m6 s12">
                                            <input id="fullName" type="text" name="fullName"
                                                   value="{{ isset($claim->customer) ? $claim->customer->fullName : null}}" disabled>
                                            <label for="fullName" class="active">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m6 s12">
                                            <input id="email" type="text" name="email" value="{{isset($claim->customer) ? $claim->customer->email : null}}"
                                                   disabled>
                                            <label for="email" class="active">Email</label>
                                        </div>
                                        <div class="input-field col m6 s12">
                                            <input id="msisdn" type="text" name="msisdn"
                                                   value="{{isset($claim->customer) ? $claim->customer->MSISDN : null}}" disabled>
                                            <label for="msisdn" class="active">Phone Number</label>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($assessment->assessor))
                                    <div class="row">
                                        <div class="col s12 col m12">
                                            <h4 class="card-title float-left">Asssessor Details</h4>
                                        </div>
                                        <div class="divider"></div>
                                            <div class="row">
                                                <div class="input-field col m6 s12">
                                                    <input id="name" type="text" name="name"
                                                           value="{{$assessment->assessor->firstName}}" disabled>
                                                    <label for="name" class="active">First Name</label>
                                                </div>
                                                <div class="input-field col m6 s12">
                                                    <input id="lastName" type="text" name="lastName"
                                                           value="{{$assessment->assessor->lastName}}" disabled>
                                                    <label for="lastName" class="active">Last Name</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col m6 s12">
                                                    <input id="MSISDN" type="text" name="MSISDN"
                                                           value="{{$assessment->assessor->MSISDN}}" disabled>
                                                    <label for="MSISDN" class="active">Phone Number</label>
                                                </div>
                                                <div class="input-field col m6 s12">
                                                    <input id="email" type="text" name="email"
                                                           value="{{$assessment->assessor->email}}" disabled>
                                                    <label for="email" class="active">Email</label>
                                                </div>
                                            </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col s12 col m12">
                                        <h4 class="card-title float-left">Claim Documents</h4>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="row">
                                        @if(count($claim->documents) > 0)
                                            @foreach($claim->documents as $document)
                                                <div class="col s4">
                                                    <img class="responsive-img" src="{{url('documents/'.$document->name) }}">
                                                </div>
                                            @endforeach
                                        @endif
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
