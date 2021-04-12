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
                                <h4 class="card-title float-left">Log Details</h4>
                            </div>
                            <div class="divider"></div>
                            <?php
                            $fromUser = \App\User::where(["id" =>$activityLog->createdBy])->first();
//                            $toUser = \App\User::where(["id" =>$activityLog->userID])->first();
                            ?>
                            <div class="row">
                                <div class="col s12">
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="claimNo" type="text" name="claimNo"
                                                   value="{{$activityLog->claimNo}}" disabled>
                                            <label for="claimNo" class="active">Claim Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="policyNo" type="text" name="policyNo"
                                                   value="{{$activityLog->policyNo}}" disabled>
                                            <label for="policyNo" class="active">Policy Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="vehicleRegNo" type="text" name="vehicleRegNo"
                                                   value="{{$activityLog->vehicleRegNo}}" disabled>
                                            <label for="vehicleRegNo" class="active">Registration Number</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="notificationType" type="text" name="notificationType"
                                                   value="{{$activityLog->notificationType}}" disabled>
                                            <label for="notificationType" class="active">Notification Type</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="activity" type="text" name="activity"
                                                   value="{{$activityLog->activity}}" disabled>
                                            <label for="activity" class="active">Activity</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="role" type="text" name="role"
                                                   value="{{$activityLog->role}}" disabled>
                                            <label for="role" class="active">Role</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m4 s12">
                                            <input id="from" type="text" name="from"
                                                   value="{{isset($fromUser->firstName) ? $fromUser->firstName : ''}} {{isset($fromUser->lastName) ? $fromUser->lastName : ''}}" disabled>
                                            <label for="from" class="active">From</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="to" type="text" name="to"
                                                   value="{{$activityLog->notificationTo}}" disabled>
                                            <label for="to" class="active">To</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input id="time" type="text" name="time"
                                                   value="{{$activityLog->dateCreated}}" disabled>
                                            <label for="time" class="active">Time</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m8 s12">
                                                <p style="border-bottom: 1px dotted lightgrey">CC</p><br/>
                                                <p style="border-bottom: 1px dotted lightgrey">
                                                    {!! $activityLog->cc !!}
                                                </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m8 s12">
                                                <p style="border-bottom: 1px dotted lightgrey">Notification</p><br/>
                                                <p style="border-bottom: 1px dotted lightgrey">
                                                    {!! $activityLog->notification !!}
                                                </p>
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
