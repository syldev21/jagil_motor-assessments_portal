@include('_partials.header')
@include('_partials.navbar')
@include('_partials.sidebar')
<!-- Page Length Options -->
<div id="main">
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
                                    <h4 class="card-title float-left">Upload Claim Documents</h4>
                                </div>
                                <div class="divider"></div>
                                <div class="row">
                                    <div class="col s12">
                                        <div class="col s12 m8 l9">
                                            <input type="file" id="input-file-now-custom-2" class="dropify" data-height="500" multiple />
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
@include('_partials.settings')
@include('_partials.footer')
