<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalvageRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salvage_registers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vehicleRegNo')->nullable();
            $table->bigInteger('claimID')->unsigned();
            $table->string('claimNo')->nullable();
            $table->bigInteger('buyerID')->nullable();
            $table->double('cost')->nullable();
            $table->tinyInteger('logbookReceived')->default(0);
            $table->tinyInteger('logbookReceivedByRecoveryOfficer')->default(0);
            $table->tinyInteger('insuredInterestedWithSalvage')->default(0);

            $table->tinyInteger('insuredRetainedSalvage')->default(0);
         
            $table->dateTime('logbookDateReceived')->nullable();
            $table->tinyInteger('recovered')->default(0);
            $table->bigInteger('recoveredBy')->nullable();
            $table->tinyInteger('recordsReceived')->default(0);
            $table->tinyInteger('documentsIssued')->default(0);
            $table->dateTime('dateRecovered')->nullable();
            $table->string('location')->nullable();
            $table->integer('createdBy')->nullable();
            $table->integer('updatedBy')->nullable();
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
        Schema::dropIfExists('salvage_registers');
    }
}
