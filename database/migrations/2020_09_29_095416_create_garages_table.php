<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGaragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('branchID')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->integer('garageType')->nullable();
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
        Schema::dropIfExists('garages');
    }
}
