<div class="row">

    <div
        class="content-wrapper-before  gradient-45deg-red-pink">
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row col s12">
                        <h4 class="card-title float-left">Re-inspection for
                            - {{$assessments->claim->claimNo}} </h4>
                    </div>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col s12">
                            <ul class="stepper parallel horizontal">
                                <li class="step active">
                                    <div class="step-title waves-effect waves-dark">Re-inspection Report</div>
                                    <div class="step-content">
                                        <div class="row">
                                            <div class="input-field col m1 s12">

                                            </div>
                                            <div class="input-field col m4 s12">
                                                <input placeholder="" id="lessLabour" type="text" name="lessLabour"
                                                       value=""/>
                                                <label for="lessLabour">Less Labour</label>
                                            </div>
                                            <div class="input-field col m1 s12">

                                            </div>
                                            <div class="input-field col m4 s12">
                                                <input placeholder="" id="additionalLabour" type="text" name="additionalLabour" value=""/>
                                                <label for="additionalLabour">Additional labour to garage</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col m12 s12">
                                                <p>Notes</p>
                                                <textarea id="notes" class="materialize-textarea notes"
                                                          name="notes"></textarea>
                                                <script>
                                                    CKEDITOR.replace('notes', {
                                                        language: 'en',
                                                        uiColor: ''
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                        <div class="step-actions">
                                            <button class="waves-effect waves-dark btn next-step"
                                                    data-validator="validateStepOne">CONTINUE
                                            </button>
                                            <button class="waves-effect waves-dark btn-flat previous-step">
                                                BACK
                                            </button>
                                        </div>
                                    </div>
                                </li>
                                <li class="step">
                                    <div class="step-title waves-effect waves-dark">Assembly</div>
                                    <div class="step-content">
                                        <table class="centered responsive-table">
                                            <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Vehicle</th>
                                                <th>Quantity</th>
                                                <th>Part Price</th>
                                                <th>Contribution</th>
                                                <th>Total</th>
                                                <th>Remarks</th>
                                                <th>Done</th>
                                                <th>Repair/Replace</th>
                                                <th>CIL</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $count =0;
                                            ?>
                                            @foreach($assessmentItems as $assessmentItem)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td><input id="vehiclePart_{{$count}}" placeholder="" type="text" name="vehiclePart[]"
                                                               value="{{$assessmentItem->part->name}}" disabled/>
                                                    </td>
                                                    <td>
                                                        <input id="quantity_{{$count}}" placeholder="" type="text" name="quantity[]"
                                                               value="{{$assessmentItem->quantity}}" disabled/>
                                                    </td>
                                                    <td>
                                                        <input id="partPrice_{{$count}}" placeholder="" type="text" name="partPrice[]"
                                                               value="{{$assessmentItem->cost}}" disabled/>
                                                    </td>
                                                    <td>
                                                        <input id="contribution_{{$count}}" placeholder="" type="text"
                                                               name="contribution[]" value="{{!empty($assessmentItem->contribution) ? $assessmentItem->contribution : 0}}" disabled/>
                                                    </td>
                                                    <td>
                                                        <input id="total_{{$count}}" placeholder="" type="text" name="total[]" value="{{$assessmentItem->total}}" class="total"
                                                               disabled/>
                                                    </td>
                                                    <td>
                                                        <input id="remarks_{{$count}}" placeholder="" type="text" name="remarks[]" value="{{$assessmentItem->remark->name}}" class="total"
                                                               disabled/>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="re_inspection[]" id="re_inspection_{{$count}}" value="{{$assessmentItem->segment}}" @if($assessmentItem->segment == \App\Conf\Config::$ASSESSMENT_SEGMENTS["INSPECTION"]["ID"]) checked @endif/>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="repaired[]" id="repaired_{{$count}}" value="{{$assessmentItem->category}}" @if($assessmentItem->category == \App\Conf\Config::$JOB_CATEGORIES["REPAIR"]["ID"]) checked @endif/>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="cil[]" id="cil_{{$count}}" value="{{$assessmentItem->category}}" @if($assessmentItem->category == \App\Conf\Config::$JOB_CATEGORIES["CIL"]["ID"]) checked @endif/>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="step-actions">
                                                <button class="waves-effect waves-dark btn next-step">CONTINUE</button>
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
                                                <form action="#" enctype="multipart/form-data" data-allowed-file-extensions='["jpeg", "jpg", "png"]' id="assessmentForm">
                                                    <div class="input-images" id="images"></div>
                                                </form>
                                                <small>Only <span
                                                        class="red-text text-darken-3">JPEG,JPG & PNG</span> files
                                                    are allowed</small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col m6">
                                                <div class="input-field">
                                                    <label>
                                                        <input type="checkbox" id="isDraft" value="0"/>
                                                        <span>Save as Draft</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="step-actions">
                                            <input type="hidden" name="counter" id="counter">
                                            <input type="hidden" name="assessmentID" id="assessmentID" value="{{$assessments->id}}">
                                            <input type="submit" class="waves-effect waves-dark btn next-step"
                                                   value="SUBMIT" id="submitReinspection"/>
                                            <button class="waves-effect waves-dark btn-flat previous-step">BACK
                                            </button>
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
<script type="text/javascript">

</script>
