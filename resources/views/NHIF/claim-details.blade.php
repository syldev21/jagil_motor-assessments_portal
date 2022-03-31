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
                                <h4 class="card-title float-left">NHIF Claim Details</h4>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">


                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="claimNo" type="text" name="claimNo"
                                                   value="{{$claim['claimNo']}}"
                                                   disabled>
                                            <label for="claimNo" class="active">CLAIM NUMBER</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="policyNumber" type="text" name="policyNumber"
                                                   value="{{$claim['policyNo']}}" disabled>
                                            <label for="policyNumber" class="active">POLICY NUMBER</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="status" type="text" name="status"

                                                    value="{{App\Conf\Config::NHIF_STATUSES[$claim['status']]["TITLE"]}}"
                                                        disabled>
                                            <label for="status" class="active">Status</label>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="input-field col m6 s12">
                                            <input id="insured" type="text" name="insured"

                                                   value="{{$claim['insured']}}" disabled>
                                            <label for="insured" class="active">Name of Insured</label>
                                        </div>
                                        <div class="input-field col m6 s12">
                                            <input id="claimant" type="text" name="claimant"
                                                   value="{{$claim['claimant']}}" disabled>
                                            <label for="claimant" class="active">Name of Claimant</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m3 s12">
                                            <input id="postalAddress" value="{{$claim['postalAddress']}}" name="postalAddress" class="materialize-textarea" disabled></input>
                                            <label for="postalAddress" class="active">Postal Address</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input placeholder="" id="postalCode" type="text" name="postalAddress"
                                                   value="{{$claim['postalCode']}}" disabled>
                                            <label for="postalCode" class="active">Postal Code</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input placeholder="" id="telephone" type="text" name="telephone"
                                                   value="{{$claim['telephone']}}" disabled>
                                            <label for="telephone" class="active">Telephone</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input placeholder="" id="mobile" type="text" name="mobile"
                                                   value="{{$claim['mobile']}}" disabled>
                                            <label for="mobile" class="active">Mobile</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m3 s12">
                                            <input id="email" name="email" class="materialize-textarea" value="{{$claim['email']}}" disabled></input>
                                            <label for="email" class="active">Email</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="occupation" name="occupation" class="materialize-textarea" value="{{$claim['occupation']}}" disabled></input>
                                            <label for="occupation" class="active">Occupation</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="dateOfBirth" name="dateOfBirth" class="datepicker" value="{{$claim['dateOfBirth']}}" disabled></input>
                                            <label for="dateOfBirth" class="active">Date of Birth</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="IDNumber" name="IDNumber" class="materialize-textarea" value="{{$claim['IDNumber']}}" disabled></input>
                                            <label for="IDNumber" class="active">ID Number</label>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="input-field col m3 s12">
                                            <input id="placeOfLoss" type="text" name="placeOfLoss"
                                                   value="{{$claim['placeOfLoss']}}" disabled>
                                            <label for="placeOfLoss" class="active">Place of Loss</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="causeOfLoss" type="text" name="causeOfLoss"
                                                   value="{{$claim['causeOfLoss']}}" disabled>
                                            <label for="causeOfLoss" class="active">Cause of Loss</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="dateOfInjury" type="text" name="dateOfInjury"
                                                   value="{{$claim['dateOfInjury']}}" class="datepicker" disabled>
                                            <label for="dateOfInjury" class="active">Date of Injury</label>
                                        </div>
                                        <div class="input-field col m3 s12">
                                            <input id="dateReceived" type="text" name="dateReceived"
                                                   value="{{$claim['dateReceived']}}" class="datepicker" disabled>
                                            <label for="dateReceived" class="active">Date Received</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="lossDescription" class="active">Loss Description </label>
                                        <input id="lossDescription" name="lossDescription" class="active" value="{{$claim['lossDescription']}}" disabled></input>

                                    </div>

                                    </div>

                                <div class="row">
                                    <div class="col s12 col m12">
                                        <h4 class="card-title float-left">Claim Documents</h4>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="row">
                                        @if(count($documents) > 0)
                                            @foreach($documents as $document)
                                                <div class="col s4">
                                                    <img class="responsive-img" src="{{url('claim_documents/'.$document->name) }}">
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
