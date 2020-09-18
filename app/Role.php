<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "roles";
    protected $primaryKey = "id";
    protected $fillable = ['name','active'];
    public $timestamps= false;
}
