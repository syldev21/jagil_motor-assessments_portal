<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SafClaimDocument extends Model
{
    protected $connection = "mysql";
    protected $table = "saf_claim_documents";
    protected $primaryKey = "id";
    protected $fillable = ['name','claimID','documentType','pdfType','url','mime','size','pdfType','isResized','processed','modifiedBy',
        'createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
