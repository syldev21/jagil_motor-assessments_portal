<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LPOTracker extends Model
{
    protected $connection = "mysql";
    protected $table = "l_p_o_trackers";
    protected $primaryKey = "id";
    protected $fillable = ['claimNo', 'policyNo', 'initialAmount', 'currentAmount', 'createdBy', 'updatedBy', 'dateCreated', 'dateModified'];
    public $timestamps= false;
}
