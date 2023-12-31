<div id="loader-wrapper" class="hideLoader">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>
@if(auth::user()->userTypeID == \App\Conf\Config::$USER_TYPES['HOME FIBER CUSTOMER']['ID'])
    @if(auth::user()->ci_code==null)

        @else

    <div class="row">
        <div class="col s4" style="position: relative; left: 970px; top: 10px">
{{--                <div class="card padding-4 animate fadeRight">--}}
                    <!-- Modal Trigger -->
                    <a href="#" id="contactUsTrigger" class="btn blue lighten-2 waves-effect"><i class="material-icons">dialpad</i>
                        <span data-i18n="Chartist">Contact Us</span></a>
{{--                </div>--}}
        </div>


{{--        ContactUSModal structure--}}

        <div id="contactUs" class="modal">
            <div class="modal-content" style="background-color: #003781">
                <div class="modal-header">
                    <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
                </div>
                <div class="modal-body clearfix">
                    <h3 style="color: white; font-size: 30px">Jubilee Allianz General Insurance (k) Limited</h3>

                    <p style="color: white">Jubilee Insurance Centre,</p>
                    <p style="color: white">Wabera Street, Nairobi</p>
                    <p style="color: white">P.O. Box 6685 - 00100 GPO, Nairobi, Kenya</p>
                    <p style="color: white">Telephone: +254 20 328 1000</p>
                    <p style="color: white">Call Centre: +254 709 949 000</p>
                    <p style="color: white">Talk2us@allianz.com</p>
                </div>
            </div>
        </div>
        {{--        requestChangeModal structure--}}

        <div id="requestChange" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
                </div>
                <div class="modal-body clearfix">
                    <h3 style="color: white; font-size: 30px">Jubilee General Allianz</h3>

                    <p style="color: white">Jubilee Insurance Centre,</p>
                    <p style="color: white">Wabera Street, Nairobi</p>
                    <p style="color: white">P.O. Box 6685 - 00100 GPO, Nairobi, Kenya</p>
                    <p style="color: white">Telephone: +254 20 328 1000</p>
                    <p style="color: white">Call Centre: +254 709 949 000</p>
                    <p style="color: white">Talk2us@allianz.com</p>
                </div>
            </div>
        </div>
    </div>


<div class="row">
    <div class="row col s12" id="police" style="border: 2px solid gray; text-align: right"></div>
    <div class="col s12 m6 l4">
    </div>
    <div class="col s12 m6 l4">
        <h5 style="text-align: center;"><span class="btn">{{$user->name}}'s Detail Summary</span></h5>
    </div>
    <div class="col s12 m6 l4">
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p><u>
                        <i class="material-icons">recent_actors</i>
                        <span data-i18n="Chartist">Name</span>
                </u></p>

{{--                <p>{{$user->name}}</p>--}}
                <p><b>{{$user->name}}</b></p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeLeft">
            <center>
                    <p><u>
                        <i class="material-icons">contact_phone</i>
                        <span data-i18n="Chartist">Phone Number</span>
                    </u></p>

                <p><b>{{$user->MSISDN}}</b></p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p><u>
                        <i class="material-icons">local_post_office</i>
                        <span data-i18n="Chartist">Postal Address</span>
                </u></p>
                <p><b>
                       {{$user->physical_address}}
                    </b></p>

            </center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p><u>
                        <i class="material-icons">contact_mail</i>
                        <span data-i18n="Chartist">Email Address</span>
                </u></p>

                <p><b>{{$user->email}}</b></p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeLeft">
            <center>
                <p><u>
                        <i class="material-icons">verified_user</i>
                        <span data-i18n="Chartist">ID Number</span>
                </u></p>
                <p><b>{{$user->idNumber}}</b></p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p><u>
                        <i class="material-icons">fiber_pin</i>
                        <span data-i18n="Chartist">KRA PIN</span>
                    </u></p>
                <p><b>{{isset($user->kra_pin)?$user->kra_pin:""}}</b></p>
            </center>
        </div>
    </div>
</div>
@endif
@else
    <div class="row">
    <div class="col s12 m6 l4">
    </div>
    <div class="col s12 m6 l4">
        <h5 style="text-align: center;"><span class="btn">Safaricom Home Fiber Summary</span></h5>
    </div>
    <div class="col s12 m6 l4">
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Expected Revenue</p>
                <i class="material-icons" style="color: teal;font-size: 3em;">navigation</i>
                <p>{{number_format($summary['expected_revenue'])}}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeLeft">
            <center>
                <p>Received Payments</p>
                <i class="material-icons" style="color: green;font-size: 3em;">attach_money</i>
                <p>{{number_format($summary['received_payments'])}}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Completed KYC</p>
                <i class="material-icons" style="color: darkslategrey;font-size: 3em;">create</i>
                <p>{{number_format($summary['completed_kyc'])}}</p>
            </center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Not Completed KYC</p>
                <i class="material-icons" style="color: teal;font-size: 3em;">error_outline</i>
                <p>{{number_format($summary['non_completed_kyc'])}}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeLeft">
            <center>
                <p>Emailed Policies</p>
                <i class="material-icons" style="color: green;font-size: 3em;">email</i>
                <p>{{number_format($summary['emailed_policies'])}}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Not Emailed Policies</p>
                <i class="material-icons" style="color: orangered;font-size: 3em;">drafts</i>
                <p>{{number_format($summary['not_emailed_policies'])}}</p>
            </center>
        </div>
    </div>
</div>
@endif
