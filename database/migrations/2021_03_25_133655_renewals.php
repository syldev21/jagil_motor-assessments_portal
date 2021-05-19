<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(  'pr_renewals', function (Blueprint $table) {

            $table->bigIncrements("ID")->nullable();
            $table->bigInteger("sysID")->nullable();
            $table->string("policyNumber")->nullable();
            $table->dateTime("policyFromDate")->nullable();
            $table->dateTime("policyToDate")->nullable();
            $table->string("productCode")->nullable();
            $table->string("productDesc")->nullable();
            $table->bigInteger("coverTypeCode")->nullable();
            $table->string("coverType")->nullable();
            $table->string("vehicleUsageCode")->nullable();
            $table->string("vehicleUsage")->nullable();
            $table->string("make")->nullable();
            $table->string("model")->nullable();
            $table->string("vehicleRegNo")->nullable();
            $table->string("YOM")->nullable();
            $table->double("premiumAmount")->nullable();
            $table->double("lossRatio")->nullable();
            $table->double("claimAmount")->nullable();
            $table->double("loadFactor")->nullable();
            $table->string("premiumCode")->nullable();
            $table->string("coverDescription")->nullable();
            $table->string("premiumDescription")->nullable();
            $table->double("premiumSiFc")->nullable();
            $table->double("applicationRate")->nullable();
            $table->double("applicationRatePer")->nullable();
            $table->double("applicationMinimumPremium")->nullable();
            $table->double("premiumFC")->nullable();
            $table->double("FAPPremium")->nullable();
            $table->double("renewalPremium")->nullable();
            $table->double("UWRenewalPremium")->nullable();
            $table->string("coverErrYn")->nullable();
            $table->string("policyUwYn")->nullable();
            $table->string("coverUwYn")->nullable();
            $table->bigInteger("customerCode")->nullable();
            $table->string("policyHolderCustomerCode")->nullable();
            $table->string("customerName")->nullable();
            $table->string("assuredCode")->nullable();
            $table->string("assuredName")->nullable();
            $table->tinyInteger("corrected")->nullable();
            $table->tinyInteger('approved')->nullable();
            $table->string("createdBy")->nullable();
            $table->string("updatedBy")->nullable();
            $table->dateTime("dateModified")->nullable();
            $table->timestamp('dateCreated')->default(DB::raw('CURRENT_TIMESTAMP'));

            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
