<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $connection = "mysql";
    protected $table = "parts";
    protected $primaryKey = "id";
    protected $fillable = ['name','modifiedBy','createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
