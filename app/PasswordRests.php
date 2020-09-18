<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordRests extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "password_resets";
    protected $fillable = ['email','token','created_at'];
    public $timestamps= false;
}
