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
                                <h4 class="card-title float-left">Policy Details</h4>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <input type="hidden" id="renewalID" value="{{$policy->id}}" name="renewalID">
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="policyNumber" type="text" name="policyNumber"
                                                   value="{{$policy->policyNumber}}" disabled>
                                            <label for="policyNumber" class="active">Policy Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="policyTypeName" type="text" name="policyTypeName"
                                                   value="{{$policy->policyTypeName}}" disabled>
                                            <label for="policyTypeName" class="active">Policy Type</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="policyStartDate" type="text" name="policyStartDate"
                                                   value="{{$policy->policyStartDate}}" disabled>
                                            <label for="loseDate" class="active">policy Start Date</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="policyEndDate" type="text" name="policyEndDate"
                                                   value="{{$policy->policyEndDate}}" disabled>
                                            <label for="policyEndDate" class="active">policy End Date</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="policyPeriod" type="text" name="policyPeriod"
                                                   value="{{$policy->policyPeriod}}" disabled>
                                            <label for="policyPeriod" class="active">Policy Period</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="totalPremium" type="text" name="totalPremium"
                                                   value="{{$policy->totalPremium}}">
                                            <label for="totalPremium" class="active">Total Premium</label>
                                        </div>
                                    </div>
                                    <div class="row ri">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="insuranceTrainingLavy" type="text" name="insuranceTrainingLavy"
                                                   value="{{$policy->insuranceTrainingLavy}}" >
                                            <label for="insuranceTrainingLavy" class="active">ITL</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="PolicyHoldersFund" type="text" name="PolicyHoldersFund"
                                                   value="{{$policy->PolicyHoldersFund}}">
                                            <label for="PolicyHoldersFund" class="active">PHF</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="customerName" type="text" name="customerName"
                                                   value="{{$policy->customerName}}">
                                            <label for="customerName" class="active">Customer Name</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="assuredName" type="text" name="assuredName"
                                                   value="{{$policy->assuredName}}" >
                                            <label for="assuredName" class="active">Assured Name</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="MSISDN" type="text" name="MSISDN"
                                                   value="{{$policy->MSISDN}}">
                                            <label for="MSISDN" class="active">Customer Telephone</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="customerEmail" type="text" name="customerEmail"
                                                   value="{{$policy->customerEmail}}">
                                            <label for="customerEmail" class="active">Customer Email</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="sumInsured" type="text" name="sumInsured"
                                                   value="{{$policy->sumInsured}}">
                                            <label for="sumInsured" class="active">Sum Insured</label>
                                        </div>
                                        <div class="input-field col m4 s12">

                                        </div>
                                        <div class="input-field col m4 s12">
                                            <a href="#"
                                               class="float-right btn cyan waves-effect waves-effect waves-light showActionButton actionButton"
                                               id="editPolicy"> <i class="material-icons right">edit</i>
                                                Update
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

