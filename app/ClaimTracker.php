<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaimTracker extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "claim_trackers";
    protected $primaryKey = "id";
    protected $fillable = ['claimID','claimNo','policyNo','createdBy','excess','garageID','sumInsured','location','updatedBy','dateModified','dateCreated'];
    public $timestamps= false;

    public function claim() {
        return $this->belongsTo(Claim::class, 'claimID', 'id');
    }
}
