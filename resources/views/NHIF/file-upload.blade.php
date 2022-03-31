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
                                            <div class="input-field col m9 s12">
                                            </div>
                                            <div class="input-field col m3 s12">
                                                <input type="hidden" name="claimID" value="{{$claim->id}}" id="claimID">
                                                <input type="submit" class="waves-effect waves-dark btn next-step"
                                                       value="UPLOAD" id="uploadClaimDocuments"/>
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

