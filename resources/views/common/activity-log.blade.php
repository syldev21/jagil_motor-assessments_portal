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
                                <h4 class="card-title float-left">Log History</h4>
                            </div>
                            <div class="row">
                                <div class="row">
                                    <div class="input-field col m3 s6">
                                        <i class="material-icons prefix">access_time</i>
                                        <input id="from_date" type="text" class="validate datepicker">
                                        <label for="from_date">From Date</label>
                                    </div>
                                    <div class="input-field col m3 s6">
                                        <i class="material-icons prefix">access_time</i>
                                        <input id="to_date" type="text" class="validate datepicker">
                                        <label for="to_date">To Date</label>
                                    </div>
                                    <div class="input-field col m3 s6">
                                        <i class="material-icons prefix">vpn_key</i>
                                        <input id="vehicle_reg_no" type="text" class="validate">
                                        <label for="vehicle_reg_no">Reg No</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light" type="submit"
                                                    name="action" id="filter-logs">
                                                <i class="material-icons left">search</i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="row" id="table-data">
                                <div class="col s12">
                                    <table id="data-table-simple" class="display">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Registration Number</th>
                                            <th>Claim Number</th>
                                            <th>Role</th>
                                            <th>Activity</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Notification Type</th>
                                            <th>Notification</th>
                                            <th>Time</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($logs as $log)
                                            <?php
                                                $user = \App\User::where(["id"=>$log->userID])->first();
                                                $notification = explode('.', $log->notification);
                                            ?>
                                            <form class="assignForm">
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$log->vehicleRegNo}}</td>
                                                    <td><a href="#" class="fetchLogDetails" data-id="{{$log->id}}">{{$log->claimNo}}</a></td>
                                                    <td>{{$log->role}}</td>
                                                    <td>{{$log->activity}}</td>
                                                    <td>{{isset($user->firstName) ? $user->firstName : '' }} {{isset($user->lastName) ? $user->lastName : ""}}</td>
                                                    <td>{{$log->notificationTo}}</td>
                                                    <td>{{$log->notificationType}}</td>
                                                    <td>{!! $notification[0].' ...' !!}</td>
                                                    <td>{{$log->dateCreated}}</td>
                                                    <td>
                                                        <!-- Dropdown Trigger -->
                                                        <a class='dropdown-trigger' href='#'
                                                           data-target='{{$loop->iteration}}'
                                                           data-activates="{{$loop->iteration}}"><i
                                                                class="Medium material-icons">menu</i><i
                                                                class="Medium material-icons">expand_more</i></a>

                                                        <ul id='{{$loop->iteration}}' class='dropdown-content'>
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


