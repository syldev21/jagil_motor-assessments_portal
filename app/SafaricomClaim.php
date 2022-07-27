<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SafaricomClaim extends Model
{
    protected $connection = "mysql";
    protected $table = "safaricom_home_claims";
    protected $primaryKey = "id";
    protected $fillable = ['ci_code','lossDescription'];
    public $timestamps= false;
}
