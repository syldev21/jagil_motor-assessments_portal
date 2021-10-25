<div id="loader-wrapper" class="hideLoader">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>
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
                                <h4 class="card-title float-left">Customers</h4>
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
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Product</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($customers as $customer)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{ isset($customer['client_name']) ? $customer['client_name'] : '' }}</td>
                                                <td>{{ isset($customer['client_email']) ? $customer['client_email'] : '' }}</td>
                                                <td>{{ isset($customer['client_phone']) ? $customer['client_phone'] : '' }}</td>
                                                <td>{{ isset($customer['physical_address']) ? $customer['physical_address'] : '' }}</td>
                                                <td>{{ isset($customer['product']) ? $customer['product'] : '' }}</td>
                                                <td>
                                                    <!-- Dropdown Trigger -->
                                                    <a class='dropdown-trigger' href='#'
                                                       data-target='{{$loop->iteration}}'
                                                       data-activates="{{$loop->iteration}}"><i
                                                            class="Medium material-icons">menu</i><i
                                                            class="Medium material-icons">expand_more</i></a>

                                                    <ul id='{{$loop->iteration}}' class='dropdown-content'>
                                                        <li id="fetch-customer-payments" data-id="{{$customer['code']}}">
                                                            <a href="#"><i class="material-icons">attach_money</i>View Payments</a>
                                                        </li>
                                                        <li id="fetch-policy-details" data-id="{{$customer['code']}}" data-id2="{{$customer['client_email']}}" data-id3="{{$customer['client_phone']}}">
                                                            <a href="#"><i class="material-icons">attach_money</i>View Policy</a>
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
