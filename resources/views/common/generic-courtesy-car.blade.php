<div class="row">
    <div class="col s2"></div>
    <div class="col s8">
        <!-- Modal Structure -->
        <div id="addCourtesyCarFirmModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="#" class="modal-action modal-close float-right"><i class="material-icons">close</i></a>
                </div>
                <div class="modal-body clearfix">
                    <div class="row">
                        <div class="input-field col m12 s12">
                            <div class="container">
                                <div class="input-field">
                                    <select class="browser-default" id="vendorID">
                                        <?php
                                        $vendors = \App\Vendor::where(['type'=>\App\Conf\Config::DISPLAY_VENDOR_TYPES['COURTESY_CAR']['ID']])->get();
                                        ?>
                                        @foreach($vendors as $vendor)
                                        <option value="{{$vendor->id}}">{{$vendor->fullName}}</option>
                                        @endforeach
                                    </select>
                                    <label for="vendorID" class="active">Select Car Hire Firm</label>
                                </div>
                                <div class="input-field">
                                    <input id="numberOfDays" type="number" name="numberOfDays"
                                           value="">
                                    <label for="numberOfDays" class="active">Number of Days</label>
                                </div>
                                <div class="input-field">
                                    <input readonly type="text" id="returnDate" class="active" value=""/>

                                    <label for="returnDate"  class="active" >Return Date</label>
                                </div>
                                <div class="input-field">
                                    <input  id="charge" type="number" name="charge"
                                           value="">
                                    <label for="charge" class="active">Charge</label>
                                </div>
                                <div class="input-field">
                                    <input readonly id="totalCharge" type="text" name="totalCharge" onchange="changeHandler(event)"
                                           value="">
                                    <label for="charge" class="active">Total Charge</label>
                                    <input type="text" id="claimID">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s8"></div>
                        <div class="input-field col s2">
                            <a href="#" id="addCourtesyCar" class="btn blue lighten-2 waves-effect showActionButton actionButton">Submit</a>
                            <a href="#"
                               class="float-right btn cyan waves-effect waves-effect waves-light hideLoadingButton loadingButton"
                            >
                                <div class="preloader-wrapper small active float-left">
                                    <div class="spinner-layer spinner-blue-only">
                                        <div class="circle-clipper left">
                                            <div class="circle"></div>
                                        </div><div class="gap-patch">
                                            <div class="circle"></div>
                                        </div><div class="circle-clipper right">
                                            <div class="circle"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="float-right"> Loading</div>
                            </a>
                        </div>
                        <div class="input-field col s2">
                            <a href="#" class="modal-action modal-close btn red lighten-2 waves-effect">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($errors->any())
            <div>
                @foreach($errors->all() as $error)
                    <li>
                        {{$error}}
                    </li>
                @endforeach
            </div>
        @endif
    </div>
    <div class="col s2"></div>
</div>
