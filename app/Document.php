<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $connection = "mysql";
    protected $table = "documents";
    protected $primaryKey = "id";
    protected $fillable = ['name','assessmentID','documentType','url','segment','mime','size','isResized','modifiedBy',
        'createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
