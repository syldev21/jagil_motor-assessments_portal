<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remarks extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "remarks";
    protected $primaryKey = "id";
    protected $fillable = ['name','createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
