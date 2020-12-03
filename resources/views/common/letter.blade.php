
@include('_partials.header')
@include('_partials.navbar')
@include('_partials.sidebar')
<div id="main">
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
                                    <div class="col s10">
                                        <h4 class="card-title float-left">Re-Inspection Report</h4>
                                    </div>
                                    <div class="col s2">
                                        <button type="button" class="btn teal float-right" onclick="printDiv()"><i class="material-icons" style="font-size: 2em;">local_printshop</i></button>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div id="printableArea">
                                    <div class="row">
                                        <div class="col s5">

                                        </div>
                                        <div class="col s2">
                                            <img class="responsive-img" src="{{url('images/logo/jubilee_logo.png') }}">
                                        </div>
                                        <div class="col s5">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s2">

                                        </div>
                                        <div class="col s8 center-align">
                                            <h5>JUBILEE INSURANCE ASSESSORS REPORT</h5>
                                            <h6>MOTOR RE-INSPECTION REPORT</h6>
                                        </div>
                                        <div class="col s2">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12">
                                            <div class="col s12">
                                                <table id="page-length-option" class="display">
                                                    <thead>

                                                    <tr>

                                                        <th><strong>Claim Number</strong></th>

                                                        <th><strong>Insured</strong></th>

                                                        <th><strong>Vehicle Registration</strong></th>

                                                        <th><strong>Date</strong></th>

                                                    </tr>

                                                    </thead>
                                                    <tbody>

                                                    <tr>

                                                        <td>{{ $claim }}</td>

                                                        <td>{{ $insured }}</td>

                                                        <td>{{ $vehicleRegNo }}</td>

                                                        <td>{{ date('l jS F Y', strtotime($day)) }}</td>

                                                    </tr>


                                                    </tbody>

                                                </table>

                                                <p>

                                                    We refer to our earlier assessment report on the above motor vehicle dated

                                                    {{ date('l jS F Y', strtotime($assessmentDate)) }}.

                                                </p>

                                                <p>

                                                    We are now pleased to confirm that the repairs have since been completed
                                                    satisfactorily and in good time.

                                                </p>

                                                @if($addLabor != 0)

                                                    <p>Additional labor to garage is KShs.

                                                        <strong>{{ number_format($addLabor * (\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE/\App\Conf\Config::INITIAL_PERCENTAGE)) }}.</strong>

                                                        (<strong>NB</strong>: This has already been reflected in the total
                                                        amount)

                                                    </p>

                                                @endif

                                                <p>

                                                    The garage to invoice Kshs. {{ number_format($amount) }} Inclusive {{\App\Conf\Config::CURRENT_VAT_PERCENTAGE}} VAT.

                                                </p>

                                                @if($subAmount == 0)



                                                @else

                                                    <p>

                                                        The following parts were not replaced:

                                                    </p>

                                                    @if(count($parts))

                                                        <ul>

                                                            @foreach($parts as $part)

                                                                <li><strong>{{ $part->part->name }}</strong>
                                                                    : {{ number_format($part->total) }}</li>

                                                            @endforeach


                                                        </ul>



                                                    @endif



                                                @endif

                                                @if($labor != 0)

                                                    <p><strong>Labour due</strong>: {{ number_format($labor) }}</p>

                                                @endif

                                                @if($subAmount != 0)

                                                    <p>

                                                        Amount due to customer for Cash In Lieu is
                                                        Kshs. {{ number_format($subAmount) }} less markup and VAT.

                                                    </p>

                                                @endif

                                                <p>

                                                    Re-inspection done on {{ date('l jS F Y', strtotime($day)) }}.

                                                </p>

                                                <p>

                                                    {{ $assessor }}, <br>

                                                    Claims Department, <br>

                                                    Jubilee Insurance Company.

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
            </div>
        </div>
    </div>
</div>
@include('_partials.settings')
@include('_partials.footer')
