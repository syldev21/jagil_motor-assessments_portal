<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerMaster extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "customer_masters";
    protected $primaryKey = "id";
    protected $fillable = ['customerCode','MSISDN','firstName','middleName','lastName','fullName',
        'customerType','email','idNumber','location','latitude','longitude','dateModified','dateCreated'];
    public $timestamps= false;

    public function claim()
    {
        return $this->hasMany(Claim::class,'customerCode','customerCode');
    }
}
