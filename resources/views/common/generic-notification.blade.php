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

                                <div>To:</div>
                                <div>
                                    <select id="email" name="email" class="browser-default">
                                        @foreach($users as $user)
                                            <option value="{{$user->email}}" >{{$user->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-field col s12">
                                    <input class="col s6"  id="subject" type="text" >
                                    <label for="subject">subject</label>
                                </div>
                                <textarea  id="message" class="materialize-textarea" name="message">
                                    </textarea>
                                <script>
                                    CKEDITOR.replace('message',{
                                        language: 'en',
                                        uiColor: ''
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12" style="text-align:right;">
                            <a href="#" id="sendNotification" class=" s6 btn blue lighten-2 waves-effect">Send</a>
                            <a href="#" class="s6 modal-action modal-close btn red lighten-2 waves-effect">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col s2"></div>
</div>
