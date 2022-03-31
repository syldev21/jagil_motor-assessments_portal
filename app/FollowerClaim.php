<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowerClaim extends Model
{
    protected $fillable = ['CLM_SYS_ID', 'CLM_POL_SYS_ID', 'CLM_POL_NO', 'CLM_NO', 'SHARE_PERC', 'PCPC_LEADER_YN', 'CLAIM_AMOUNT', 'SHARE_AMOUNT', 'modifiedBy','createdBy', 'dateModified', 'dateCreated'];
    public $timestamps= false;
}
