<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourtesyCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courtesy_cars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('vendorID')->nullable();
            $table->integer('claimID')->nullable();
            $table->integer('numberOfDays')->nullable();
            $table->dateTime('returnDate')->useCurrent();
            $table->integer('charge')->nullable();
            $table->integer('totalCharge')->nullable();
            $table->integer('modifiedBy')->nullable();
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
        Schema::dropIfExists('courtesy_cars');
    }
}
