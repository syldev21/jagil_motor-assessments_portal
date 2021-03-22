
@include('_partials.header')
@include('_partials.navbar')
@include('_partials.sidebar')
<div id="main">
   <div class="col s2">
    <button type="button" style="margin-top: -10px; margin-right: 50px;" class="btn teal float-right" onclick="printDiv()"><i class="material-icons"
            style="font-size: 2em;">local_printshop</i></button>
</div>
<div id="printableArea" style="margin-top:70px; margin-left: auto; margin-right: auto; width: 80%; margin-bottom:200px;">


    <div>
        <img src="{{ asset('images/logo/jubilee_logo.png') }}" alt="" style="width: 120px; text-align: left; margin-right:44%; float: left; ">

    <div style=" text-align:center; width: 35%; height: 70px; border-top: 1px solid #cf0d3d; border-bottom: 5px solid #cf0d3d;  margin-top:30px ; float: left; margin-right:0px;">
        <p style="margin-top: 30px;"> <b>DISCHARGE VOUCHER</b> </p>
    </div>
    </div>

    <div style="float: left; margin-right: 18%; width:40%;">
        <p > <b>JUBILEE GENERAL INSURANCE LIMITED</b> </p>
        <div style="line-height: 10%; font-size:13px;">
        <p>Head Office:</p>
        <p>Jubilee Insurance House, Wabera Street,</p>
        <p>P.O. Box 6685 - 00100 GPO, Nairobi, Kenya</p>
        <p>Tel: +254 20 328 1000</p>
        <p>Call Centre: +254 709 949 000</p>
        <p>Email: talk2us@jubileekenya.com</p>
        <p>www.jubileeinsurance.com</p>
        </div>
    </div>
    <div style="float: left;">
        <h5>Tracking No:</h5>
        <div style="line-height: 5%; padding: 3px; -webkit-print-color-adjust:exact;" class="card-panel grey lighten-2">
            <p style="font-size: 10px;"><i><b>DIRECTIONS:</b></i></p>
            <div style="font-size: 10px;">
            <p ><i>All sections must be answered in full and in BLOCK letters.</i></p>
            <p><i>Complete the <b>bank details form</b> and attach a copy of</i></p>
            <p><i>your <b>ATM Card</b>  or <b>cancelled cheque</b> that will assist us</i></p>
            <p><i>to transfer the funds to your bank account.</i></p>
            </div>
        </div>
    </div>
    <div style="clear:both; text-align:right;">
        <h5 style="margin-right: 8%;">“Without Prejudice”</h5>
    </div>
    <div style="line-height: 1%; ">
        <p style="font-weight: bolder"> <u>MOTOR DISCHARGE VOUCHER (CASH-IN-LIEU OF REPAIRS)</u></p>
        <p style="float: left; margin-right:10%;">POLICY No. {{$assessment->claim->policyNo}}</p>
        <p style="float: left;">CLAIM NO. {{$assessment->claim->claimNo}}</p>
    </div>
    <div style="clear: both; text-align:justify; width:95%">
    <p>I/We, <span style="text-transform: uppercase"><b>{{$insured->fullName}}</b></span> in the republic of Kenya, hereby accept from The Jubilee General Insurance (the insurer) in
    the republic aforesaid, a sum of <span style="text-transform: uppercase"> <b>{{number_format($amount)}}</b> </span> in words – Kenya
    Shillings <b>{{$val}}</b>/= (for cash-in-lieu of repairs) in full and final settlement and discharge of all my/our claim(s)
    (past, present and future) which I/we our/my executors, administrators or assignees have, may have or could have made
    against the said insurer in respect of vandalism damage to motor vehicle <b>{{$assessment->claim->vehicleRegNo}}</b> on or about the <b>{{$assessment->claim->loseDate}}</b> (hereinafter
    referred to as ‘the loss’).</p>

    <p>
        It is further understood and agreed that in consideration of the aforesaid sum being paid to me/us, I/we assign hereby
        <b>ALL</b> my/our rights in the case of recovery herein to the said insurer to pursue in my/our name and undertake to refund
        (if applicable) to the insurer any amount paid to me/us in respect of such recovery. In case legal liability claims
        arising from the incident are lodged against myself/the company or any other person or the company institutes a recovery
        under subrogation, I commit to avail all the relevant information including the witnesses to assist the company
        prosecute and/or defend the claims in court.
    </p>

    <p>
        I/We acknowledge that I/we have not been induced to sign this discharge by any representation(s) made to me/us by the
        insurer or their servants/agents and that I/we have executed this document on my/our own accord voluntarily. I also
        understand that after repairs
    </p>
    <div style="line-height: 1%;">
            <p style="float: left; margin-right:100px;"><b>CLAIM NO:</b> {{$assessment->claim->claimNo}}</p>
            <p style="float: left;"><b>TRACKING NO:</b></p>
    </div>
    <br> <br>
    <p style="clear: both;">The insurer will have the right to Re-inspect the repair works. In the event that the repair work is not satisfactorily
    done, and/or some parts which were recommended for replacement are not replaced, the insurer will have the right to
    adjust the repair costs and only pay for the work done. In addition, the insurer may downgrade the insurance policy in
    respect to the vehicle to third party only.</p>
    <p>I/We also agree and understand that in the event the insurers make discovery of any fact or occurrence which makes or
    would make my/our claim against them for the said loss inadmissible or not payable for whatsoever reason, then the
    insurers shall be under no obligation hereunder or otherwise, to compensate me/us for the said loss or to pay to me/us
    the said sum or any part thereof or at all and in the event that the insurers have paid, they shall have a right of
    recovery against me/us of the amounts so paid.</p>

    <p style="color: #cf0d3d "> <b><i>Complete the bank details form and attach a copy of your ATM Card or cancelled cheque that will assist us to transfer
    the funds to your bank account.</i></b></p>

    <div style=" text-align:center; width: 33%; height: 40px;  border-top: 1px solid black;  margin-top:3% ; float: left; margin-right: 20%;">
        <p style="margin-top: 2px;">(Official Stamp of the Insured)</p>
    </div>
    <div
        style=" text-align:center; width: 45%; height: 40px;  border-top: 1px solid black;  margin-top:3% ; float: left; margin-right:0px;">
        <p style="margin-top: 2px;">Name of Official Signing</p>
    </div>

    <div style=" clear:both; text-align:center; width: 33%; height: 40px;  border-top: 1px solid black;  margin-top:3% ; float: left; margin-right:20%;">
        <p style="margin-top: 2px;">Signature of Official Signing</p>
    </div>
    <p style="float: left;">Date:</p>
    <div
        style=" text-align:center; width: 42%; height: 40px;  border-top: 1px solid black;  margin-top:3% ; float: left; margin-right:0px;">

    </div>

    <p style=" clear:both; float: left;">Name of Witness:</p>
    <div
        style=" text-align:center; width: 30%; height: 40px;  border-top: 1px solid black;  margin-top:30px ; float: left; margin-right:10px;">

    </div>
    <p style="float: left;">Signature of Witness:</p>
    <div
        style=" text-align:center; width: 20%; height: 40px;  border-top: 1px solid black;  margin-top:30px ; float: left; margin-right:0px;">

    </div>

    <div style=" clear:both; color: #cf0d3d; text-align:center; "><b><i>(For Jubilee General Insurance use only)</i></b></div>
    <br>
    <div style="margin-right: 100px;">

    <p style="float: left;">1st Signatory</p>
    <div
        style=" text-align:center; width: 33%; height: 5px;    margin-top:30px ; float: left; margin-right:0px;">

    </div>

    <p style="float: left;">Name:</p>
    <div
        style=" text-align:center; width: 32%; height: 5px;  border-top: 1px solid black;  margin-top:30px ; float: left; margin-right:0px;">

    </div>

    <p style="float: left; clear:both;">Signature:</p>
    <div
        style=" text-align:center; width: 25%; height: 5px;  border-top: 1px solid black;  margin-top:30px ; float: left; margin-right:10%;">

    </div>
    <p style="float: left;">Date:</p>
    <div
        style=" text-align:center; width: 33%; height: 5px;  border-top: 1px solid black;  margin-top:30px ; float: left; margin-right:0px;">

    </div>

    <p style="float: left; clear:both"> 2 <sup>nd</sup> Signatory</p>
    <div style=" text-align:center; width: 32%; height: 5px;    margin-top:30px ; float: left; margin-right:0px;">

    </div>

    <p style="float: left;">Name:</p>
    <div
        style=" text-align:center; width: 33%; height: 5px;  border-top: 1px solid black;  margin-top:30px ; float: left; margin-right:0px;">

    </div>

    <p style="float: left; clear:both;">Signature:</p>
    <div
        style=" text-align:center; width: 25%; height: 5px;  border-top: 1px solid black;  margin-top:30px ; float: left; margin-right:10%;">

    </div>
    <p style="float: left;">Date:</p>
    <div
        style=" text-align:center; width: 33%; height: 5px;  border-top: 1px solid black;  margin-top:30px ; float: left; margin-right:10%;">

    </div>
    </div>
    <div >

    <div style=" border: 2px dotted black; width : 20%; height:150px; margin-top: -21%;  text-align: center; float:right;">
        <p style="margin-top: 35%;  color: gray"> STAMP</p>

    </div>
    </div>
    <div style=" clear: both; text-align:center; margin-bottom: 25px;">
        <p style="color: #cf0d3d; "><b><i>This document is not valid unless signed and stamped</i></b></p>
    </div>








    </div>

</div>

</div>

{{-- </body>
</html> --}}

@include('_partials.settings')
@include('_partials.footer')


