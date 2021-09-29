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
                                <h4 class="card-title float-left">User Management</h4>
                                @hasrole('Admin')
                                <a href="#" id="triggerAddpermission" class="float-right btn cyan darken-3 waves-effect waves-effect waves-light"><i class="material-icons left">add_circle_outline</i> Add Permission</a>
                                @endhasrole
                                <br/>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <table id="data-table-simple" class="display">
                                        <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($permissions as $permission)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$permission->name}}</td>
                                                    <td>
                                                        <label>
                                                            <input type="hidden" id="userID" value="{{$user->id}}">
                                                            <input type="checkbox" name="permissions[]" class="filled-in" value="{{$permission->id}}" @if($user->can($permission->name)) checked @endif/>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col s8"></div>
                                        <div class="col s4">
                                            <a href="#" class="btn" id="assignPermission">Assign Permission</a>
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

<div class="row">
    <div class="col s2"></div>
    <div class="col s8">
        <!-- Modal Structure -->
        <div id="addPermissionModal" class="modal">
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
                                    <label for="name" class="active">Permission Name</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s8"></div>
                        <div class="input-field col s2">
                            <a href="#" id="addPermission" class="btn blue lighten-2 waves-effect showActionButton actionButton">Submit</a>
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
