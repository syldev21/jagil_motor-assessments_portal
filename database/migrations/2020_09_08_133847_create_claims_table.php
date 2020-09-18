<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('claimNo')->unique();
            $table->string('policyNo')->unique();
            $table->string('branch')->nullable();
            $table->string('vehicleRegNo')->nullable();
            $table->bigInteger('garageID')->nullable();
            $table->bigInteger('centerID')->nullable();
            $table->string('customerCode')->nullable();
            $table->string('claimType')->nullable();
            $table->double('sumInsured')->nullable();
            $table->double('excess')->nullable();
            $table->dateTime('intimationDate')->nullable();
            $table->dateTime('loseDate')->nullable();
            $table->string('location')->nullable();
            $table->tinyInteger('changed')->default(0);
            $table->integer('createdBy')->nullable();
            $table->integer('updatedBy')->nullable();
            $table->integer('claimStatusID')->nullable();
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
        Schema::dropIfExists('claims');
    }
}
