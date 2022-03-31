<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimMocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_mocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('claimNo')->nullable();
            $table->string('policyNo')->nullable();
            $table->string('agent')->nullable();
            $table->string('insured')->nullable();
            $table->string('claimant')->nullable();
            $table->string('postalAddress')->nullable();
            $table->string('postalCode')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('occupation')->nullable();
            $table->dateTime('dateOfBirth')->nullable();
            $table->integer('IDNumber')->nullable();
            $table->string('placeOfLoss')->nullable();
            $table->string('causeOfLoss')->nullable();
            $table->integer('typeOfInjury')->nullable();
            $table->dateTime('dateOfInjury')->nullable();
            $table->dateTime('dateReceived')->nullable();
            $table->longText('lossDescription')->nullable();
            $table->integer('status')->nullable();
            $table->integer('modifiedBy')->nullable();
            $table->integer('createdBy')->nullable();
            $table->timestamp('dateModified')->nullable();
            $table->timestamp('dateCreated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_mocks');
    }
}
