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
                                <h4 class="card-title float-left"></h4>
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
                                        <input id="policyNumber" type="text" class="validate">
                                        <input id="assessmentStatusID" type="hidden" value="">
                                        <input id="status" type="hidden" value="{{isset($policyStatus) ? $policyStatus : ''}}">
                                        <label for="policyNumber">Policy No</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light" type="submit" id="filter-travel-policies"
                                                    name="action">
                                                <i class="material-icons left">search</i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="row ">
                                <div class="col s12 ">
                                    <table id="data-table-simple" class="display">
                                        <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Policy Number</th>
                                            <th>Claim Number</th>
                                            <th>Loss Date</th>
                                            <th>Cause of Loss</th>
                                            <th>Intimation Date</th>
                                            <th>Loss Description</th>
                                            <th>Nature of Loss</th>
                                            <th>Estimation Amount</th>
                                            <th>Cover Type</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($policies as $policy)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$policy['policyNumber']}}</td>
                                                <td>{{$policy['claimNumber']}}</td>
                                                <td>{{$policy['lossDate']}}</td>
                                                <td>{{$policy['causeOfLoss']}}</td>
                                                <td>{{$policy['intimationDate']}}</td>
                                                <td>{{$policy['lossDescription']}}</td>
                                                <td>{{$policy['natureOfLoss']}}</td>
                                                <td>{{$policy['estimationAmount']}}</td>
                                                <td>{{$policy['coverType']}}</td>
                                                <td>
                                                    <!-- Dropdown Trigger -->
                                                    <a class='dropdown-trigger' href='#'
                                                       data-target='{{$loop->iteration}}'
                                                       data-activates="{{$loop->iteration}}">
                                                        <i
                                                            class="Medium material-icons">menu</i><i
                                                            class="Medium material-icons">expand_more
                                                        </i>
                                                    </a>

                                                    <!-- Dropdown Structure -->

                                                    <ul id='{{$loop->iteration}}' class='dropdown-content'>

                                                        <li>
                                                            <a href="#" id="uploadDocumentsForm" ><i
                                                                    class="material-icons">upload</i>Upload Document</a>
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
            @include('common.generic-notification')
        </div>
    </div>
</div>
