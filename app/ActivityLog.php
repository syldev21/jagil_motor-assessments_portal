<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = "mysql";
    protected $table = "activity_logs";
    protected $primaryKey = "id";
    protected $fillable = ['vehicleRegNo','claimNo','policyNo','userID','role','activity','notification','notificationTo','cc','notificationType','createdBy','updatedBy','dateModified','dateCreated'];
    public $timestamps= false;
}
