<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    protected $connection = "mysql";
    protected $table = "pr_renewals";
    protected $primaryKey = "ID";
    protected $fillable = ['sysID','policyNumber','policyFromDate',
        'policyToDate','productCode','productDesc','coverTypeCode','coverType',
        'vehicleUsageCode','vehicleUsage','make','model','vehicleRegNo',
        'YOM','premiumAmount','lossRatio','claimAmount','loadFactor','premiumCode',
        'coverDescription','premiumDescription','premiumSiFc',
        'applicationRate','applicationRatePer','applicationMinimumPremium',
        'premiumFC','FAPPremium','renewalPremium','UWRenewalPremium',
        'coverErrYn','policyUwYn','coverUwYn','customerCode',
        'policyHolderCustomerCode','customerName','assuredCode',
        'assuredName','corrected','approved','approvedAll','createdBy','updatedBy',
        'dateModified','dateCreated'];
    public $timestamps= false;

    public function departments() {
        return $this->belongsTo(Department::class, 'departmentCode', 'code');
    }
    public function divisions() {
        return $this->belongsTo(Division::class, 'divisionCode', 'code');
    }
}
