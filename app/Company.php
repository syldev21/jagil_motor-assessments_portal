<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = "mysql";
    protected $table = "companies";
    protected $primaryKey = "id";
    protected $fillable = ['name','email','building','street','city','updatedBy','createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
