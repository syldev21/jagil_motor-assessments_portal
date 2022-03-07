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
                                <h4 class="card-title float-left">Upload Claim Documents</h4>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <form action="#" enctype="multipart/form-data"
                                          data-allowed-file-extensions='["jpeg", "jpg", "png","pdf"]' id="assessmentForm">
                                        <div class="row">
                                            <div class="input-field col m12 s12">
                                                <div class="input-images" id="images"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col m9 s12">
                                                <small>Only <span
                                                        class="red-text text-darken-3">JPEG,JPG,PNG</span> files
                                                    are allowed</small>
                                            </div>
                                            <div class="input-field col m3 s12">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col m6 s12">
                                                <div class="file-field input-field">
                                                    <div class="btn">
                                                        <span>Claim Form</span>
                                                        <input type="file" id="claimFormpdf" name="claimFormpdf">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Upload pdf file">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-field col m6 s12">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col m4" id="subrogationForm">
                                                <br/>
                                                <label>
                                                    <input name="subrogation" type="checkbox"
                                                           class="with-gap subrogation subrogation-checkbox" value="" id="subrogation"/>
                                                    <span>Has Subrogation:</span>
                                                </label>
                                            </div>
                                            <div class="col m4">
                                                <select id="company" name="company" class="browser-default subrogationSelect hideSubrogation">
                                                    <option value="0">Select Company</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col m4"></div>
                                        </div>
                                        <br/>
                                        <div class="row hideSubrogation thirdPartyDetails">
                                            <div class="input-field col m4 s12">
                                                <input placeholder="" id="thirdPartyDriver" type="text" name="thirdPartyDriver"
                                                       value="">
                                                <label for="thirdPartyDriver" class="active">3rd Party Driver Name</label>
                                            </div>
                                            <div class="input-field col m4 s12">
                                                <input placeholder="" id="thirdPartyPolicy" type="text" name="thirdPartyPolicy"
                                                       value="">
                                                <label for="thirdPartyPolicy" class="active">3rd Party Policy</label>
                                            </div>
                                            <div class="input-field col m4 s12">
                                                <input placeholder="" id="thirdPartyVehicleRegNo" type="text" name="thirdPartyVehicleRegNo"
                                                       value="">
                                                <label for="thirdPartyVehicleRegNo" class="active">3rd Party VehicleRegNo</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col m9 s12">
                                            </div>
                                            <div class="input-field col m3 s12">
                                                <input type="hidden" name="claimID" value="{{$claim->id}}" id="claimID">
                                                <input type="submit" class="waves-effect waves-dark btn next-step"
                                                       value="UPLOAD" id="uploadDocuments"/>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
