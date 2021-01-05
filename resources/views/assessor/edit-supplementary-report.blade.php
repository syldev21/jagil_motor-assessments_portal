<div class="row">

    <div class="content-wrapper-before  gradient-45deg-red-pink">
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row col s12">
                        <h4 class="card-title float-left">Assessment for claim Number
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
                                            <div class="col m12 s12">
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
                                                <th>Remove</th>
                                                <th>Vehicle Part</th>
                                                <th>Quantity</th>
                                                <th>Part Price</th>
                                                <th>Contribution</th>
                                                <th>Discount</th>
                                                <th>Total</th>
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
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <select id="vehiclePart_{{$count}}" name="vehiclePart[]"
                                                                    class="browser-default">
                                                                @foreach($parts as $part)
                                                                    <option value="{{$part->id}}" @if($assessmentItem->part->id
                                                                == $part->id) selected @endif>{{$part->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input id="quantity_{{$count}}" oninput="getTotal(0)"
                                                                   placeholder="" type="text" name="quantity[]"
                                                                   value="{{$assessmentItem->quantity}}" />
                                                        </td>
                                                        <td>
                                                            <input id="partPrice_{{$count}}" oninput="getTotal(0)"
                                                                   placeholder="" type="text" name="partPrice[]"
                                                                   value="{{$assessmentItem->cost}}" />
                                                        </td>
                                                        <td>
                                                            <input id="contribution_{{$count}}" placeholder="" type="text"
                                                                   name="contribution[]" oninput="getTotal(0)"
                                                                   value="{{!empty($assessmentItem->contribution) ? $assessmentItem->contribution : 0}}" />
                                                        </td>
                                                        <td>
                                                            <input id="discount_{{$count}}" oninput="getTotal(0)"
                                                                   placeholder="" type="text" name="discount[]"
                                                                   value="{{!empty($assessmentItem->discount) ? $assessmentItem->discount : 0}}" />
                                                        </td>
                                                        <td>
                                                            <input id="total_{{$count}}" placeholder="" type="text"
                                                                   name="total[]" value="{{$assessmentItem->total}}"
                                                                   class="total" disabled />
                                                        </td>
                                                        <td>
                                                            <select id="remarks_{{$count}}" name="remarks[]"
                                                                    class="browser-default">
                                                                @foreach($remarks as $remark)
                                                                    <option value="{{$remark->id}}" @if($assessmentItem->
                                                                remark->id == $remark->id) selected
                                                                        @endif>{{$remark->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select id="category_{{$count}}" name="category[]"
                                                                    class="browser-default">
                                                                @foreach(\App\Conf\Config::$JOB_CATEGORIES as $category)
                                                                    <option value="{{$category['ID']}}" @if($assessmentItem->
                                                                category == $category['ID']) selected
                                                                        @endif>{{$category['TITLE']}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $count ++;
                                                    ?>
                                                @endforeach
                                            @else
                                                <tr class="dynamicVehiclePart">
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" />
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <select id="vehiclePart_0" name="vehiclePart[]"
                                                                class="browser-default">
                                                            @foreach($parts as $part)
                                                                <option value="{{$part->id}}">{{$part->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input id="quantity_0" oninput="getTotal(0)" placeholder=""
                                                               type="text" name="quantity[]" value="" maxlength="3" />
                                                    </td>
                                                    <td>
                                                        <input id="partPrice_0" oninput="getTotal(0)" placeholder=""
                                                               type="text" name="partPrice[]" value="" />
                                                    </td>
                                                    <td>
                                                        <input id="contribution_0" placeholder="" type="text"
                                                               name="contribution[]" oninput="getTotal(0)" value=""
                                                               maxlength="2" />
                                                    </td>
                                                    <td>
                                                        <input id="discount_0" oninput="getTotal(0)" placeholder=""
                                                               type="text" name="discount[]" value="" maxlength="2" />
                                                    </td>
                                                    <td>
                                                        <input id="total_0" placeholder="" type="text" name="total[]"
                                                               value="" class="total" disabled />
                                                    </td>
                                                    <td>
                                                        <select id="remarks_0" name="remarks[]" class="browser-default">
                                                            @foreach($remarks as $remark)
                                                                <option value="{{$remark->id}}">{{$remark->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select id="category_0" name="category[]"
                                                                class="browser-default">
                                                            @foreach(\App\Conf\Config::$JOB_CATEGORIES as $category)
                                                                <option value="{{$category['ID']}}">{{$category['TITLE']}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                        <div id="addP"></div>
                                        <div class="row">
                                            <div class="col s12" id="dynamicPartsSelector">
                                                <a href="#" class="btn blue lighten-2" id="addPart">Add Part <i
                                                        class="medium material-icons">add</i></a>
                                                <a href="#" class="btn red darken-4" onclick="deletePart()">Remove <i
                                                        class="medium material-icons">remove</i></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col m5">
                                                <table class="table table-borderless" id="partsTable">
                                                    <tr>
                                                        <td>Labour:</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="labour" type="text"
                                                                       name="labour"
                                                                       value="{{ isset($jobDraftDetail['Labour']) ? $jobDraftDetail['Labour'] : null }}"
                                                                       class="border-fields" oninput="findTotal()" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Painting:</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="painting" type="text"
                                                                       name="painting"
                                                                       value="{{ isset($jobDraftDetail['Painting']) ? $jobDraftDetail['Painting'] : null }}"
                                                                       class="border-fields" oninput="findTotal()" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Miscellaneous:</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="miscellaneous" type="text"
                                                                       name="miscellaneous"
                                                                       value="{{ isset($jobDraftDetail['Miscellaneous']) ? $jobDraftDetail['Miscellaneous'] : null }}"
                                                                       class="border-fields" oninput="findTotal()" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2K Primer :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="2kprimer" type="text"
                                                                       name="2kprimer"
                                                                       value="{{ isset($jobDraftDetail['2k Primer']) ? $jobDraftDetail['2k Primer'] : null }}"
                                                                       class="border-fields" oninput="findTotal()" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jigging :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="jigging" type="text"
                                                                       name="jigging"
                                                                       value="{{ isset($jobDraftDetail['Jigging']) ? $jobDraftDetail['Jigging'] : null }}"
                                                                       class="border-fields" oninput="findTotal()" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Body Repair (Reconstruction) :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="reconstruction" type="text"
                                                                       name="reconstruction"
                                                                       value="{{ isset($jobDraftDetail['Reconstruction']) ? $jobDraftDetail['Reconstruction'] : null }}"
                                                                       class="border-fields" oninput="findTotal()" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>A/C Gas :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="acgas" type="text"
                                                                       name="acgas"
                                                                       value="{{ isset($jobDraftDetail['AC/Gas']) ? $jobDraftDetail['AC/Gas'] : null }}"
                                                                       class="border-fields" oninput="findTotal()" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Welding / Gas :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="weldinggas" type="text"
                                                                       name="weldinggas"
                                                                       value="{{ isset($jobDraftDetail['Welding/Gas']) ? $jobDraftDetail['Welding/Gas'] : null }}"
                                                                       class="border-fields" oninput="findTotal()" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col m7">
                                                <div class="row">
                                                    <div class="col m4">
                                                        <label>
                                                            <input name="assessmentType" type="radio"
                                                                   onclick="findTotal()" class="with-gap assessmentType"
                                                                   value="1" @if(isset($draftAssessment->assessmentTypeID))
                                                                   @if($draftAssessment->assessmentTypeID ==
                                                                   \App\Conf\Config::ASSESSMENT_TYPES["AUTHORITY_TO_GARAGE"])
                                                                   checked @endif
                                                                   @else
                                                                   checked
                                                                @endif
                                                            />
                                                            <span>Authority To Garage</span>
                                                        </label>
                                                    </div>
                                                    <div class="col m4">
                                                        <label>
                                                            <input name="assessmentType" type="radio"
                                                                   onclick="findTotal()" class="with-gap assessmentType"
                                                                   value="2" @if(isset($draftAssessment->assessmentTypeID))
                                                                   @if($draftAssessment->assessmentTypeID ==
                                                                   \App\Conf\Config::ASSESSMENT_TYPES["CASH_IN_LIEU"]) checked
                                                                @endif
                                                                @endif
                                                            />
                                                            <span>Cash In Lieu</span>
                                                        </label>
                                                    </div>
                                                    <div class="col m4">
                                                        <label>
                                                            <input name="assessmentType" type="radio"
                                                                   onclick="findTotal()" class="with-gap assessmentType"
                                                                   value="3" @if(isset($draftAssessment->assessmentTypeID))
                                                                   @if($draftAssessment->assessmentTypeID ==
                                                                   \App\Conf\Config::ASSESSMENT_TYPES["TOTAL_LOSS"]) checked
                                                                @endif
                                                                @endif
                                                            />
                                                            <span>Total loss</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col m1"></div>
                                                    <div class="col m10">
                                                        <div id="authorityToGarage">
                                                            <h6 class="float-left">Total:</h6>
                                                            <div class="input-field float-right">
                                                                <input type="text"
                                                                       value="{{isset($draftAssessment->totalCost)  ? $draftAssessment->totalCost : null}}"
                                                                       name="sumTotal" id="sumTotal"
                                                                       class="border-fields" />
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if(isset($draftAssessment->assessmentTypeID) && $draftAssessment->assessmentTypeID == \App\Conf\Config::ASSESSMENT_TYPES["TOTAL_LOSS"])
                                                            $toggoleTotalLoss= " ";
                                                        else
                                                            $toggoleTotalLoss= "hideTotalLose";
                                                        ?>
                                                        <div class="totalLose card clearfix {{$toggoleTotalLoss}}">
                                                            <div class="card-content">
                                                                <h6>Economics of Repair Vis a Vis Total Loss</h6>
                                                                <table id="totallosetable">
                                                                    <tr>
                                                                        <td>Sum Insured :</td>
                                                                        <td>
                                                                            <div class="input-field">
                                                                                <input placeholder="" id="sumInsured"
                                                                                       type="text" name="sumInsured"
                                                                                       value="{{number_format($assessment->claim->sumInsured)}}"
                                                                                       class="border-fields" />
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PAV :</td>
                                                                        <td>
                                                                            <div class="input-field">
                                                                                <input placeholder="" id="pav"
                                                                                       type="text" name="pav"
                                                                                       value="{{isset($draftAssessment->pav) ? $draftAssessment->pav : null }}"
                                                                                       class="border-fields"
                                                                                       oninput="findTotalLoss()" />
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Salvage :</td>
                                                                        <td>
                                                                            <div class="input-field">
                                                                                <input placeholder="" id="salvage"
                                                                                       type="text" name="salvage"
                                                                                       value="{{isset($draftAssessment->salvage) ? $draftAssessment->salvage : null}}"
                                                                                       class="border-fields"
                                                                                       oninput="findTotalLoss()" />
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Total Loss :</td>
                                                                        <td>
                                                                            <div class="input-field">
                                                                                <input placeholder="" id="total_loss"
                                                                                       type="text" name="total_loss"
                                                                                       value="{{isset($draftAssessment->totalLoss) ? $draftAssessment->totalLoss : null}}"
                                                                                       class="border-fields total_loss" />
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Repair :</td>
                                                                        <td>
                                                                            <div class="input-field">
                                                                                <input placeholder="" id="repair"
                                                                                       type="text" name="repair"
                                                                                       value="{{ number_format(($assessment->claim->sumInsured) * 0.5)}}"
                                                                                       class="border-fields" />
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <div class="row">
                                                                    <div class="col m3"></div>
                                                                    <div class="col m9">
                                                                        <h6>Total</h6>
                                                                        <div class="input-field">
                                                                            <input placeholder="" id="sumTotals"
                                                                                   type="text" name="sumTotals"
                                                                                   value="{{isset($draftAssessment->totalCost) ? $draftAssessment->totalCost : null}}"
                                                                                   class="border-fields" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                    <div class="step-title waves-effect waves-dark">Upload Images</div>
                                    <div class="step-content">
                                        <div class="row">
                                            <div class="col s12">
                                                <form action="#" enctype="multipart/form-data"
                                                      data-allowed-file-extensions='["jpeg", "jpg", "png"]'
                                                      id="assessmentForm">
                                                    <div class="input-images" id="images"></div>
                                                </form>
                                                <small>Only <span class="red-text text-darken-3">JPEG,JPG & PNG</span>
                                                    files
                                                    are allowed</small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col m6">
                                                <div class="input-field">
                                                    <label>
                                                        <input type="checkbox" id="isDraft" value="0" />
                                                        {{-- <span>Save as Draft</span> --}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col m6">
                                            </div>
                                            <div class="step-actions">
                                                <input type="hidden" name="counter" id="counter"
                                                       value="{{isset($count) ? $count-1 : 0}}">
                                                <input type="hidden" name="assessmentID" id="assessmentID"
                                                       value="{{$assessment->id}}">
                                                <input type="hidden" name="claimID" value="{{$assessment->claim->id}}" id="claimID">
                                                <input type="submit" class="waves-effect waves-dark btn next-step"
                                                       value="SUBMIT" id="submit-edited-supplementary" />
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
            '                                                        <td>\n' +
            '                                                            <label>\n' +
            '                                                                <input type="checkbox"/>\n' +
            '                                                                <span></span>\n' +
            '                                                            </label>\n' +
            '                                                        </td>\n' +
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
