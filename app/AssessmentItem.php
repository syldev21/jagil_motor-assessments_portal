<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentItem extends Model
{
    protected $connection = "mysql";
    protected $table = "assessment_items";
    protected $primaryKey = "id";
    protected $fillable = ['assessmentID','partID','quantity','contribution','discount','cost',
        'total','remarks','assessmentItemType','supplementary','reInspection','repaired','replace','cashInLieu','modifiedBy',
        'createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
