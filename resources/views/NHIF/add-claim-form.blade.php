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
                            <div class="row">
                                <div class="col s12">
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="policyNo" type="text" name="policyNo"
                                                   value="" >
                                            <label for="policyNo" class="active">Policy Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="lossDate" type="text" name="lossDate"
                                                   value="" class="datepicker">
                                            <label for="lossDate" class="active">Loss Date</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="currencyCode" type="text" name="currencyCode"
                                                   value="">
                                            <label for="currencyCode" class="active">Currency Code</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m6 s12">
                                            <textarea id="causeOfLoss" class="materialize-textarea"></textarea>
                                            <label for="causeOfLoss" class="active">Cause Of Loss</label>
                                        </div>
                                        <div class="input-field col m6 s12">
                                            <input placeholder="" id="intimationDate" type="text" name="intimationDate"
                                                   value="" class="datepicker">
                                            <label for="intimationDate" class="active">Intimation Date</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m6 s12">
                                            <textarea id="lossDescription" class="materialize-textarea"></textarea>
                                            <label for="lossDescription" class="active">Loss Description</label>
                                        </div>
                                        <div class="input-field col m6 s12">
                                            <textarea id="natureOfLoss" class="materialize-textarea"></textarea>
                                            <label for="natureOfLoss" class="active">Nature Of Loss</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m6 s12">
                                            <textarea id="lossDescription" class="materialize-textarea"></textarea>
                                            <label for="lossDescription" class="active">Loss Description</label>
                                        </div>
                                        <div class="input-field col m6 s12">
                                            <textarea id="natureOfLoss" class="materialize-textarea"></textarea>
                                            <label for="natureOfLoss" class="active">Nature Of Loss</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m6 s12">
                                            <select id="coverCode" required name="coverCode" class="browser-default">
                                                <option value="">Select Cover Type</option>
                                                <option value="">Select Cover Type</option>
                                                <option value="">Select Cover Type</option>
                                            </select>
                                        </div>
                                        <div class="input-field col m6 s12">
                                            <textarea id="natureOfLoss" class="materialize-textarea"></textarea>
                                            <label for="natureOfLoss" class="active">Nature Of Loss</label>
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
    </div>
</div>
