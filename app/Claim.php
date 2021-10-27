<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "claims";
    protected $primaryKey = "id";
    protected $fillable = ['claimNo','policyNo','branch','vehicleRegNo','carMakeCode','carModelCode','engineNumber','chassisNumber','yom','garageID','centerID','customerCode','claimType',
        'sumInsured','excess','intimationDate','loseDate','location','changed','salvageProcessed','salvageProcessedDate','salvageProcessedBy','LPOAmount','LPOAddedBy','LPODateCreated','createdBy','updatedBy','claimStatusID','active','inPremia','archivedBy','archivalNote','archivedAt','dateModified','dateCreated'];
    public $timestamps= false;

    public function assessment() {
        return $this->hasMany(Assessment::class, 'claimID', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(CustomerMaster::class,'customerCode','customerCode');
    }
    public function adjuster()
    {
        return $this->belongsTo(User::class,'createdBy','id');
    }
    public function claimtracker() {
        return $this->hasMany(ClaimTracker::class, 'claimID', 'id');
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'carModelCode', 'carModelCode');
    }
    public function documents()
    {
        return $this->hasMany(Document::class,'claimID','id');
    }
    public function garage()
    {
        return $this->hasOne(Garage::class,'id','garageID');
    }
}
