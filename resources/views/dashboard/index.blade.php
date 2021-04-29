<div class="row">
    <div class="col s12 m6 l4">
    </div>
    <div class="col s12 m6 l4">
        <h5 style="text-align: center;"><span class="btn">Assessments</span></h5>
    </div>
    <div class="col s12 m6 l4">
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Uploaded Claims</p>
                <i class="material-icons" style="color: teal;font-size: 3em;">file_upload</i>
                <p>{{ number_format($numberOfClaims) }}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeLeft">
            <center>
                <p>Approved Assessments</p>
                <i class="material-icons" style="color: green;font-size: 3em;">done_all</i>
                <p>{{ number_format($assessments->approved) }}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Provisional Approved Claims</p>
                <i class="material-icons" style="color: orangered;font-size: 3em;">done</i>
                <p>{{ number_format($assessments->provisionallyApproved) }}</p>
            </center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Assessed Claims</p>
                <i class="material-icons" style="color: darkorange;font-size: 3em;">assessment</i>
                <p>{{ number_format($assessments->assessed) }}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeLeft">
            <center>
                <p>Drafted Assessments</p>
                <i class="material-icons" style="color: orange;font-size: 3em;">feedback</i>
                <p>{{ number_format($assessments->drafted) }}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Changes Due Assessments</p>
                <i class="material-icons" style="color: #ac1f40;font-size: 3em;">assignment_late</i>
                <p>{{ number_format($assessments->changesDue) }}</p>
            </center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Flagged Assessments</p>
                <i class="material-icons" style="color: red;font-size: 3em;">flag</i>
                <p>{{ number_format($flaggedAssessments) }}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Archived Claims</p>
                <i class="material-icons" style="color: dodgerblue;font-size: 3em;">archive</i>
                <p>{{ number_format($numberOfArchivedClaims) }}</p>
            </center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
    </div>
    <div class="col s12 m6 l4">
        <h5 style="text-align: center;"><span class="btn">Supplementaries</span></h5>
    </div>
    <div class="col s12 m6 l4">
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeLeft">
            <center>
                <p>Approved Supplementaries</p>
                <i class="material-icons" style="color: green;font-size: 3em;">done_all</i>
                <p>{{ number_format($supplementaries->approved) }}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Provisional Approved Supplementaries</p>
                <i class="material-icons" style="color: orangered;font-size: 3em;">done</i>
                <p>{{ number_format($supplementaries->provisionallyApproved) }}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Assessed Supplementaries</p>
                <i class="material-icons" style="color: darkorange;font-size: 3em;">assessment</i>
                <p>{{ number_format($supplementaries->assessed) }}</p>
            </center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeLeft">
            <center>
                <p>Drafted Supplementaries</p>
                <i class="material-icons" style="color: orange;font-size: 3em;">feedback</i>
                <p>{{ number_format($supplementaries->drafted) }}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Changes Due Supplementaries</p>
                <i class="material-icons" style="color: #ac1f40;font-size: 3em;">assignment_late</i>
                <p>{{ number_format($supplementaries->changesDue) }}</p>
            </center>
        </div>
    </div>
    <div class="col s12 m6 l4">
        <div class="card padding-4 animate fadeRight">
            <center>
                <p>Flagged Supplementaries</p>
                <i class="material-icons" style="color: red;font-size: 3em;">flag</i>
                <p>{{ number_format($flaggedSupplementaries) }}</p>
            </center>
        </div>
    </div>
</div>
