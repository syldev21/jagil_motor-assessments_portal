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
                                                <small>File types allowed: <span
                                                        class="red-text text-darken-3">PDF, JPEG,JPG,PNG</span></small>
                                            </div>
                                            <div class="input-field col m3 s12">
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom: 0">
                                            <div class="input-field col m6 s12" style="margin-bottom: 0">
                                                <div class="">
                                                    <a href="{{url('/safclaimform/HOME_FIBER_CLAIM_FORM_Interactive.pdf')}}" target="_blank" class=" btn fa-file-download"><i class="material-icons right">file_download</i>Download Claim Form</a>

                                                </div>
                                            </div>
                                            <div class="input-field col m6 s12" style="margin-top: 0">
                                                <div class="file-field input-field">
                                                    <div class="btn">
                                                        <span>Upload Claim Form</span>
                                                        <input type="file" id="uploadClaimFormpdf" name="" data-id="claimForm">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Upload pdf file">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-field col m6 s12">
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 0">
                                            <div class="input-field col m4 s12">
                                                <div class="file-field input-field">
                                                    <div class="btn">
                                                        <span>Abstract</span>
                                                        <input type="file" id="abstract" name="" data-id="abstract">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Upload pdf file">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-field col m4 s12">
                                                <div class="file-field input-field">
                                                    <div class="btn">
                                                        <span>Handset Certificate</span>
                                                        <input type="file" id="handsetCertificate" name="" data-id="handsetCertificate">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input  class="file-path validate" type="text" placeholder="Upload pdf file">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-field col m4 s12">
                                                <div class="file-field input-field">
                                                    <div class="btn">
                                                        <span>Proforma Invoice</span>
                                                        <input type="file" id="proformaInvoice" name="" data-id="proformaInvoice">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Upload pdf file">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col m9 s12">
                                            </div>
                                            <div class="input-field col m3 s12">

                                                <a href="#"
                                                   class="float-right btn  waves-effect waves-effect waves-light showActionButton actionButton"
                                                   id="submitSafClaim"> <i class="material-icons right">send</i>
                                                    Submit Claim
                                                </a>
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
