<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "assessments";
    protected $primaryKey = "id";
    protected $fillable = ['claimID','assessmentID','userID','garageID','assessmentTypeID',
        'approvedBy','totalCost','cause',
        'assessmentStatusID',
        'dateModified','dateCreated'];
    public $timestamps= false;

    public function claim() {
        return $this->belongsTo(Claim::class, 'claimID', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'userID','id');
    }
}
