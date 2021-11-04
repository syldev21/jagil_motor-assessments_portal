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
                                            <input placeholder="" id="carMake" type="text" name="carMake"
                                                   value="{{$carDetails->makeName}}" disabled>
                                            <input type="hidden" name="carMakeCode" id="carMakeCode" value="{{$claim['VEH_MAKE']}}">
                                            <label for="carMake" class="active">Car Make</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="carModel" type="text" name="carModel"
                                                   value="{{$carDetails->modelName}}" disabled>
                                            <input type="hidden" name="carModelCode" id="carModelCode" value="{{$claim['VEH_MODEL']}}">
                                            <label for="carModel" class="active">Car Model</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="yom" type="text" name="yom"
                                                   value="{{$claim['VEH_MFG_YR']}}" disabled>
                                            <label for="yom" class="active">Year Of Make</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="engineNumber" type="text" name="engineNumber"
                                                   value="{{$claim['VEH_ENG_NO']}}" disabled>
                                            <label for="engineNumber" class="active">Engine Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="chassisNumber" type="text" name="chassisNumber"
                                                   value="{{$claim['VEH_CHASSIS_NO']}}" disabled>
                                            <label for="chassisNumber" class="active">Chassis Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="claimType" type="text" name="claimType"
                                                   VALUE="{{$claim['CLAIM_TYPE']}}" disabled>
                                            <label for="claimType" class="active">Claim Type</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input type="hidden" value="{{$claim['EXCESS_AMT']}}" id="originalExcess">
                                            <input id="excess" type="text" name="excess"
                                                   value="{{$claim['EXCESS_AMT']}}">
                                            <label for="excess" class="active">Excess</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input type="hidden" value="{{$claim['SUM_INSURED']}}" id="originalSumInsured">
                                            <input id="sumInsured" type="text" name="sumInsured"
                                                   VALUE="{{$claim['SUM_INSURED']}}" disabled>
                                            <label for="sumInsured" class="active">Sum Insured</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <div style="margin-top: 20px;"></div>

                                            <select id="garageID" required name="garageID" class="browser-default">
                                                <option value="">Select Garage</option>
                                                @if(count($garages)>0)
                                                    @foreach($garages as $garage)
                                                        @if($garage->garageType == App\Conf\Config::GARAGE_TYPES[$claim['CLAIM_TYPE']]['ID'])
                                                        <option value="{{$garage->id}}"
                                                                @if($claim['CLAIM_TYPE'] == App\Conf\Config::GARAGE_TYPES['Theft']['TEXT']) selected @endif
                                                        >{{$garage->name}}</option>
                                                        @endif
                                                    @endforeach
                                                    @foreach($garages as $garage)
                                                        @if($claim['CLAIM_TYPE'] == App\Conf\Config::GARAGE_TYPES['Windscreen']['TEXT'])
                                                            @if($garage->garageType == App\Conf\Config::GARAGE_TYPES['Assessement']['ID'])
                                                                <option value="{{$garage->id}}">{{$garage->name}}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="customerCode" type="text" name="customerCode"
                                                   value="{{$claim['CUST_CODE']}}" disabled>
                                            <label for="customerCode" class="active">Client Code</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input type="text" name="email" id="email" value="{{$claim['CUST_EMAIL1']}}">
                                            <label for="email" class="active">Email</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input type="text" name="fullName" id="fullName"
                                                   value="{{$claim['CUST_NAME']}}">
                                            <label for="fullName" class="active">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input type="text" name="MSISDN" id="MSISDN"
                                                   value="{{$claim['CUST_MOBILE_NO']}}">
                                            <label for="MSISDN" class="active">MSISDN</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <a href="#"
                                               class="float-right btn  waves-effect waves-effect waves-light showActionButton actionButton"
                                               id="addClaim"> <i class="material-icons right">send</i>
                                                Submit
                                            </a>
                                            <a href="#"
                                               class="float-right btn cyan waves-effect waves-effect waves-light hideLoadingButton loadingButton"
                                            >
                                                <div class="preloader-wrapper small active float-left">
                                                    <div class="spinner-layer spinner-blue-only">
                                                        <div class="circle-clipper left">
                                                            <div class="circle"></div>
                                                        </div><div class="gap-patch">
                                                            <div class="circle"></div>
                                                        </div><div class="circle-clipper right">
                                                            <div class="circle"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="float-right"> Loading</div>
                                            </a>
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


<script type="text/javascript">
    $(document).ready(function() {
        $('#garageID').select2({dropdownAutoWidth : true, width: '100%'});
    });
</script>
