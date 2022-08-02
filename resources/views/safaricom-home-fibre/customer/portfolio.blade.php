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
                                <h4 class="card-title float-left">
                                    My Portfolio
                                </h4>

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
                                        <input id="claimNo" type="text" class="validate">
                                        <input id="claimStatusID" type="hidden" value="">
                                        <label for="claimNo">Claim No</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light" type="submit" id="filter_nhif_claims"
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
{{--                                            <th>S/N</th>--}}
                                            <th>Product Name</th>
                                            <th>Policy Number</th>
                                            <th>Policy From Date</th>
                                            <th>Policy To Date</th>
                                            <th>Sum Insured</th>
                                            <th>Premium</th>
                                            <th>Status</th>
                                            <th>Operations</th>
                                        </tr>
                                        </thead>
                                        <tbody>
{{--                                        @foreach($customer_portforlio as $portforlio)--}}

                                            <tr>
{{--                                                <td>{{$loop->iteration}}</td>--}}


                                                <td>{{$customer_portforlio[2][0]["product"]}}</td>
                                                <td>{{$customer_portforlio[0][0]["policy_number"]}}</td>
                                                <td>{{$customer_portforlio[0][0]["from_date"]}}</td>
                                                <td>{{$customer_portforlio[0][0]["to_date"]}}</td>
                                                <td></td>
                                                <td>{{$customer_portforlio[0][0]["premium"]}}</td>
                                                <td>{{$customer_portforlio[1]}}</td>
                                                <td>
                                                    <!-- Dropdown Trigger -->
                                                    <a class='dropdown-trigger' href='#'
                                                       data-target='{{$customer_portforlio[0][0]["policy_number"]}}'
                                                       data-activates="{{$customer_portforlio[0][0]["policy_number"]}}"><i
                                                            class="Medium material-icons">menu</i><i
                                                            class="Medium material-icons">expand_more</i></a>

                                                    <!-- Dropdown Structure -->
                                                    <ul id='' class='dropdown-content'>

                                                            <li>
                                                                <a href="" download><i
                                                                        class="material-icons">file_download</i>Claim Form</a>
                                                            </li>

                                                            <li>
                                                                <a href="" download><i
                                                                        class="material-icons">file_download</i>Invoice</a></li>
                                                            <li>
                                                                <a href="#" id="fillAssessmentReport"
                                                                   data-id=""><i
                                                                        class="material-icons">insert_drive_file</i>Submit
                                                                    Assessment </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" id="view-assessor-assessment-report"
                                                                   data-id=""><i
                                                                        class="material-icons">edit</i>Edit Assessment </a>
                                                            </li>

                                                            <li>
                                                                <a href="#" id="fillAssessmentReport"
                                                                   data-id=""><i
                                                                        class="material-icons">insert_drive_file</i>Fill
                                                                    Report </a>
                                                            </li>
                                                    </ul>

                                                </td>
                                            </tr>
{{--                                        @endforeach--}}
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
