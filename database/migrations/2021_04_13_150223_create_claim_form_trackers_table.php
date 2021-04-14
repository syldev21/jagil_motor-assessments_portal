<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimFormTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_form_trackers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('claimID')->unsigned();
            $table->string('claimNo')->unique();
            $table->string('policyNo')->nullable();
            $table->string('vehicleRegNo')->nullable();
            $table->string('customerCode')->nullable();
            $table->tinyInteger('notificationCount')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->integer('createdBy')->nullable();
            $table->dateTime('dateCreated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_form_trackers');
    }
}
