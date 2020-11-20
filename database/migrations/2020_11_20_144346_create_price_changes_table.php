<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_changes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('assessmentID')->unsigned();
            $table->bigInteger('assessedBy')->nullable();
            $table->float('previousTotal')->nullable();
            $table->float('currentTotal')->nullable();
            $table->float('priceDifference')->nullable();
            $table->bigInteger('approvedBy')->nullable();
            $table->dateTime('approvedAt')->nullable();
            $table->tinyInteger('finalApproved')->nullable();
            $table->bigInteger('finalApprover')->nullable();
            $table->dateTime('finalApprovedAt')->nullable();
            $table->bigInteger('modifiedBy')->nullable();
            $table->bigInteger('createdBy')->nullable();
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
        Schema::dropIfExists('price_changes');
    }
}
