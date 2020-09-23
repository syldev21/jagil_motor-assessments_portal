<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "locations";
    protected $primaryKey = "id";
    protected $fillable = ['name','createdBy','updatedBy','dateModified','dateCreated'];
    public $timestamps= false;
}
