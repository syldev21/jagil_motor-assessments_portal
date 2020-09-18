<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHasRole extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "user_has_roles";
    protected $primaryKey = "id";
    protected $fillable = ['roleID','userID'];
}
