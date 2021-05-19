<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $connection = "mysql";
    protected $table = "pr_departments";
    protected $primaryKey= "id";
    protected $fillable = ['code','divisionCode','name','dateModified','dateCreated'];
    public $timestamps= false;
}


