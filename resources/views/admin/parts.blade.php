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
                                <h4 class="card-title float-left">Parts Management</h4>
                                @hasanyrole('Admin|Head Assessor')
                                <a href="#" id="triggerAddpart" class="float-right btn cyan darken-3 waves-effect waves-effect waves-light"><i class="material-icons left">add_circle_outline</i> Add Part</a>
                                @endhasanyrole
                                <br/>
                            </div>
                            <div class="row">
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <table id="data-table-simple" class="display">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Part Name</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($parts as $part)
                                            <form class="assignForm">
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$part->name}}</td>
                                                    <input type="hidden" name="claimID{{$loop->iteration}}"
                                                           id="claimID{{$loop->iteration}}"
                                                           value="{{$part->id}}" class="partID">

                                                    <td>
                                                        <!-- Dropdown Trigger -->
                                                        <a class='dropdown-trigger' href='#'
                                                           data-target='{{$loop->iteration}}'
                                                           data-activates="{{$loop->iteration}}"><i
                                                                class="Medium material-icons">menu</i><i
                                                                class="Medium material-icons">expand_more</i></a>

                                                        <!-- Dropdown Structure -->

                                                        <ul id='{{$loop->iteration}}' class='dropdown-content'>
                                                            <li>
                                                                <a href="#" id="triggerNotification" data-id=""><i
                                                                        class="material-icons">notifications_active</i>Send Notification </a>
                                                            </li>
                                                        </ul>

                                                    </td>
                                                </tr>
                                            </form>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s2"></div>
    <div class="col s8">
        <!-- Modal Structure -->
        <div id="addPartModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
                </div>
                <div class="modal-body clearfix">
                    <div class="row">
                        <div class="input-field col m12 s12">
                            <div class="container">
                                <div class="input-field">
                                    <input id="name" type="text" name="name"
                                           value="">
                                    <label for="name" class="active">Part Name</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s8"></div>
                        <div class="input-field col s2">
                            <a href="#" id="addPart" class="btn blue lighten-2 waves-effect showActionButton actionButton">Submit</a>
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
