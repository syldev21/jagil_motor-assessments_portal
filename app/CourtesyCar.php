<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourtesyCar extends Model
{
    protected $connection = "mysql";
    protected $table = "courtesy_cars";
    protected $primaryKey = "id";
    protected $fillable = ['vendorID', 'claimID', 'numberOfDays', 'returnDate', 'charge', 'totalCharge', 'updatedBy', 'createdBy', 'dateModified', 'dateCreated'];
    public $timestamps = false;
    public function claim()
    {
        return $this->belongsTo(Claim::class, 'claimID', 'id');
    }
    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendorID');


    }
}
