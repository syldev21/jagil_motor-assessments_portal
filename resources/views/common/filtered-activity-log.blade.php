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
            <th>Operation</th>

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
