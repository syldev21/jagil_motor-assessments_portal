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
        'totalLoss','totalCost','cause','note',
        'assessmentStatusID','approvedBy','approvedAt','finalApprovalBy','finalApprovedAt','changesDue','reviewNote','createdBy','updatedBy',
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
    public function assessor()
    {
        return $this->belongsTo(User::class,'assessedBy','id');
    }
}
