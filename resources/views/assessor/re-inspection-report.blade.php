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
                                                       value="{{isset($inspections->labor) ? $inspections->labor : ''}}"/>
                                                <label for="lessLabour" @if(isset($inspections->labor)) class="active" @endif>Less Labour</label>
                                            </div>
                                            <div class="input-field col m1 s12">

                                            </div>
                                            <div class="input-field col m4 s12">
                                                <input placeholder="" id="additionalLabour" type="text" name="additionalLabour" value="{{isset($inspections->addLabor) ? $inspections->addLabor : ''}}"/>
                                                <label for="additionalLabour" @if(isset($inspections->addLabor)) class="active" @endif>Additional labour to garage</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col m12 s12">
                                                <p>Notes</p>
                                                <textarea id="notes" class="materialize-textarea notes"
                                                          name="notes">
                                                    {{isset($inspections->notes) ? $inspections->notes : ''}}
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
                                        <table class="centered responsive-table" id="reinspectionTable">
                                            <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Vehicle</th>
                                                <th>Quantity</th>
                                                <th>Part Price</th>
                                                <th>Contribution</th>
                                                <th>Total</th>
                                                <th>Remarks</th>
                                                <th>Done/Replace</th>
                                                <th>Repair</th>
                                                <th>CIL</th>
                                                <th>Re-Used</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $count =0;
                                            ?>
                                            @foreach($assessmentItems as $assessmentItem)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>
                                                        <input type="hidden" id="assessmentItemID_{{$count}}" value="{{$assessmentItem->id}}">
                                                        <input id="vehiclePart_{{$count}}" placeholder="" type="text" name="vehiclePart[]"
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
                                                            <input type="checkbox" name="replacePart[]" value="{{$assessmentItem->id}}" @if($assessmentItem->reInspection) == \App\Conf\Config::ACTIVE) @if($assessmentItem->reInspectionType == \App\Conf\Config::$JOB_CATEGORIES['REPLACE']["ID"]) checked @endif @endif/>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="repaired[]" value="{{$assessmentItem->id}}" @if($assessmentItem->reInspection == \App\Conf\Config::ACTIVE) @if($assessmentItem->reInspectionType == \App\Conf\Config::$JOB_CATEGORIES["REPAIR"]["ID"]) checked @endif @endif/>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="cil[]" value="{{$assessmentItem->id}}" @if($assessmentItem->reInspection == \App\Conf\Config::ACTIVE) @if($assessmentItem->reInspectionType == \App\Conf\Config::$JOB_CATEGORIES["CIL"]["ID"]) checked @endif @endif/>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="reused[]" value="{{$assessmentItem->id}}" @if($assessmentItem->reInspection == \App\Conf\Config::ACTIVE) @if($assessmentItem->reInspectionType == \App\Conf\Config::$JOB_CATEGORIES["REUSE"]["ID"]) checked @endif @endif/>
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <?php $count ++; ?>
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
                                        <div class="step-actions">
                                            <input type="hidden" name="counter" id="counter" value="{{$count}}">
                                            <input type="hidden" name="assessmentID" id="assessmentID" value="{{$assessments->id}}">
                                            <input type="hidden" name="reInspected" id="reInspected" value="{{$assessments->id}}">
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
@if(isset($inspections->id))
    <?php
    $images = \App\Document::where(["inspectionID"=>$inspections->id])->get();
    ?>
    <input type="hidden" value="{{$images}}" name="imagesArray" id="imagesArray">
    <input type="hidden" value="{{count($images)}}" id="imagesCount">
@endif
<script type="text/javascript">

</script>
