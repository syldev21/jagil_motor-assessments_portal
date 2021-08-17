<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $connection = "mysql";
    protected $table = "documents";
    protected $primaryKey = "id";
    protected $fillable = ['name','claimID','assessmentID','inspectionID','documentType','pdfType','url','segment','mime','size','isResized','processed','modifiedBy',
        'createdBy','dateModified','dateCreated'];
    public $timestamps= false;

}
