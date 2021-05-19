<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $connection = "mysql";
    protected $table = "pr_divisions";
    protected $primaryKey = "id";
    protected $fillable = ['code','name','dateModified','dateCreated'];
    public $timestamps= false;
}
