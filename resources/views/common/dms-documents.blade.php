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
                                <h4 class="card-title float-left">DMS Documents</h4>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s12">
                                    <table id="data-table-simple" class="display">
                                        <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Document</th>
                                            <th>Claim Number</th>
                                            <th>Policy Number</th>
                                            <th>Document Type</th>
                                            <th>Source</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($documents as $document)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td><a href="{{$document->link}}">Document_{{$loop->iteration}}</a></td>
                                                <td>{{$document->claimNo}}</td>
                                                <td>{{$document->policyNo}}</td>
                                                <td>{{$document->type}}</td>
                                                <td>{{$document->source}}</td>
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


