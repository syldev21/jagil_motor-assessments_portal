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
            $table->bigInteger('assessedBy')->unsigned();
            $table->dateTime('assessedAt')->nullable();
            $table->bigInteger('garageID')->nullable();
            $table->bigInteger('assessmentTypeID')->nullable();
            $table->double('pav')->nullable();
            $table->double('salvage')->nullable();
            $table->double('totalLoss')->nullable();
            $table->double('totalCost')->nullable();
            $table->double('totalChange')->nullable();
            $table->double('priceChange')->nullable();
            $table->longText('cause')->nullable();
            $table->longText('note')->nullable();
            $table->bigInteger('assessmentStatusID')->nullable();
            $table->bigInteger('changeTypeID')->nullable();
            $table->bigInteger('segment')->nullable();
            $table->bigInteger('approvedBy')->nullable();
            $table->dateTime('approvedAt')->nullable();
            $table->bigInteger('finalApprovalBy')->nullable();
            $table->dateTime('finalApprovedAt')->nullable();
            $table->tinyInteger('changesDue')->default(0);
            $table->dateTime('changeRequestAt')->nullable();
            $table->longText('reviewNote')->nullable();
            $table->double('scrapValue')->nullable();
            $table->tinyInteger('scrap')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->tinyInteger('isSubrogate')->default(0);
            $table->bigInteger('companyID')->nullable();
            $table->tinyInteger('isTheft')->nullable();
            $table->double('PTV')->nullable();
            $table->bigInteger('createdBy')->nullable();
            $table->bigInteger('updatedBy')->nullable();
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
        Schema::dropIfExists('assessments');
    }
}
