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
                                <h4 class="card-title float-left">Claim Exceptions</h4>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                   <table>
                                       <thead>
                                       <tr>
                                           <th>S/N</th>
                                           <th>Date Modified</th>
                                           <th>Claim No</th>
                                           <th>Vehicle Reg No</th>
                                           <th>Sum Insured</th>
                                           <th>Excess</th>
                                           <th>Garage</th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                       <tr>
                                           <td></td>
                                           <td>{{$claim->dateModified}}</td>
                                           <td>{{$claim->claimNo}}</td>
                                           <td>{{$claim->vehicleRegNo}}</td>
                                           <td>{{$claim->sumInsured}}</td>
                                           <td>{{$claim->excess}}</td>
                                           <td>{{$claim->garageID}}</td>
                                       </tr>
                                       <tr>
                                           <td colspan="6"></td>
                                       </tr>
                                       @foreach($claim->claimtracker as $claimtracker)
                                           <tr>
                                               <td>{{$loop->iteration}}</td>
                                               <td>{{$claimtracker->dateModified}}</td>
                                               <td>{{$claim->claimNo}}</td>
                                               <td>{{$claim->vehicleRegNo}}</td>
                                               <td>{{$claimtracker->sumInsured}}</td>
                                               <td>{{$claimtracker->excess}}</td>
                                               <td>{{$claimtracker->garageID}}</td>
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
