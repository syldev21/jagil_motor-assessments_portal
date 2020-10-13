<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $connection = "mysql";
    protected $table = "car_models";
    protected $primaryKey = "id";
    protected $fillable = ['makeCode','makeName','modelCode','modelName','createdBy','updatedBy','dateModified','dateCreated'];
    public $timestamps= false;
}
