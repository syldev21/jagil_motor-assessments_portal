<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalvageRegister extends Model
{
    protected $connection = "mysql";
    protected $table = "salvage_registers";
    protected $primaryKey = "id";
    protected $fillable = ['vehicleRegNo','claimID','claimNo','buyerID','cost','logbookReceived','logbookReceivedByRecoveryOfficer','logbookDateReceived','insuredInterestedWithSalvage', 'insuredRetainedSalvage','recovered','recoveredBy','recordsReceived','documentsIssued','dateRecovered','location','createdBy','updatedBy',
        'dateModified','dateCreated'];
    public $timestamps= false;

    public function assessment() {
        return $this->belongsTo(Assessment::class, 'claimID', 'claimID');
    }
    public function vendor(){
        return $this->hasOne(Vendor::class,'id','buyerID');
    }
    public function claim(){
        return $this->belongsTo(Claim::class,'claimID','id');
    }
}
