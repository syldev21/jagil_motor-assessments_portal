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
                                <h4 class="card-title float-left">Customer Payments</h4>
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
{{--                                    <div class="input-field col m3 s6">--}}
{{--                                        <i class="material-icons prefix">vpn_key</i>--}}
{{--                                        <input id="vehicle_reg_no" type="text" class="validate">--}}
{{--                                        <label for="vehicle_reg_no">Reg No</label>--}}
{{--                                    </div>--}}
                                    <div class="input-field col m3 s12">
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light" type="submit" id="filterReInspections"
                                                    name="action">
                                                <i class="material-icons left">search</i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <table id="data-table-simple" class="display">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Policy Number</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Premium</th>
                                            <th>Product Description</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>KRA pin</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($policies as $policy)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{ isset($policy['policy_number']) ? $policy['policy_number'] : '' }}</td>
                                                <td>{{ isset($policy['from_date']) ? $policy['from_date'] : '' }}</td>
                                                <td>{{ isset($policy['to_date']) ? $policy['to_date'] : '' }}</td>
                                                <td>{{ isset($policy['premium']) ? number_format($policy['premium']) : '' }}</td>
                                                <td>{{ isset($policy['product_description']) ? $policy['product_description'] : ''}}</td>
                                                <td>{{ isset($policy['assured_name']) ? $policy['assured_name'] : '' }}</td>
                                                <td>{{ isset($email) ? $email : '' }}</td>
                                                <td>{{ isset($phone) ? $phone : '' }}</td>
                                                <td>{{ isset($policy['kra_pin']) ? $policy['kra_pin'] : '' }}</td>
                                                <td>
                                                    <!-- Dropdown Trigger -->
                                                    <a class='dropdown-trigger' href='#'
                                                       data-target='{{$loop->iteration}}'
                                                       data-activates="{{$loop->iteration}}"><i
                                                            class="Medium material-icons">menu</i><i
                                                            class="Medium material-icons">expand_more</i></a>
                                                    <ul id='{{$loop->iteration}}' class='dropdown-content'>
                                                        <li id="fetch-customer-payments" data-id="{{$ci_code}}">
                                                            <a href="#"><i class="material-icons">attach_money</i>View Payments</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" id="sendPolicyDocument" data-id="{{$email}}" data-id2="{{$policy['policy_number']}}"><i
                                                                    class="material-icons">notifications_active</i>Send Policy Document </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" id="triggerNotification" data-id=""><i
                                                                    class="material-icons">notifications_active</i>Send Notification </a>
                                                        </li>
                                                    </ul>
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
