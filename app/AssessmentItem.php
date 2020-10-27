<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentItem extends Model
{
    protected $connection = "mysql";
    protected $table = "assessment_items";
    protected $primaryKey = "id";
    protected $fillable = ['assessmentID','partID','quantity','contribution','discount','cost',
        'total','remarks','assessmentItemType','category','reInspection','reInspectionType','segment','modifiedBy',
        'createdBy','dateModified','dateCreated'];
    public $timestamps= false;

    public function part() {
        return $this->belongsTo(Part::class, 'partID', 'id');
    }
    public function remark() {
        return $this->belongsTo(Remarks::class, 'remarks', 'id');
    }
}
