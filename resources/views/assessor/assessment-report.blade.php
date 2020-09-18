<div class="row">

    <div
        class="content-wrapper-before  gradient-45deg-red-pink">
    </div>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row col s12">
                        <h4 class="card-title float-left">Assessment for claim Number
                            - {{$assessments->claim->claimNo}} </h4>
                    </div>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col s12">
                            <ul class="stepper parallel horizontal">
                                <li class="step active">
                                    <div class="step-title waves-effect waves-dark">Assessment Report</div>
                                    <div class="step-content">
                                        <div class="row">
                                            <div class="input-field col m4 s12">
                                                <input placeholder="" id="chassisNo" type="text" name="chassisNo"
                                                       value=""/>
                                                <label for="chassisNo">Chassis Number</label>
                                            </div>
                                            <div class="input-field col m4 s12">
                                                <select>
                                                    @foreach($carModels as $carModel)
                                                        <option value="{{$carModel->id}}">{{$carModel->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="chassisNo">Select Car Model</label>
                                            </div>
                                            <div class="input-field col m2 s12">
                                                <?php
                                                $year = \App\Conf\Config::START_YEAR;
                                                $currentYear = date('Y');
                                                ?>
                                                <select>
                                                @while($year <= $currentYear)
                                                        <option value="{{$year}}" id="YOM">{{$year}}</option>
                                                    {{$year ++ }}
                                                @endwhile
                                                </select>
                                                <label for="YOM">YOM</label>
                                            </div>
                                            <div class="input-field col m2 s12">
                                                <input placeholder="" id="PAV" type="text" name="PAV" value=""/>
                                                <label for="PAV">PAV</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col m6 s12">
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
                                            <div class="col m6 s12">
                                                <p>Cause & Nature of Accident</p>
                                                <textarea id="cause" class="materialize-textarea cause"
                                                          name="cause"></textarea>
                                                <script>
                                                    CKEDITOR.replace('cause', {
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
                                    <div class="step-title waves-effect waves-dark">Replace Assembly</div>
                                    <div class="step-content">
                                        <div class="row dynamicVehiclePart">
                                            <div class="input-field col m1 s12">
                                                <label>
                                                    <input type="checkbox"/>
                                                    <span>Remove</span>
                                                </label>
                                            </div>
                                            <div class="input-field col m2 s12">
                                                <select id="vehiclePart">
                                                    <option value="">147</option>
                                                    <option value="">1324</option>
                                                    <option value="">150</option>
                                                    <option value="">1020</option>
                                                </select>
                                                <label for="vehiclePart">Vehicle Part</label>
                                            </div>
                                            <div class="input-field col m1 s12">
                                                <input placeholder="" id="quantity" type="text" name="quantity"
                                                       value=""/>
                                                <label for="quantity">Quantity</label>
                                            </div>
                                            <div class="input-field col m1 s12">
                                                <input placeholder="" id="partPrice" type="text" name="partPrice"
                                                       value=""/>
                                                <label for="partPrice">Part Price</label>
                                            </div>
                                            <div class="input-field col m1 s12">
                                                <input placeholder="" id="contribution" type="text"
                                                       name="contribution" value=""/>
                                                <label for="contribution">Contribution</label>
                                            </div>
                                            <div class="input-field col m2 s12">
                                                <input placeholder="" id="discount" type="text" name="discount"
                                                       value=""/>
                                                <label for="discount">Discount</label>
                                            </div>
                                            <div class="input-field col m2 s12">
                                                <input placeholder="" id="total" type="text" name="total" value=""
                                                       disabled/>
                                                <label for="total">Total</label>
                                            </div>
                                            <div class="input-field col m2 s12">
                                                <select id="remarks">
                                                    @foreach($remarks as $remark)
                                                        <option value="{{$remark->id}}">{{$remark->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="vehiclePart">Remarks</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                <a href="#" class="btn blue lighten-2" id="addPart" onclick="addMore()">Add Part <i
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
                                                                       name="labour" value=""
                                                                       class="border-fields"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Painting:</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="painting" type="text"
                                                                       name="painting" value=""
                                                                       class="border-fields"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Miscellaneous:</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="miscellaneous" type="text"
                                                                       name="miscellaneous" value=""
                                                                       class="border-fields"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2K Primer :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="2kprimer" type="text"
                                                                       name="2kprimer" value=""
                                                                       class="border-fields"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jigging :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="jigging" type="text"
                                                                       name="jigging" value=""
                                                                       class="border-fields"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Body Repair (Reconstruction) :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="jigging" type="text"
                                                                       name="jigging" value=""
                                                                       class="border-fields"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>A/C Gas :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="acgas" type="text"
                                                                       name="acgas" value="" class="border-fields"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Welding / Gas :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="weldinggas" type="text"
                                                                       name="weldinggas" value=""
                                                                       class="border-fields"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Bumper Fibre :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="bumperfibre" type="text"
                                                                       name="bumperfibre" value=""
                                                                       class="border-fields"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dam Kit :</td>
                                                        <td>
                                                            <div class="input-field">
                                                                <input placeholder="" id="damkit" type="text"
                                                                       name="damkit" value=""
                                                                       class="border-fields"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col m7">
                                                <div class="row">
                                                    <div class="col m4">
                                                        <label>
                                                            <input name="assessmentType" type="radio" checked
                                                                   class="with-gap assessmentType" value="Authority To Garage"/>
                                                            <span>Authority To Garage</span>
                                                        </label>
                                                    </div>
                                                    <div class="col m4">
                                                        <label>
                                                            <input name="assessmentType" type="radio"
                                                                   class="with-gap assessmentType" value="Cash In Lieu"/>
                                                            <span>Cash In Lieu</span>
                                                        </label>
                                                    </div>
                                                    <div class="col m4">
                                                        <label>
                                                            <input name="assessmentType" type="radio"
                                                                   class="with-gap assessmentType" value="Total loss"/>
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
                                                                <input id="total" type="text" name="total" value=""
                                                                       class="border-fields" checked/>
                                                            </div>
                                                        </div>
                                                        <div class="totalLose hideTotalLose card clearfix">
                                                            <div class="card-content">
                                                                <h6>Economics of Repair Vis a Vis Total Loss</h6>
                                                                <table id="totallosetable">
                                                                    <tr>
                                                                        <td>Sum Insured :</td>
                                                                        <td>
                                                                            <div class="input-field">
                                                                                <input placeholder=""
                                                                                       id="sumInsured" type="text"
                                                                                       name="sumInsured" value=""
                                                                                       class="border-fields"/>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PAV :</td>
                                                                        <td>
                                                                            <div class="input-field">
                                                                                <input placeholder="" id="pav"
                                                                                       type="text" name="pav"
                                                                                       value=""
                                                                                       class="border-fields"/>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Salvage :</td>
                                                                        <td>
                                                                            <div class="input-field">
                                                                                <input placeholder="" id="salvage"
                                                                                       type="text" name="salvage"
                                                                                       value=""
                                                                                       class="border-fields"/>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Total Loss :</td>
                                                                        <td>
                                                                            <div class="input-field">
                                                                                <input placeholder="" id="totalLoss"
                                                                                       type="text" name="totalLoss"
                                                                                       value=""
                                                                                       class="border-fields"/>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Repair :</td>
                                                                        <td>
                                                                            <div class="input-field">
                                                                                <input placeholder="" id="repair"
                                                                                       type="text" name="repair"
                                                                                       value=""
                                                                                       class="border-fields"/>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <div class="row">
                                                                    <div class="col m3"></div>
                                                                    <div class="col m9">
                                                                        <h6>Total</h6>
                                                                        <div class="input-field">
                                                                            <input placeholder="" id="grandTotal"
                                                                                   type="text" name="grandTotal"
                                                                                   value="" class="border-fields"/>
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
                                                <button class="waves-effect waves-dark btn next-step">CONTINUE</button>
                                                <button class="waves-effect waves-dark btn-flat previous-step">BACK
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="step">
                                    <div class="step-title waves-effect waves-dark">Parts to be Repaired</div>
                                    <div class="step-content">
                                        <div class="row repairedVehicleParts">
                                            <div class="col m2">
                                                Remove
                                                <div class="input-field">
                                                    <label>
                                                        <input type="checkbox"/>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col m4">
                                                Vehicle Parts
                                                <div class="input-field">
                                                    <select id="vehiclePart">
                                                        <option value="">147</option>
                                                        <option value="">1324</option>
                                                        <option value="">150</option>
                                                        <option value="">1020</option>
                                                        <label for="vehiclePart">Vehicle
                                                            Part</label>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col m2">
                                                Quantity
                                                <div class="input-field">
                                                    <input placeholder="" id="quantity" type="text" name="quantity"
                                                           value="" class="border-fields"/>
                                                </div>
                                            </div>
                                            <div class="col m4">
                                                Remarks
                                                <div class="input-field">
                                                    <select name="remarks" id="remarks">
                                                        @foreach($remarks as $remark)
                                                            <option value="{{$remark->id}}">{{$remark->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                <a href="#" class="btn blue lighten-2" onclick="addRepairedVehiclePart()">Add Part <i
                                                        class="medium material-icons">add</i></a>
                                                <a href="#" class="btn red darken-4" onclick="deleteRepairedVehiclePart()">Remove <i
                                                        class="medium material-icons">remove</i></a>
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
                                    <div class="step-title waves-effect waves-dark">Upload Images</div>
                                    <div class="step-content">
                                        <div class="row">
                                            <div class="col s12">
                                                <form action="#" enctype="multipart/form-data">
                                                    <div class="input-images"></div>
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
                                                        <input type="checkbox"/>
                                                        <span>Save as Draft</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="step-actions">
                                            <input type="submit" class="waves-effect waves-dark btn next-step"
                                                   value="SUBMIT"/>
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
