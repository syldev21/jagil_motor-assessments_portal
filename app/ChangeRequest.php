<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    protected $connection = "mysql";
    protected $table = "change_requests";
    protected $primaryKey = "id";
    protected $fillable = ['assessmentID','changeRequest','createdBy','directedTo','status','updatedBy','dateModified','dateCreated'];
    public $timestamps= false;
}
