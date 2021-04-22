<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "assessments";
    protected $primaryKey = "id";
    protected $fillable = ['claimID','assessmentID','assessedBy','assessedAt','garageID','assessmentTypeID','pav','salvage',
        'totalLoss','totalCost','totalChange','priceChange','cause','note',
        'assessmentStatusID','changeTypeID','segment','approvedBy','approvedAt','finalApprovalBy','finalApprovedAt','changesDue','changeRequestAt','reviewNote','scrapValue', 'scrap','active','createdBy','updatedBy',
        'dateModified','dateCreated'];
    public $timestamps= false;

    public function claim() {
        return $this->belongsTo(Claim::class, 'claimID', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'userID','id');
    }
    public function approver()
    {
        return $this->belongsTo(User::class,'approvedBy','id');
    }
    public function final_approver()
    {
        return $this->belongsTo(User::class,'finalApprovalBy','id');
    }
    public function assessor()
    {
        return $this->belongsTo(User::class,'assessedBy','id');
    }
    public function garage()
    {
        return $this->belongsTo(Garage::class,'garageID','id');
    }
    public function reInspection()
    {
      return $this->hasOne(ReInspection::class,'assessmentID','id');
    }
    public function supplementaries()
    {
        return $this->hasMany(Assessment::class,'assessmentID','id');
    }
}
