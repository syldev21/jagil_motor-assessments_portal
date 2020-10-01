<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobDetail extends Model
{
    protected $connection = "mysql";
    protected $table = "job_details";
    protected $primaryKey = "id";
    protected $fillable = ['assessmentID','name','jobType','jobCategory','cost','remarks','modifiedBy',
        'createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
