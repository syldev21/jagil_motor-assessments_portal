<div class="row">

    <div class="content-wrapper-before  gradient-45deg-red-pink">
    </div>


    <div class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <h4 class="card-title float-left">
                                    Follower Proportions</h4>
                            </div>
                            <div class="row">
                                <div class="row" style="position: relative;">
                                    <div class="input-field col m3 s6" >
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
                                    <div class="input-field col m3 s12" >
                                        <div class="input-field col s12">
                                            <button class="btn cyan waves-effect waves-light" type="submit" id="filter_nhif_claims"
                                                    name="action">
                                                <i class="material-icons left">search</i> Filter
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 20px; font-size:9px;">
                                <a class="waves-effect waves-light btn" style="float: right; font-size: 11px; width: 100px;" id="approveAll">Approve all</a>
                            </div>
{{--                            <div class="divider"></div>--}}
                            <div class="row">
                                <div class="filteredPolicyData">

                                    <table id="data-table-simple" class="display responsive-table" style="background-color: white !important; border: none">
                                        <thead style="background-color: transparent">
                                        <tr class="prow">
                                            <th>
                                                <ul class="ul">
                                                    <li>Claim Number</li>
                                                    <li>Coinsurance</li>
                                                    <li>Share Percentage</li>
                                                    <li>Claim Amount</li>
                                                    <li>Share Amount</li>
                                                    <li>Paid Amount</li>
                                                    <li>Pending Amount</li>

                                                </ul>
                                            </th>
                                        </tr>

                                        </thead>
                                        <tbody style="background-color: red">
                                        @foreach ($claims as $claim)
                                            <tr style="background-color: white !important">
                                                <td colspan="16" style="font-size: 11px;" style="background-color: white !important">
                                                    <table class="tbl-accordion-nested tb" style="background-color: white !important">
                                                        <thead class="ths" style="background-color: white !important">
                                                        <tr class="tbl-accordion-section" style="background-color: white !important">


                                                            <td colspan="13">

                                                                <ul class="ul ul2" style=" margin: 0px 0px">
                                                                    <li style="border-color: transparent" >{{$claim->CLM_NO}}</li>
                                                                    <li style=""><i
                                                                            class="Medium material-icons">menu</i><i
                                                                            class="Medium material-icons">expand_more
                                                                        </i></li>
                                                                    <li >{{$claim->SHARE_PERC}}</li>
                                                                    <li >{{$claim->CLAIM_AMOUNT}}</li>
                                                                    <li style="">
                                                                        <i
                                                                            class="Medium material-icons">menu</i><i
                                                                            class="Medium material-icons">expand_more
                                                                        </i>
                                                                    </li>
                                                                    <li >{{$claim->PAID_AMOUNT}}</li>
                                                                    <li >{{$claim->PENDING_AMOUNT}}</li>
                                                                </ul>
                                                            </td>

                                                        </tr>

                                                        </thead>
                                                        <tbody class="bd">
                                                        @foreach (\App\FollowerProportion::where("claim_id", "=", $claim->id)->orderBy("SHARE_PERC", "DESC")->orderBy("COINSURER_NAME")->get() as $follower )

                                                            <tr class="test2">
                                                                <td></td>
{{--                                                                <td style="position: relative; left: 100px; background-color: rgba(0, 0, 0, 0)">{{$follower->COINSURER_NAME}}</td>--}}
{{--                                                                <td style="position: relative; right: 110px">{{$follower->SHARE_PERC}}</td>--}}
{{--                                                                <td style="position: relative; left: 50px">{{$follower->CLAIM_AMOUNT}}</td>--}}
{{--                                                                <td style="position: relative; left: 100px">{{$follower->SHARE_AMOUNT}}</td>--}}

{{--                                                                <td style="position: absolute; left: 300px">{{$follower->COINSURER_NAME}}</td>--}}
{{--                                                                <td style="text-align: center; position: absolute; right: 440px">{{$follower->SHARE_PERC}}</td>--}}
{{--                                                                <td style="text-align: center; position: absolute; right: 200px">{{$follower->CLAIM_AMOUNT}}</td>--}}
{{--                                                                <td style="text-align: center; position: absolute; right: 10px">{{$follower->SHARE_AMOUNT}}</td>--}}

                                                                <td style="text-align: center">{{$follower->COINSURER_NAME}}</td>
                                                                <td style="text-align: left; position: relative; right: 35px">{{$follower->SHARE_PERC}}</td>
                                                                <td style="text-align: center; position: relative; left: 10px">{{$follower->CLAIM_AMOUNT}}</td>
                                                                <td style="text-align: right">{{$follower->SHARE_AMOUNT}}</td>
                                                                <td style="text-align: center; position: relative; left: 10px">{{$follower->PAID_AMOUNT}}</td>
                                                                <td style="text-align: right">{{$follower->PENDING_AMOUNT}}</td>
                                                            </tr>
                                                        @endforeach

                                                        </tbody>
                                                    </table>
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
<script>
    $(document).ready(function(e){
        // $("#filter").select2({dropdownAutoWidth : true, width: '150px'});
        // $("#filterBy").select2({dropdownAutoWidth : true, width: '150px'});
        $("#parent").click(function() {

            if($(this).is(':checked'))
            {
                $('#approveAll').show();
            }
            else{
                $('#approveAll').hide();
            }

            $(".child").prop("checked", $(this).is(':checked'));
        });
        $('.child').click(function() {
            if ($('.child:checked').length == $('.child').length) {
                $('#parent').prop('checked', true);
            } else {
                $('#parent').prop('checked', false);
            }
        });


        $(".child").click(function(){
            if($(this).is(':checked'))
            {
                $('#approveAll').show()
            }
        });
        $('.tbl-accordion-nested').each(function(){
            var thead = $(this).find('thead');
            var tbody = $(this).find('tbody');
            tbody.hide();
            thead.click(function(e){
                $(this).toggleClass('disp');
                var tbody = $('.tbl-accordion-nested').find('tbody');
                tbody.not(this).hide();
                var tbody = $(this).find('tbody');
                if(thead.hasClass('disp'))
                {
                    $(this).next().toggle();
                }else{
                    $(this).next().hide();
                }
            })
        });

    });
</script>
