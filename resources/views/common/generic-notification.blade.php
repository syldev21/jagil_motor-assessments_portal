<div class="row">
    <div class="col s2"></div>
    <div class="col s8">
        <!-- Modal Structure -->
        <div id="genericNotification" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
                </div>
                <div class="modal-body clearfix">
                    <div class="row">
                        <div class="input-field col m12 s12">
                            <div class="container">
                                <div id="users">
                                </div>
                                <div>Message:</div>
                                <textarea  id="message" class="materialize-textarea" name="message">
                                    </textarea>
                                <script>
                                    CKEDITOR.replace('message',{
                                        language: 'en',
                                        uiColor: ''
                                    });
                                </script>
                                <input type="hidden" id="assessmentID" name="assessmentID">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s8"></div>
                        <div class="input-field col s2">
                            <a href="#" id="sendNotification" class="btn blue lighten-2 waves-effect showActionButton actionButton">Send</a>
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
                        <div class="input-field col s2">
                            <a href="#" class="modal-action modal-close btn red lighten-2 waves-effect">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col s2"></div>
</div>
