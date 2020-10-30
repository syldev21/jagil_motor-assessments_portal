<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReInspection extends Model
{
    protected $connection = "mysql";
    protected $table = "re_inspections";
    protected $primaryKey = "id";
    protected $fillable = ['assessmentID','labor','addLabor','notes','total','approved','approvedBy','approvedAt','modifiedBy','createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
