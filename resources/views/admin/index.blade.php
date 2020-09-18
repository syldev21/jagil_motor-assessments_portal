@include('_partials.header')
@include('_partials.navbar')
@include('_partials.sidebar')
<!-- Page Length Options -->
<div id="main">
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
                                    <div class="col s12 m6">
                                        <div class="row">
                                            <h4 class="card-title float-left">Assign Role</h4>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="row">
                                            <div class="input-field">
                                                <select class="error user" id="userID"required>
                                                    <option value="">Select User</option>
                                                    @if(count($users)>0)
                                                        @foreach($users as $user)
                                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="input-field">
                                                <select class="error role" id="roleID"required>
                                                    <option value="">Select Role</option>
                                                    @if(count($roles)>0)
                                                        @foreach($roles as $role)
                                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="input-field col m6 s12">
                                                <a href="#" class="float-right btn cyan waves-effect waves-effect waves-light float-right" id="assignRole">Assign Role <i class="material-icons right">send</i></a>
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
</div>
@include('_partials.settings')
@include('_partials.footer')
