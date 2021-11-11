<div class="row">
    <div id="userStatus" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
            </div>
            <div class="modal-body clearfix">
                <div class="row">
                    <div class="input-field col m12 s12">
                        <div class="container">
                            <div class="input-field">

                                <table>
                                    <thead>
                                    <tr >
                                        <th><b>S/N</b></th>
                                        <th><b>Name</b></th>
                                        <th><b>Email</b></th>
                                        <th><b>Switch Status</b></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>

                                        <td id="sn" style="text-align: center !important;"></td>
                                        <td id="u_id" style="text-align: center !important; display: none"></td>
                                        <td id="name" style="text-align: center !important;"></td>
                                        <td id="email" style="text-align: center !important;"></td>
                                        <td style="text-align: center !important;">
                                            <div class="switch">
                                                <label>
                                                    Inactive
                                                    <input  id="user_switch" type="checkbox" >
                                                    <span class="lever"></span>
                                                    Active
                                                </label>
                                            </div>
                                        </td>


                                    </tr>
                                    </tbody>
                                </table>




                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s8"></div>
                    <div class="input-field col s2">
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
                                <h4 class="card-title float-left um">User Management</h4>
                                @hasrole('Admin')
                                <a href="#" id="registerUserForm" class="float-right btn cyan darken-3 waves-effect waves-effect waves-light"><i class="material-icons left">add_circle_outline</i> Add User</a>
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
                                            <th>Email</th>
                                            @hasrole('Admin')
                                            <th>{{\App\Conf\Config::$ROLES['ADMIN']}}</th>
                                            @endhasrole
                                            <th>{{\App\Conf\Config::$ROLES['ASSESSOR']}}</th>
                                            @hasrole('Admin')
                                            <th>{{\App\Conf\Config::$ROLES['ADJUSTER']}}</th>
                                            @endhasrole
                                            <th>{{\App\Conf\Config::$ROLES['HEAD-ASSESSOR']}}</th>
                                            <th>{{\App\Conf\Config::$ROLES['ASSISTANT-HEAD']}}</th>
                                            @hasrole('Admin')
                                            <th>{{\App\Conf\Config::$ROLES['MANAGER']}}</th>
                                            @endhasrole
                                            <th>{{\App\Conf\Config::$ROLES['ASSESSMENT-MANAGER']}}</th>
                                            @hasrole('Admin')
                                            <th>{{\App\Conf\Config::$ROLES['UNDERWRITER']}}</th>
                                            @endhasrole
                                            @hasrole('Admin')
                                            <th>{{\App\Conf\Config::$ROLES['CUSTOMER-SERVICE']}}</th>
                                            @endhasrole
                                            <th>Status</th>
{{--                                            <th>Permission</th>--}}
                                            {{--                                                <th>Operation</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $user)
                                            <?php
                                            $rolesArray =array();
                                            ?>
                                            @foreach($user->roles as $role)
                                                <?php
                                                array_push($rolesArray,$role->name)
                                                ?>
                                            @endforeach
                                            <tr>
                                                <td class="sn">
                                                    {{$loop->iteration}}
                                                    <span class="name" style="display: none">{{$user->name}}</span>
                                                    <span class="userID" style="display: none">{{$user->id}}</span>
                                                    <span class="userStatus" style="display: none">{{$user->status}}</span>
                                                </td>
                                                <td class="email">{{$user->email}}</td>
                                                @hasrole('Admin')
                                                <td>
                                                        <label>
                                                            <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['ADMIN'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['ADMIN']}}"/>
                                                            <span></span>
                                                        </label>
                                                </td>
                                                @endhasrole
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['ASSESSOR'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['ASSESSOR']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                @hasrole('Admin')
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['ADJUSTER'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['ADJUSTER']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                @endhasrole
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['HEAD-ASSESSOR'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['HEAD-ASSESSOR']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['ASSISTANT-HEAD'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['ASSISTANT-HEAD']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                @hasrole('Admin')
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['MANAGER'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['MANAGER']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                @endhasrole
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['ASSESSMENT-MANAGER'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['ASSESSMENT-MANAGER']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                @hasrole('Admin')
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['UNDERWRITER'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['UNDERWRITER']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                @endhasrole
                                                @hasrole('Admin')
                                                <td>


                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['CUSTOMER-SERVICE'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['CUSTOMER-SERVICE']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                @endhasrole
                                                <td>
                                                    <select id="" name="" class="browser-default">
                                                            <option data-userid="{{$user->id}}" id="user_status" value="{{$user->id}}">
                                                                @if($user->status==0)
                                                                    Activate

                                                                @else
                                                                    Deactivate
                                                                @endif

                                                            </option>
                                                            <option data-id="{{$loop->iteration}}"  data-user="{{$user->id}}" id="assignRole" value="">Role</option>
                                                            <option data-id="{{$user->id}}" id="fetchPermissions" value="">Permission</option>
                                                    </select>
                                                </td>
                                            </tr>

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
