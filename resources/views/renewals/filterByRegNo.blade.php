<table id="data-table-simple" class="display">
    <thead>
        <tr class="prow">
            <th style="wihth: 20px; position:relative; left:0px;">
                <label>
                    <input type="checkbox" id="parent" class="filled-in" />
                    <span></span>
                </label>
            </th>
            <th>
                <ul class="ul">
                    <li>Customer  Name</li>
                    <li>Asurred Name</li>
                    <li>Policy No.</li>
                    <li >Product<br /> Description</li>
                    <li>Cover Type</li>
                    <li >Make</li>
                    <li >Model</li>
                    <li >RegNo.</li>
                    {{-- <li>YOM</li> --}}
                    <li>Last Year <br />  Premium</li>
                    <li>Renewal <br /> Premium</li>
                    <li>Loss <br /> Ratio</li>
                    {{-- <li>Claim Amount</li> --}}
                    <li>Load <br /> Factor</li>
                    <li>Approved</li>
                    </ul>
            </th>
        </tr>

    </thead>
    <tbody>



        @foreach ($renewals as $renewal)
        <tr style="width: 40px; position:absolute;z-index:3333;">
            <td style="border:none;">
                <ul style="width: 40px;">
                    <li style="width: 40px;">
                        <label style="position: relative; left:10px;">
                            <input type="checkbox" class="child filled-in cd" />
                            <span></span>
                        </label>
                    </li>
                </ul>
            </td>
        </tr>

        <tr style="align-content: center;">
            <td colspan="16" style="font-size: 11px;">
                <table class="tbl-accordion-nested tb">
                    <thead class="ths" style="position: relative;">


                        <tr class="tbl-accordion-section" style="z-index: -1;">

                            <td colspan="13">


                                <ul class="ul">

                                    <li style="wihth: 20px; position:relative; left:10px;">
                                        {{-- <label>
                                            <input type="checkbox"  class="filled-in cd child" />
                                            <span></span>
                                        </label> --}}
                                    </li>

                                    <li style="width:100px; position:relative;left:40px;" >{{$renewal->customerName}}</li>
                                    <li style="width: 70px; word-break: break-all;position:relative; left:50px; ">{{$renewal->assuredName}}</li>
                                    <li style="width:80px; position:relative; left: 70px;word-break: break-all;">{{$renewal->policyNumber}}</li>
                                    <li style="width:100px;position:relative; left: 60px; ">{{$renewal->productDesc}}</li>
                                    <li style="width: 80px;position:relative; left: 60px;"> {{$renewal->coverType}}</li>
                                    <li style="width: 60px;position:relative;left: 48px; font-size:9px;word-break: break-all; ">{{$renewal->make}}</li>
                                    <li style="width: 60px;position:relative;left: 38px; font-size:9px; word-break: break-all;">{{$renewal->model}}</li>
                                    <li class="reg" style="width: 80px;position:relative; left: 25px;font-size:9px;" >{{$renewal->vehicleRegNo}}</li>
                                    {{-- <li class="yom" style="width: 80px; margin-left: -50px;position: relative;left:10px; ">{{$renewal->YOM}}</li> --}}
                                    <li style="width: 80px;position:relative;">{{$renewal->premiumFC}}</li>
                                    <li style="width: 80px;position:relative;left:-20px;">{{\App\Renewal::where(['vehicleRegNo' => $renewal->vehicleRegNo])->sum("renewalPremium")}}</li>
                                    <li style="width: 80px;position:relative;right:45px;  position:relative; right:30px">{{$renewal->lossRatio}}</li>
                                    {{-- <li style="width: 10px;position: relative;left: -50px;">{{$renewal->claimAmount}}</li> --}}
                                    <li style="width: 10px;position:relative;right:67px;">{{$renewal->loadFactor}}</li>
                                    <li class="test" style="width: 10px;position:relative; right: 15px;">
                                        @if(isset($renewal->approvedAll) && $renewal->approvedAll > 0) <i class="material-icons"  data-id="{{$renewal->ID}}"  style="color: green;">checked</i>
                                        @else<i class="material-icons"  data-id="{{$renewal->ID}}" style="color: red">clear</i>@endif
                                    </li>
                                </ul>
                            </td>

                        </tr>
                    </thead>
                    <tbody class="bd">

                        <tr style="font-weight: bold;">
                            <td>Cover Description</td>
                            <td>premiumSiFc</td>
                            <td>Applied Rate</td>
                            <td>Applied Rate Per</td>
                            <td>Applied Minimum Premium</td>
                            <td >Last Year Premium</td>
                            <td>Corrected Premium</td>
                            <td>Corrected</td>
                            <td>Action</td>
                        </tr>
                        @foreach (\App\Renewal::where(['vehicleRegNo' => $renewal->vehicleRegNo])->get() as $subrenewal )
                        <tr class="test2">
                            <td style="width: 150px; word-break: break-all;">{{$subrenewal->coverDescription}}</td>
                            <td style="text-align: center;">{{$subrenewal->premiumSiFc}}</td>
                            <td style="text-align: center;">{{$subrenewal->applicationRate}}</td>
                            <td style="text-align: center;">{{$subrenewal->applicationRatePer}}</td>
                            <td style="padding-left:60px;">{{$subrenewal->applicationMinimumPremium}}</td>
                            <td style="padding-left:40px;">{{$subrenewal->premiumFC}}</td>
                            {{-- <td>{{$subrenewal->FAPPremium}}
            </td>
            <td>{{$subrenewal->renewalPremium}}</td> --}}
            <td class="status"><input type="text" data-id="{{$subrenewal->ID}}" data-id2="{{$subrenewal->policyNumber}}"  class="rp"
                    value="{{$subrenewal->renewalPremium}}" style="width: 80px;"></td>
            <td class="tt ">@if(isset($subrenewal->approved) && $subrenewal->approved > 0) <i class="material-icons "
                    style="color: green;">checked</i> @else<i class="material-icons " style="color: red;">clear</i>@endif
            </td>
            <td><a class="waves-effect waves-light btn approveSubDetails approveParentDetails">Approve</a></td>

        </tr>
        @endforeach
        {{-- <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td> <b> Total Basic Premium</b></td>
            <td><b >{{\App\Renewal::where(['policyNumber' => $renewal->policyNumber, 'policyToDate'=> $formattedDate ])->sum("renewalPremium")}}</b></td>
        </tr> --}}

    </tbody>
