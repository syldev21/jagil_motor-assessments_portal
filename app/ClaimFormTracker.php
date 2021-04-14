<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaimFormTracker extends Model
{
    protected $connection = "mysql";
    protected $table = "claim_form_trackers";
    protected $primaryKey = "id";
    protected $fillable = ['claimID','claimNo','policyNo','vehicleRegNo','customerCode','notificationCount','status','createdBy','dateCreated'];
    public $timestamps= false;
}
