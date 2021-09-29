<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $connection = "mysql";
    protected $table = "vendors";
    protected $primaryKey = "id";
    protected $fillable = ['firstName','lastName','fullName','email','MSISDN','type','businessType','companyName','location','status','dateModified','dateCreated'];
    public $timestamps= false;
}
