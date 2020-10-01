<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('claimID')->unsigned();
            $table->bigInteger('assessmentID')->default(0);
            $table->bigInteger('userID')->unsigned();
            $table->bigInteger('garageID')->nullable();
            $table->bigInteger('assessmentTypeID')->nullable();
            $table->double('pav')->nullable();
            $table->double('salvage')->nullable();
            $table->double('totalLoss')->nullable();
            $table->double('totalCost')->nullable();
            $table->longText('cause')->nullable();
            $table->longText('note')->nullable();
            $table->bigInteger('assessmentStatusID')->nullable();
            $table->timestamp('approvedBy')->nullable();
            $table->timestamp('dateModified')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('assessments');
    }
}