</table>
</td>
</tr>
@endforeach

</tbody>
</table>


<script>
    $(document).ready(function(e){


        $('.approveSubDetails').on('click', function(){
                var val = $(this).parent().prev().prev().children().val();
                var id = $(this).parent().prev().prev().children().data('id');
                var id2 = $(this).parent().prev().prev().children().data('id2');

                var child = $(this).parent().prev();
                $(this).parent().prev().html(

`<div class="preloader-wrapper small active">
    <div class="spinner-layer spinner-green-only">
      <div class="circle-clipper left">
        <div class="circle"></div>
      </div><div class="gap-patch">
        <div class="circle"></div>
      </div><div class="circle-clipper right">
        <div class="circle"></div>
      </div>
    </div>
  </div>`
  );




                $.ajaxSetup({

                headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

                });
                $.ajax({

                type: 'POST',


                url: '/updatingRenewalPremium',

                data: {val: val, id:id},

                success: function (data) {
                    child.html('<i class="material-icons " style="color: green;">checked</i>');


                }

                });

        });
        // $('.rp').on('keyup', function(){
        //         alert($(this).val());
        // });
        $('#filterBy').on('change', function()
        {
          var val=$('#filterBy').val();
          if(val=="lossRatio_below_60" || val=="lossRatio_above_60")
          {
              $('#hideSelect').hide();
          }else{
              $('#hideSelect').show();
          }
        $.ajaxSetup({

        headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

        });
        $.ajax({

        type: 'GET',


        url: '/getselect',

        data: {val: val},

        success: function (data) {
        $(".sel").html(data);


        }

        });
        });

        $("#filter").select2({dropdownAutoWidth : true, width: '150px'});
        $("#filterBy").select2({dropdownAutoWidth : true, width: '150px'});
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
