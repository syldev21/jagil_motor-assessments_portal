<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusTracker extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "status_trackers";
    protected $primaryKey = "id";
    protected $fillable = ['claimID','assessmentID','oldStatus','newStatus','statusType','modifiedBy','createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
