<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLPOTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_p_o_trackers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('claimNo')->nullable();
            $table->string('policyNo')->nullable();
            $table->double('initialAmount')->nullable();
            $table->double('currentAmount')->nullable();
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
        Schema::dropIfExists('l_p_o_trackers');
    }
}
