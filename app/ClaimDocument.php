<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaimDocument extends Model
{
    protected $connection = "mysql";
    protected $table = "claim_documents";
    protected $primaryKey = "id";
    protected $fillable = ['name','claimID','documentType','pdfType','url','segment','mime','size','pdfType','isResized','processed','modifiedBy',
        'createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
