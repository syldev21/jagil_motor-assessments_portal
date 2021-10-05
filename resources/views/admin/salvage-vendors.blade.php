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
                                <h4 class="card-title float-left">Vendor Management</h4>
                                @can(App\Conf\Config::PERMISSIONS['ADD_VENDOR'])
                                <a href="#" id="addVendorForm" class="float-right btn"><i class="material-icons left">add_circle_outline</i> Add Vendor</a>
                                @endcan
                                <br/>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <table id="data-table-simple" class="display">
                                        <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>MSISDN</th>
                                            <th>Company Name</th>
                                            <th>Location</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($vendors as $vendor)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$vendor->firstName}}</td>
                                                <td>{{$vendor->lastName}}</td>
                                                <td>{{$vendor->email}}</td>
                                                <td>{{$vendor->MSISDN}}</td>
                                                <td>{{$vendor->companyName}}</td>
                                                <td>{{$vendor->location}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
