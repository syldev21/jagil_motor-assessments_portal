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
                                            <input placeholder="" id="carMake" type="text" name="carMake"
                                                   value="{{$carDetails->makeName}}" disabled>
                                            <input type="hidden" name="carMakeCode" id="carMakeCode" value="{{$carDetails->carMakeCode}}">
                                            <label for="carMake" class="active">Car Make</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="carModel" type="text" name="carModel"
                                                   value="{{$carDetails->modelName}}" disabled>
                                            <input type="hidden" name="carModelCode" id="carModelCode" value="{{$carDetails->carModelCode}}">
                                            <label for="carModel" class="active">Car Model</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="yom" type="text" name="yom"
                                                   value="{{$claim['yom']}}" disabled>
                                            <label for="yom" class="active">Year Of Make</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="engineNumber" type="text" name="engineNumber"
                                                   value="{{$claim['engineNumber']}}" disabled>
                                            <label for="engineNumber" class="active">Engine Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="chassisNumber" type="text" name="chassisNumber"
                                                   value="{{$claim['chassisNumber']}}" disabled>
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
                                            <input id="excess" type="text" name="excess" value="{{$claim->excess}}">
                                            <input type="hidden" name="oldExcess" id="oldExcess" value="{{$claim->excess}}">
                                            <label for="excess" class="active">Excess</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="sumInsured" type="text" name="sumInsured"
                                                   VALUE="{{$claim->sumInsured}}" disabled>
                                            <input type="hidden" name="oldSumInsured" id="oldSumInsured" value="{{$claim->sumInsured}}">
                                            <label for="sumInsured" class="active">Sum Insured</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="customerCode" type="text" name="customerCode"
                                                   value="{{$claim->customerCode}}" disabled>
                                            <label for="customerCode" class="active">Client Code</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <div style="margin-top: 20px;"></div>

                                            <select id="garageID" required name="garageID" class="browser-default">
                                                <option value="">Select Garage</option>
                                                @if(count($garages)>0)
                                                    @foreach($garages as $garage)
                                                        <option value="{{$garage->id}}" @if($claim->garageID == $garage->id) selected @endif>{{$garage->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <input type="hidden" value="{{$claim->id}}" name="claimID" id="claimID">

                                        </div>
                                    </div>
                                    @if(count($claim->documents)>0)
                                    <div class="row">
                                        <div class="col s12">
                                            <div class="col s12">
                                                <input type="hidden" value="{{$claim->documents}}" name="imagesArray" id="imagesArray">
                                                <form action="#" enctype="multipart/form-data" data-allowed-file-extensions='["jpeg", "jpg", "png"]' id="editClaimForm">
                                                    <div class="input-images" id="images"></div>
                                                </form>
                                                <small>Only <span
                                                        class="red-text text-darken-3">JPEG,JPG & PNG</span> files
                                                    are allowed</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col m8 s12"></div>
                                        <div class="input-field col m4 s12">
                                            <a href="#"
                                               class="float-right btn cyan waves-effect waves-effect waves-light showActionButton actionButton"
                                               id="updateClaim">
                                                <i class="material-icons right">send</i>
                                                Update
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
