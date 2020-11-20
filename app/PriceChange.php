<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceChange extends Model
{
    protected $connection = "mysql";
    protected $table = "price_changes";
    protected $primaryKey = "id";
    protected $fillable = ['assessmentID','assessedBy','previousTotal','currentTotal','priceDifference','approvedBy','approvedAt','finalApproved','finalApprover','finalApprovedAt','modifiedBy','createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
