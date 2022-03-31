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
                                   @if($claimStatusID == \App\Conf\Config::NHIF_DISPLAY_STATUSES["SUBMITTED"]["ID"])
                                       {{\App\Conf\Config::NHIF_DISPLAY_STATUSES["SUBMITTED"]["TITLE"]}} NHIF Claims
                                    @endif
                                   @if($claimStatusID == \App\Conf\Config::NHIF_DISPLAY_STATUSES["IN_PROGRESS"]["ID"])
                                           NHIF Claims {{\App\Conf\Config::NHIF_DISPLAY_STATUSES["IN_PROGRESS"]["TITLE"]}}
                                    @endif
                                   @if($claimStatusID == \App\Conf\Config::NHIF_DISPLAY_STATUSES["PAID"]["ID"])
                                       {{\App\Conf\Config::NHIF_DISPLAY_STATUSES["PAID"]["TITLE"]}} NHIF Claims
                                    @endif
                                   @if($claimStatusID == \App\Conf\Config::NHIF_DISPLAY_STATUSES["CLOSED"]["ID"])
                                       {{\App\Conf\Config::NHIF_DISPLAY_STATUSES["CLOSED"]["TITLE"]}} NHIF Claims
                                    @endif
                                   @if($claimStatusID == \App\Conf\Config::NHIF_DISPLAY_STATUSES["REJECTED"]["ID"])
                                       {{\App\Conf\Config::NHIF_DISPLAY_STATUSES["REJECTED"]["TITLE"]}} NHIF Claims
                                    @endif
                                   @if($claimStatusID == \App\Conf\Config::NHIF_DISPLAY_STATUSES["DOCUMENTS_PENDING"]["ID"])
                                       Submitted NHIF Claims- {{\App\Conf\Config::NHIF_DISPLAY_STATUSES["DOCUMENTS_PENDING"]["TITLE"]}}
                                       @endif
{{--                                    @foreach($datedClaims as $datedClaim){--}}
{{--                                        Claims Dated Between {{$fromDate}} and {{$toDate}}--}}
{{--                                       @endforeach--}}
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
                                        <input id="claimStatusID" type="hidden" value="{{$claimStatusID}}">
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
                                            <th>S/N</th>
                                            <th>Claim Number</th>
                                            <th>Name of Claimant</th>
                                            <th>ID Number</th>
                                            <th>Date of Loss</th>
                                            <th>Cause of Loss</th>
                                            <th>Place of Injury</th>
                                            <th>Date Created</th>
                                            <th>Date Received</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($claims as $claim)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>

                                                <td>

                                                    <a href="#" class="nhifClaimDetails" data-id="{{$claim->status}}" id="{{$claim["claimNo"]}}">{{$claim["claimNo"]}}</a>
                                                </td>

                                                <td>{{$claim->claimant}}</td>
                                                <td>{{$claim->IDNumber}}</td>
                                                <td>{{$claim->dateOfInjury}}</td>
                                                <td>{{$claim->causeOfLoss}}</td>
                                                <td>{{$claim->placeOfLoss}}</td>
                                                <td>{{$claim->dateCreated}}</td>
                                                <td>{{$claim->dateReceived}}</td>
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
                                                            <a href="#" id="uploadDocumentsForm" data-id="{{$claim->id}}" ><i
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
