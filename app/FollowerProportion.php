<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowerProportion extends Model
{
    protected $fillable = ['claim_id', 'COINSURER_CODE', 'COINSURER_NAME', 'SHARE_PERC',  'CLAIM_AMOUNT', 'SHARE_AMOUNT', 'modifiedBy','createdBy', 'dateModified', 'dateCreated'];
    public $timestamps= false;
}
