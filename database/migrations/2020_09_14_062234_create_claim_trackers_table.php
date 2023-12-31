<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_trackers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('claimID')->nullable();
            $table->string('claimNo')->nullable();
            $table->string('policyNo')->nullable();
            $table->string('location')->nullable();
            $table->double('sumInsured')->nullable();
            $table->double('excess')->nullable();
            $table->bigInteger('garageID')->nullable();
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
        Schema::dropIfExists('claim_trackers');
    }
}
