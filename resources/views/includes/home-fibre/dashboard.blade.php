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
