<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimMock extends Model
{

    protected $primaryKey = "id";
    protected $fillable =['claimNo', 'policyNo', 'agent', 'insured', 'claimant', 'postalAddress', 'postalCode', 'telephone', 'mobile', 'email', 'occupation', 'dateOfBirth', 'IDNumber', 'placeOfLoss', 'causeOfLoss','typeOfInjury', 'dateOfInjury', 'dateReceived', 'status','lossDescription', 'modifiedBy', 'createdBy', 'dateModified', 'dateCreated', 'deleted_at'];
    public $timestamps= false;
}
