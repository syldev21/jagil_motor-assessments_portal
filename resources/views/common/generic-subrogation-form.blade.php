@if($assessment['claim']['isSubrogate'] == App\Conf\Config::ACTIVE)
    <div class="row">
        <div class="col m6" id="subrogationForm">
            <br/>
            <label>
                <input name="subrogation" type="checkbox"
                       class="with-gap subrogation" value="" id="subrogation" @if($assessment['claim']['isSubrogate'] == App\Conf\Config::ACTIVE) checked  @endif disabled/>
                <span>Has Subrogation:</span>
            </label>
        </div>
        <div class="col m6">
            <select id="company" name="company" class="browser-default subrogationSelect" disabled>
                <option value="0">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{$company->id}}" @if($assessment['claim']['companyID'] == $company->id) selected @endif>{{$company->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="input-field col m4 s12">
            <input placeholder="" id="thirdPartyDriver" type="text" name="thirdPartyDriver"
                   value="{{$assessment['claim']['thirdPartyDriver']}}" disabled>
            <label for="thirdPartyDriver" class="active">3rd Party Driver Name</label>
        </div>
        <div class="input-field col m4 s12">
            <input placeholder="" id="thirdPartyPolicy" type="text" name="thirdPartyPolicy"
                   value="{{$assessment['claim']['thirdPartyPolicy']}}" disabled>
            <label for="thirdPartyPolicy" class="active">3rd Party Policy</label>
        </div>
        <div class="input-field col m4 s12">
            <input placeholder="" id="thirdPartyVehicleRegNo" type="text" name="thirdPartyVehicleRegNo"
                   value="{{$assessment['claim']['thirdPartyVehicleRegNo']}}" disabled>
            <label for="thirdPartyVehicleRegNo" class="active">3rd Party VehicleRegNo</label>
        </div>
    </div>
@endif
