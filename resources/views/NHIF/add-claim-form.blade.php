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
                            </div>
                            <div class="divider"></div>
                                <span style="color: red">Important!</span>: all fields marked with an asterisk (<span style="color: red">*</span>) are mandatory
                            <div class="divider"></div>
                            <div class="row" id="civil_servants_nys" style="border: 2px solid gray">
                                <div class="col s12 m4"><h5><strong style="color: green">{{\App\Conf\Config::POLICY_TYPES["CIVIL_SERVANTS_AND_NYS"]["TITLE"]}}</strong></h5></div>
                                <div class="col s12 m4"><h5><strong>FROM DATE: <span style="color: green">{{\App\Conf\Config::POLICY_TYPES["CIVIL_SERVANTS_AND_NYS"]["WEF"]}}</span></strong></h5></div>
                                <div class="col s12 m4"><h5><strong>TO DATE: <span style="color: green">{{\App\Conf\Config::POLICY_TYPES["CIVIL_SERVANTS_AND_NYS"]["WET"]}}</span></strong></h5></div>
                            </div>
                            <div class="row" id="police" style="border: 2px solid gray">
                                <div class="col s12 m4"><h5><strong style="color: green">{{\App\Conf\Config::POLICY_TYPES["KENYA_POLICE_AND_KENYA_PRISONS"]["TITLE"]}}</strong></h5></div>
                                <div class="col s12 m4"><h5><strong>FROM DATE: <span style="color: green">{{\App\Conf\Config::POLICY_TYPES["KENYA_POLICE_AND_KENYA_PRISONS"]["WEF"]}}</span></strong></h5></div>
                                <div class="col s12 m4"><h5><strong>TO DATE: <span style="color: green">{{\App\Conf\Config::POLICY_TYPES["KENYA_POLICE_AND_KENYA_PRISONS"]["WET"]}}</span></strong></h5></div>
                            </div>

                            <ul id="saveform_errList"></ul>

                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <div class="row">
                                        <div class="input-field col m3 s12">
                                            <select class="browser-default" id="policyType">
                                                <option value="{{App\Conf\Config::POLICY_TYPES["CIVIL_SERVANTS_AND_NYS"]["ID"]}}">{{\App\Conf\Config::POLICY_TYPES["CIVIL_SERVANTS_AND_NYS"]["TITLE"]}}</option>
                                                <option value="{{\App\Conf\Config::POLICY_TYPES["KENYA_POLICE_AND_KENYA_PRISONS"]["ID"]}}">{{\App\Conf\Config::POLICY_TYPES["KENYA_POLICE_AND_KENYA_PRISONS"]["TITLE"]}}</option>
                                            </select>
                                            <label for="policyType" class="active">Policy Type <span style="color: red">*</span></label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="claimant" type="text" name="claimant"
                                                   value="">
                                            <label for="claimant" class="active">Name of Claimant <span style="color: red">*</span></label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="dateOfBirth" name="dateOfBirth" class="datepicker">
                                            <label for="dateOfBirth" class="active">Date of Birth</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="IDNumber" name="IDNumber" class="materialize-textarea"></input>
                                            <label for="IDNumber" class="active">ID Number</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="postalAddress" name="postalAddress" class="materialize-textarea"></input>
                                            <label for="postalAddress" class="active">Postal Address</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="postalCode" type="text" name="postalAddress"
                                                   value="">
                                            <label for="postalCode" class="active">Postal Code</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="telephone" type="text" name="telephone"
                                                   value="">
                                            <label for="telephone" class="active">Telephone</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="mobile" type="text" name="mobile"
                                                   value="">
                                            <label for="mobile" class="active">Mobile</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <textarea id="email" name="email" class="materialize-textarea"></textarea>
                                            <label for="email" class="active">Email</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <textarea id="occupation" name="occupation" class="materialize-textarea"></textarea>
                                            <label for="occupation" class="active">Occupation</label>
                                        </div>


                                    </div>
                                    <div class="row">

                                        <div class="input-field col m4 s12">
                                            <input id="placeOfLoss" type="text" name="placeOfLoss"
                                                   value="" required>
                                            <label for="placeOfLoss" class="active">Place of Loss<span style="color: red">*</span></label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="causeOfLoss" type="text" name="causeOfLoss"
                                                   value="">
                                            <label for="causeOfLoss" class="active">Cause of Loss<span style="color: red">*</span></label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <select class="browser-default" id="typeOfInjury">
                                                <option value="{{\App\Conf\Config::INJURY_TYPES["INJURY"]["ID"]}}">{{App\Conf\Config::INJURY_TYPES["INJURY"]["TITLE"]}}</option>
                                                <option value="{{\App\Conf\Config::INJURY_TYPES["DEATH"]["ID"]}}">{{App\Conf\Config::INJURY_TYPES["DEATH"]["TITLE"]}}</option>
                                            </select>
                                            <label for="causeOfLoss" class="active">Type of Injury<span style="color: red">*</span></label>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="input-field col m6 s12">
                                            <label for="lossDescription" class="active required">Loss Description<span style="color: red">*</span></label>
                                            <textarea id="lossDescription" name="lossDescription" class="active materialize-textarea required" rows="20" placeholder="Describe fully how the loss occurred"></textarea>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="dateOfInjury" type="text" name="dateOfInjury"
                                                   value="" class="datepicker">
                                            <label for="dateOfInjury" class="active">Date of Injury<span style="color: red">*</span></label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="dateReceived" type="text" name="dateReceived"
                                                   value="" class="datepicker">
                                            <label for="dateReceived" class="active">Date Received<span style="color: red">*</span></label>
                                        </div>
                                        </div>

                                    </div>
                                    <div>
                                        <div class="file-field input-field col m4 s12 injuryClaimFormpdf">
                                            <div class="btn">
                                                <span>Claim Form</span>
                                                <input type="file" id="injuryClaimFormpdf" name="injuryClaimFormpdf" value="">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path validate" type="text" placeholder="Upload Injury Claim Form">
                                            </div>
                                        </div>
                                        <div class="file-field input-field col m4 s12 deathClaimFormpdf">
                                            <div class="btn">
                                                <span>Claim Form</span>
                                                <i








                                                    nput type="file" id="deathClaimFormpdf" name="deathClaimFormpdf" value="">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path validate" type="text" placeholder="Upload Death Claim Form">
                                            </div>
                                        </div>

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
<script type="text/javascript">
    $(document).ready(function (){
        $("#civil_servants_nys").removeClass("hide");
        $("#police").addClass("hide");

        // console.log($('#policyType').val());

        $("body").on("change", "#policyType", function(){
            // console.log($('#vendorType').val());
            if($('#policyType').val() == "{{App\Conf\Config::POLICY_TYPES['KENYA_POLICE_AND_KENYA_PRISONS']['ID']}}") {
                $('#civil_servants_nys').addClass('hide');
                $('#police').removeClass('hide');
            } else {
                $('#civil_servants_nys').removeClass('hide');
                $('#police').addClass('hide');
            }
            // console.log($('#policyType').val());
        });

        $(".injuryClaimFormpdf").removeClass("hide");
        $(".deathClaimFormpdf").addClass("hide")
        $("body").on("change", "#typeOfInjury", function (){
            if($('#typeOfInjury').val() == "{{\App\Conf\Config::INJURY_TYPES["DEATH"]["ID"]}}") {
                $(".injuryClaimFormpdf").addClass("hide");
                $(".deathClaimFormpdf").removeClass("hide");
            }else{
                $(".injuryClaimFormpdf").removeClass("hide");
                $(".deathClaimFormpdf").addClass("hide");
            }
            // console.log($("#typeOfInjury").val());
        });

    });
</script>
