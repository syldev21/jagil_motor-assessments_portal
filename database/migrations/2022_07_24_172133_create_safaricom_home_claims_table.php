<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSafaricomHomeClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safaricom_home_claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ci_code')->nullable();
            $table->string('policyNumber')->nullable();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('postalCode')->nullable();
            $table->string('telOffice')->nullable();
            $table->string('telHouse')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('occupation')->nullable();
            $table->dateTime('dateOfLoss')->nullable();
            $table->dateTime('TimeOfLoss')->nullable();
            $table->longText('situationOfPlace')->nullable();
            $table->longText('lossDescription')->nullable();
            $table->dateTime('dateDiscovered')->nullable();
            $table->string('whoDiscovered')->nullable();
            $table->dateTime('lastSeen')->nullable();
            $table->dateTime('datePoliceNotified')->nullable();
            $table->string('policeStation')->nullable();
            $table->integer('premiseOccupied')->nullable();
            $table->integer('premiseNotOccupied')->nullable();
            $table->string('premiseOccupiedBy')->nullable();
            $table->dateTime('premiseLastOccupied')->nullable();
            $table->dateTime('timePremiseLastOccupied')->nullable();
            $table->integer('watchmanPresent')->nullable();
            $table->integer('watchmanNotPresent')->nullable();
            $table->integer('soleOwner')->nullable();
            $table->integer('notNoleOwner')->nullable();
            $table->longText('owners')->nullable();
            $table->integer('thirdPartyLoss')->nullable();
            $table->integer('noThirdPartyLoss')->nullable();
            $table->integer('previousClaim')->nullable();
            $table->integer('noPreviousClaim')->nullable();
            $table->longText('claimParticulars')->nullable();
            $table->longText('propertyDescription')->nullable();
            $table->dateTime('datePurchased')->nullable();
            $table->string('propertyOrigin')->nullable();
            $table->integer('propertyCost')->nullable();
            $table->integer('amountClaimed')->nullable();
            $table->integer('totalPropertyCost')->nullable();
            $table->integer('totalAmountClaimed')->nullable();
            $table->integer('updatedBy')->nullable();
            $table->integer('createdBy')->nullable();
            $table->dateTime('dateModified')->nullable();
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
        Schema::dropIfExists('safaricom_home_claims');
    }
}
