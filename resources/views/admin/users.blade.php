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
                                <a href="#" id="registerUserForm" class="float-right btn cyan darken-3 waves-effect waves-effect waves-light"><i class="material-icons left">add_circle_outline</i> Add User</a>
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
                                            <th>Admin</th>
                                            <th>Assessor</th>
                                            <th>Adjuster</th>
                                            <th>Head Assessor</th>
                                            <th>Assistant Head</th>
                                            <th>Assessment Manager</th>
                                            <th>Operation</th>
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
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>
                                                        <label>
                                                            <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['ADMIN'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['ADMIN']}}"/>
                                                            <span></span>
                                                        </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['ASSESSOR'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['ASSESSOR']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['ADJUSTER'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['ADJUSTER']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
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
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="roles_{{$loop->iteration}}" class="filled-in" @if(in_array(App\Conf\Config::$ROLES['ASSESSMENT-MANAGER'], $rolesArray)) checked="checked"  @endif value="{{App\Conf\Config::$ROLES['ASSESSMENT-MANAGER']}}"/>
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <a data-id="{{$loop->iteration}}"  data-user="{{$user->id}}" href="#" class="btn cyan waves-effect waves-effect waves-light" id="assignRole"><i class="material-icons left">edit</i>Update</a>
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
