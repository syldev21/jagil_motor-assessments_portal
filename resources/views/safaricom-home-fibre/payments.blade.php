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
                                <h4 class="card-title float-left">Payments</h4>
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
                                            <th>Product</th>
                                            <th>M-pesa Code</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Payment Date</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{ isset($payment['client_name']) ? $payment['client_name'] : '' }}</td>
                                                <td>{{ isset($payment['client_email']) ? $payment['client_email'] : '' }}</td>
                                                <td>{{ isset($payment['client_phone']) ? $payment['client_phone'] : '' }}</td>
                                                <td>{{ isset($payment['product']) ? $payment['product'] : '' }}</td>
                                                <td>{{ isset($payment['mpesa_code']) ? (strlen($payment['mpesa_code']) < 15 ? $payment['mpesa_code'] : '') : '' }}</td>
                                                <td>{{ isset($payment['amount']) ? $payment['amount'] : '' }}</td>
                                                <td>{{ isset($payment['payment_description']) ? $payment['payment_description'] : '' }}</td>
                                                <td>{{ isset($payment['payment_date']) ? $payment['payment_date'] : '' }}</td>
                                                <td>
                                                    <!-- Dropdown Trigger -->
                                                    <a class='dropdown-trigger' href='#'
                                                       data-target='{{$loop->iteration}}'
                                                       data-activates="{{$loop->iteration}}"><i
                                                            class="Medium material-icons">menu</i><i
                                                            class="Medium material-icons">expand_more</i></a>
                                                    <ul id='{{$loop->iteration}}' class='dropdown-content'>
                                                        <li id="fetch-customer-payments" data-id="{{$payment['code']}}">
                                                            <a href="#"><i class="material-icons">attach_money</i>View Payments</a>
                                                        </li>
                                                        <li id="fetch-policy-details" data-id="{{$payment['code']}}" data-id2="{{$payment['client_email']}}" data-id3="{{$payment['client_phone']}}">
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
