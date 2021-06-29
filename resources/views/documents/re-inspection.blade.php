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
                                <div class="col s4"></div>
                                <div class="col s4">
                                    <div class="center-align">
                                        <h4 class="card-title float-left">JUBILEE ALLIANZ INSURANCE ASSESSORS REPORT</h4>
                                        <h5 class="card-title float-left">MOTOR RE-INSPECTION REPORT</h5>
                                    </div>
                                </div>
                                <div class="col s4"></div>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <div class="table-responsive">
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

                                                <td>{{ $vehicle_reg }}</td>

                                                <td>{{ date('l jS F Y', strtotime($day)) }}</td>

                                            </tr>


                                            </tbody>

                                        </table>
                                    </div>
                                    <div class="col s12">

                                        <p>

                                            We refer to our earlier assessment report on the above motor vehicle dated

                                            {{ date('l jS F Y', strtotime($surveyDate)) }}.

                                        </p>

                                        <p>

                                            We are now pleased to confirm that the repairs have since been completed
                                            satisfactorily and in good time.

                                        </p>

                                        @if($addLabor != 0)

                                            <p>Additional labor to garage is KShs.

                                                <strong>{{ number_format($addLabor * 1.16) }}.</strong>

                                                (<strong>NB</strong>: This has already been reflected in the total
                                                amount)

                                            </p>

                                        @endif

                                        <p>

                                            The garage to invoice Kshs. {{ number_format($amount) }} Inclusive 16% VAT.

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


                                    </div>
                                    <div class="col-md-12">

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
</div>
