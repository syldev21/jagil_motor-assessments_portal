<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
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
                                <h4 class="card-title float-left">New Claim</h4>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12 m4">
                                    <h5 class="bold"><b>Documents Required to Launch a Claim</b></h5>
                                    <p style="color: red">Please see details below to know what is required to launch a claim (click on the link provided in item 1 to download claim form)</p>
                                    <br><span style="color: red">Important!</span>: Items marked with an asterisk (<span style="color: red">*</span>) are mandatory
                                    <ol style="line-height: 38px">
                                        <li id="item">Duly filled <a href="{{url('/safclaimform/HOME_FIBER_CLAIM_FORM_Interactive.pdf')}}" target="_blank" class="fa-file-download">claim form<span style="color: red">*</span></a></li>
                                        <li id="abstract"></li>
                                        <li id="handset"></li>
                                        <li id="proforma"></li>
                                    </ol>
                                </div>
                                <div class="col s12 m4">
                                    <h5 class="bold"><b>Upload Files</b></h5>
                                    <div>
                                        <div class="row">
                                            <div class="input-field">
                                                <div class="file-field input-field">
                                                    <div class="btn">
                                                        <span ><p style="padding-left: 28px; padding-right: 22px">Claim Form</p> </span>
                                                        <input type="file" id="uploadClaimFormpdf" name="" data-id="claimForm">
                                                    </div>
                                                    <div class="file-path-wrapper">`
                                                        <input class="file-path validate" type="text" placeholder="Upload pdf file">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-field">
                                                <div class="file-field input-field">
                                                    <div class="btn">
{{--                                                        <span>Abstract</span>--}}
                                                        <span ><p style="padding-left: 28px; padding-right: 40px">Abstract</p> </span>
                                                        <input type="file" id="abstract" name="" data-id="abstract">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Upload pdf file">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-field">
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
                                            <div class="input-field">
                                                <div class="file-field input-field">
                                                    <div class="btn">
{{--                                                        <span>Proforma Invoice</span>--}}
                                                        <span ><p style="padding-left: 2px; padding-right: 8px">Proforma Invoice</p> </span>
                                                        <input type="file" id="proformaInvoice" name="" data-id="proformaInvoice">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Upload pdf file">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                <div class="col s12 m4" style="position: relative">
                                    <h5 class="bold"><b>Claim Description<span style="color: red">*</span></b></h5>
                                    <div class="input-field">
                                        <textarea placeholder="A summary of your claim in not more than 200 charcaters" style="height: 207px" id="lossDescription"  class = "" length = "120"></textarea>
                                    </div>

                                    <div class="input-field" style= "margin-bottom: 0; margin-right: 0; position: absolute">

                                        <a href="#"
                                           class="float-right btn  waves-effect waves-effect waves-light showActionButton actionButton"
                                           id="submitSafClaim"> <i class="material-icons right">send</i>
                                            Submit Claim
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
<!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
