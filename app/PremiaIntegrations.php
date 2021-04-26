<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PremiaIntegrations extends Model
{
    protected $connection = "mysql";
    protected $table = "premia_integrations";
    protected $primaryKey = "id";
    protected $fillable = ['response', 'status', 'claimNo','modifiedBy','createdBy','dateModified','dateCreated'];
    public $timestamps= false;
}
