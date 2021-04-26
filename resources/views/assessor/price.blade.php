<div class="row">

    <div class="content-wrapper-before  gradient-45deg-red-pink">
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row col s12">
                        <h4 class="card-title float-left">Price chnage for claim Number
                            - {{$assessment->claim->claimNo}} </h4>
                    </div>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col s12">
                            <ul class="stepper parallel horizontal">
                                <li class="step active">
                                    <div class="step-title waves-effect waves-dark">Assessment Report</div>
                                    <div class="step-content">
                                        <div class="row">
                                            <div class="input-field col m3 s12">
                                                <input type="hidden" value="{{isset($draftAssessment->id) ? 1 : 0}}"
                                                       id="drafted" name="drafted">
                                                <input placeholder="" id="chassisNumber" type="text"
                                                       name="chassisNumber"
                                                       value="{{$assessment->claim->chassisNumber}}" />
                                                <label for="chassisNumber" class="active">Chassis Number</label>
                                            </div>
                                            <div class="input-field col m2 s12">
                                                <input placeholder="" id="carMake" type="text" name="carMake"
                                                       value="{{$carDetails->makeName}}" disabled />
                                                <label for="carMake" class="active">Car Make</label>
                                            </div>
                                            <div class="input-field col m2 s12">
                                                <input placeholder="" id="carModel" type="text" name="carModel"
                                                       value="{{$carDetails->modelName}}" disabled />
                                                <label for="carModel" class="active">Car Model</label>
                                            </div>
                                            <div class="input-field col m2 s12">
                                                <input placeholder="" id="YOM" type="text" name="YOM"
                                                       value="{{$assessment->claim->yom}}" disabled />
                                                <label for="YOM" class="active">YOM</label>
                                            </div>
                                            <div class="input-field col m3 s12">
                                                <input placeholder="" id="PAV" type="text" name="PAV"
                                                       value="{{isset($draftAssessment->pav) ? $draftAssessment->pav : null }}"
                                                       required />
                                                <label for="PAV" class="active">PAV <span
                                                        style="color: red">*</span></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col m6 s12">
                                                <p>Notes</p>
                                                <textarea id="notes" class="materialize-textarea notes" name="notes">
                                                    {{isset($draftAssessment->note) ? $draftAssessment->note : null}}
                                                </textarea>
                                                <script>
                                                    CKEDITOR.replace('notes', {
                                                        language: 'en',
                                                        uiColor: ''
                                                    });
                                                </script>
                                            </div>
                                            <div class="col m6 s12">
                                                <p>Cause & Nature of Accident</p>
                                                <textarea id="cause" class="materialize-textarea cause" name="cause">
                                                    {{isset($draftAssessment->cause) ? $draftAssessment->cause : null}}
                                                </textarea>
                                                <script>
                                                    CKEDITOR.replace('cause', {
                                                        language: 'en',
                                                        uiColor: ''
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                        <div class="step-actions">
                                            <button id="validateStepOne" class="waves-effect waves-dark btn next-step"
                                                    data-validator="validateStepOne">CONTINUE
                                            </button>
                                            <button class="waves-effect waves-dark btn-flat previous-step">
                                                BACK
                                            </button>
                                        </div>
                                    </div>
                                </li>
                                <li class="step">
                                    <div class="step-title waves-effect waves-dark">Replace Assembly</div>
                                    <div class="step-content">
                                        <table class="centered">
                                            <thead>
                                            <tr>

                                                <th>Vehicle Part</th>
                                                <th>Quantity</th>
                                                <th>Initial Price</th>
                                                <th>Current Price</th>
                                                <th>Difference</th>
                                                <th>Remarks</th>
                                                <th>Category</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($assessmentItems)>0)
                                                <?php
                                                $count = 0;
                                                ?>
                                                @foreach($assessmentItems as $assessmentItem)
                                                    <tr class="dynamicVehiclePart">
                                                        <td class="checks">
                                                            <input class="center-align vehiclePart_{{$count}}" disabled type="text" id="{{$assessmentItem->id}} " name="vehiclePart[]" value="{{ $assessmentItem->part->name }}">
                                                        </td>
                                                        <td>
                                                            <input disabled class="center-align" id="quantity_{{$count}}"
                                                                   placeholder="" type="text" name="[]"
                                                                   value="{{$assessmentItem->quantity}}" />
                                                        </td>
                                                        <td>
                                                            <input disabled class="center-align" id="partPrice_{{$count}}"
                                                                   placeholder="" type="text" name="partPrice[]"
                                                                   value="{{$assessmentItem->cost}}" />
                                                        </td>
                                                        <td>
                                                            <input  class="center-align current" id="current_{{$count}}" placeholder="" type="text"
                                                                    name="current[]" value="{{$assessmentItem->current!=0?$assessmentItem->current:''}}" />
                                                        </td>
                                                        <td>
                                                            <input disabled class="center-align" id="difference_{{$count}}"
                                                                   placeholder="" type="text" name="discount[]"  value="{{$assessmentItem->difference!=0?$assessmentItem->difference:''}}"
                                                            />
                                                        </td>
                                                        <td>
                                                            <input class="center-align" disabled type="text" id="remarks_{{$count}}" name="remarks[]" value="{{ $assessmentItem->remark->name }}">
                                                        </td>
                                                        <td>
                                                            @foreach(\App\Conf\Config::$JOB_CATEGORIES as $category)
                                                                @if($assessmentItem->category == $category['ID'])
                                                                    <input class="center-align" disabled type="text" id="category_{{$count}}" name="category[]" value= "{{$category['TITLE']}}">
                                                                @endif
                                                            @endforeach

                                                        </td>


                                                    </tr>
                                                    <?php
                                                    $count ++;
                                                    ?>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                        <div id="addP"></div>


                                        <div class="row">
                                            <div class="step-actions">
                                                <button id="validateStepTwo"
                                                        class="waves-effect waves-dark btn next-step">CONTINUE</button>
                                                <button class="waves-effect waves-dark btn-flat previous-step">BACK
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="step">
                                    <div class="step-title waves-effect waves-dark">Upload Image</div>
                                    <div class="step-content">
                                        <div class="row">
                                            <div class="col s12">
                                                <form action="#" enctype="multipart/form-data" data-allowed-file-extensions='["jpeg", "jpg", "png"]' id="assessmentForm">
                                                    <div class="input-images" id="images"></div>
                                                </form>
                                                <small>Only <span
                                                        class="red-text text-darken-3">JPEG,JPG & PNG</span> files
                                                    are allowed</small>
                                            </div>
                                        </div>
                                        {{--                                        <div class="row">--}}
                                        {{--                                            <div class="col m6">--}}
                                        {{--                                                <div class="input-field">--}}
                                        {{--                                                    <label>--}}
                                        {{--                                                        <input  type="checkbox" id="isDraft" value="0"/>--}}
                                        {{--                                                        <span>Save as Draft</span>--}}
                                        {{--                                                    </label>--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                        <br/>
                                        <div class="row">
                                            <div class="col m6">
                                            </div>
                                            <div class="step-actions">
                                                <input type="hidden" name="counter" id="counter" value="{{isset($count) ? $count-1 : 0}}">
                                                <input type="hidden" name="assessmentID" id="assessmentID" value="{{$assessment->id}}">
                                                <input type="submit" class="waves-effect waves-dark btn next-step"
                                                       value="SUBMIT" id="submit-price-change"/>
                                                <button class="waves-effect waves-dark btn-flat previous-step">BACK
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(isset($draftAssessment->id))
    <?php
    $images = \App\Document::where(["assessmentID"=>$draftAssessment->id])->get();
    ?>
    <input type="hidden" value="{{$images}}" name="imagesArray" id="imagesArray">
@endif
<script type="text/javascript">
    var t =0;
    $(document).ready(function() {

        $("#vehiclePart_"+t).select2({dropdownAutoWidth : true, width: '160px'});
        var partsCounter = $("#counter").val()+1;
        for(var i=1;i<partsCounter;i++)
        {
            $("#vehiclePart_"+i).select2({dropdownAutoWidth : true, width: '160px'});
        }

        $("#vehiclePart_"+t).select2({dropdownAutoWidth : true, width: '160px'});

        $('body').on('click','#addPart',function(){
            $("#vehiclePart_"+t).select2({dropdownAutoWidth : true, width: '160px'});
        });
    });
    $("#dynamicPartsSelector").on('click','#addPart',function (){
        var drafted = $("#drafted").val();
        if(drafted == 1)
        {
            t = $("#counter").val();
            t++;
        }else
        {
            t = t + 1;
        }
        $("#counter").val(t);

        $('#addP').append('<tr class="row dynamicVehiclePart removable" id="row'+t+'">\n' +
            '
        '                                                        <td>\n' +
        '                                                            <select id="vehiclePart_'+t+'" name="vehiclePart[]" class="browser-default">\n' +
        '                                                                @foreach($parts as $part)\n' +
        '                                                                    <option value="{{$part->id}}">{{$part->name}}</option>\n' +
        '                                                                @endforeach\n' +
        '                                                            </select>\n' +
        '                                                        </td>\n' +
        '                                                        <td>\n' +
        '                                                            <input id="quantity_'+t+'" oninput="getTotal('+t+')" placeholder="quantity" type="text" name="quantity[]"\n' +
        '                                                                   value="" maxlength="3"/>\n' +
        '                                                        </td>\n' +
        '                                                        <td>\n' +
        '                                                            <input id="partPrice_'+t+'" oninput="getTotal('+t+')" placeholder="part price" type="text" name="partPrice[]"\n' +
        '                                                                   value=""/>\n' +
        '                                                        </td>\n' +
        '                                                        <td>\n' +
        '                                                            <input id="contribution_'+t+'" placeholder="contribution" type="text"\n' +
        '                                                                   name="contribution[]" oninput="getTotal('+t+')" value="" maxlength="2"/>\n' +
        '                                                        </td>\n' +
        '                                                        <td>\n' +
        '                                                            <input id="discount_'+t+'" oninput="getTotal('+t+')" placeholder="discount" type="text" name="discount[]"\n' +
        '                                                                   value="" maxlength="2"/>\n' +
        '                                                        </td>\n' +
        '                                                        <td>\n' +
        '                                                            <input id="total_'+t+'" placeholder="" type="text" name="total[]" value="" class="total"\n' +
        '                                                                   disabled/>\n' +
        '                                                        </td>\n' +
        '                                                        <td>\n' +
        '                                                            <select id="remarks_'+t+'" name="remarks[]" class="browser-default">\n' +
        '                                                                @foreach($remarks as $remark)\n' +
        '                                                                    <option value="{{$remark->id}}">{{$remark->name}}</option>\n' +
        '                                                                @endforeach\n' +
        '                                                            </select>\n' +
        '                                                        </td>\n' +
        '                                                        <td>\n' +
        '                                                            <select id="category_'+t+'" name="category[]" class="browser-default">\n' +
        '                                                                @foreach(\App\Conf\Config::$JOB_CATEGORIES as $category)\n' +
        '                                                                    <option value="{{$category['ID']}}">{{$category['TITLE']}}</option>\n' +
        '                                                                @endforeach\n' +
        '                                                            </select>\n' +
        '                                                        </td>\n' +
        '                                                    </tr>');
        $("#vehiclePart_"+t).select2({dropdownAutoWidth : true, width: '160px'});
    });
    function getTotal(t) {

        var quantity = "quantity_" + t;

        var quantity = document.getElementById(quantity).value;

        var total = document.getElementById('total_'+t);

        var cost = document.getElementById('partPrice_'+t).value;

        var contribution = document.getElementById('contribution_'+t).value;

        var discount = document.getElementById('discount_'+t).value;

        var result = (quantity * cost);

        if (contribution > 0 && discount < 1) {

            result = ((((100 - contribution)/100) * (quantity * cost)));

        }

        if (discount > 0 && contribution < 1) {

            result = ((((100 - discount)/100) * (quantity * cost)));

        }

        if (discount > 0 && contribution > 0) {

            result = ((100 - contribution)/100) * ((((100 - discount)/100) * (quantity * cost)));

        }



        total.value = result;

    }

    function getPriceChange(i) {

        var price = document.getElementById('quantity_'+i).value;

        var initial = document.getElementById('partPrice_'+i).value;

        var current = document.getElementById('current_'+i).value;

        var depreciation = document.getElementById('difference_'+i).value;

        var result = (parseInt(current) - parseInt(initial));

        if (depreciation > 0 && depreciation != '') {

            current = ((100 - depreciation)/100) * current;

            result = (parseInt(current) - parseInt(initial));

        }

        price.value = Math.round(result);

    }

    function findTotal() {

        var arr = document.getElementsByClassName('total');

        var labour = document.getElementById('labour').value;

        var paint = document.getElementById('painting').value;

        var miscellaneous = document.getElementById('miscellaneous').value;

        var primer = document.getElementById('2kprimer').value;

        var jigging = document.getElementById('jigging').value;

        var reconstruction = document.getElementById('reconstruction').value;

        var gas = document.getElementById('acgas').value;

        var welding = document.getElementById('weldinggas').value;

        var assessmentType = document.querySelector('input[name="assessmentType"]:checked').value;

        var total = 0;

        for(var i = 0; i < arr.length; i++){

            if(parseInt(arr[i].value))

                total += parseInt(arr[i].value);

        }



        var result = parseFloat( "0" + labour ) + parseFloat( "0" + paint ) + parseFloat( "0" + miscellaneous ) + parseFloat( "0" + primer )

            + parseFloat( "0" + jigging ) + parseFloat( "0" + reconstruction ) + parseFloat( "0" + gas ) + parseFloat( "0" + welding )

            + parseFloat( "0" + total );



        var cil = parseFloat( "0" + labour ) + parseFloat( "0" + paint ) + parseFloat( "0" + miscellaneous ) + parseFloat( "0" + primer )

            + parseFloat( "0" + jigging ) + parseFloat( "0" + reconstruction ) + parseFloat( "0" + gas ) + parseFloat( "0" + welding );

        if("{{$assessment->claim->intimationDate}}" >= "{{\App\Conf\Config::VAT_REDUCTION_DATE}}" && "{{$assessment->claim->intimationDate}}" <= "{{\App\Conf\Config::VAT_END_DATE}}")
        {
            var tax = "{{\App\Conf\Config::CURRENT_TOTAL_PERCENTAGE}}"/"{{App\Conf\Config::INITIAL_PERCENTAGE}}";
        }else
        {
            var tax = "{{\App\Conf\Config::TOTAL_PERCENTAGE}}"/"{{\App\Conf\Config::INITIAL_PERCENTAGE}}";
        }

        if(assessmentType == 1) {

            result = result * tax;

        } else if(assessmentType == 2) {

            result = (parseFloat( "0" + cil ) + parseFloat( "0" + total )) * 0.9;

        } else if(assessmentType == 3) {

            result = result * tax;

        }
        document.getElementById('sumTotals').value = Math.round(result);
        document.getElementById('sumTotal').value = Math.round(result);

    }


    function findTotalLoss() {

        var pav = document.getElementById('pav').value;

        var salvage = document.getElementById('salvage').value;



        var totalLoss = parseFloat( "0" + pav ) - parseFloat( "0" + salvage );



        document.getElementById('total_loss').value = totalLoss

    }






</script>
