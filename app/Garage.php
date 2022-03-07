<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Garage extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "garages";
    protected $primaryKey = "id";
    protected $fillable = ['branchID','name','email','location','longitude','latitude','garageType','modifiedBy',
        'createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
