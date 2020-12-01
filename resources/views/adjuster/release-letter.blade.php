<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<div style="text-align: center">
    <img src="{{ public_path('images/logo/jubilee_logo.png') }}" class="content-group mt-10" alt="" style="width: 120px;">
</div>
<h4>DATE: {{ Carbon\Carbon::now()->format('l, F d, Y') }}</h4>
<h4>OUR REF: <span style="margin-left: 20px">{{ $claim->claimNo }}</span></h4>

<p>
    The Workshop Manager, <br>
    {{ \App\Garage::where(['id'=>$claim->garageID])->first()->name }}
    <br>
    <u>{{ \App\Garage::where(['id'=>$claim->garageID])->first()->location }}</u>
</p>

<p>Dear Sir,</p>
<p><strong>RE: ACCIDENT TO  {{ $claim->vehicleRegNo }} ON {{ $claim->intimationDate }}
        <br> &nbsp; &nbsp;&nbsp;	INSURED: {{ isset($claim->customer->firstName) ? $claim->customer->firstName : ''}} {{isset($claim->customer->middleName) ? $claim->customer->middleName : ''}} {{isset($claim->customer->lastName) ? $claim->customer->lastName  : ''}}</strong>
</p>

<p>Please release the above-mentioned vehicle to the insured and/or their authorized representative after re-inspection by the assessor who authorized repairs.</p>

<p>Kindly have the insured sign a satisfaction note, which please forward to us together with the repair invoice for our consideration.<p>

<p>Yours faithfully,</p>

<p>
    <strong>{{ isset(Auth::user()->firstName) ? Auth::user()->firstName : '' }} {{ isset(Auth::user()->middleName) ? Auth::user()->middleName : '' }} {{ isset(Auth::user()->lastName) ? Auth::user()->lastName : '' }}<BR>
            <u>{{ $role }}</u><BR>
                <u>TEL 0709901537</u></BR>
    </strong>
</p>

<p>
    <strong>Note:</strong><br><br>
    You will retain the scrap metal as per the agreed terms.

</p>

</body>
</html>
