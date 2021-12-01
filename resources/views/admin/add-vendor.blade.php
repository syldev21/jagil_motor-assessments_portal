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
                                <h4 class="card-title float-left">Add new Vendor</h4>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="firstName" type="text" name="firstName" value="">
                                            <label for="firstName" class="active">First Name</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="lastName" type="text" name="lastName" value="">
                                            <label for="lastName" class="active">Last Name</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="email" type="text" name="email" value="">
                                            <label for="email" class="active">Email</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="MSISDN" type="text" name="MSISDN" value="">
                                            <label for="MSISDN" class="active">MSISDN</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="idNumber" type="text" name="idNumber" value="">
                                            <label for="idNumber" class="active">ID Number</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="kraPin" type="text" name="kraPin" value="">
                                            <label for="kraPin" class="active">KRA Pin</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <h1 id="test"></h1>
                                        <div class="input-field col m4 s12">
                                            <input placeholder="" id="location" type="text" name="location" value="">
                                            <label for="location" class="active">Location</label>
                                        </div>
                                        <div class="input-field col m4 s12">
                                            <select id="vendorType" name="vendorType">
                                                @foreach(App\Conf\Config::VENDOR_TYPES as $vendor)
                                                    <option value="{{$vendor['ID']}}">{{$vendor['TITLE']}}</option>
                                                @endforeach
                                            </select>
                                            <label for="vendorType" class="active">Vendor Type</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col m8 s12">

                                        </div>
                                        <div class="input-field col m4 s12">
                                            <a href="#"
                                               class="float-right btn cyan waves-effect waves-effect waves-light"
                                               id="addVendor"> <i class="material-icons right">send</i>Submit</a>
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
</div>
