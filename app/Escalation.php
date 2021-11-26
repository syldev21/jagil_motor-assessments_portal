<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escalation extends Model
{
    protected $connection = "mysql";
    protected $table = "escalations";
    protected $primaryKey = "id";
    protected $fillable = ['subject', 'to','cc','message','modifiedBy', 'createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
