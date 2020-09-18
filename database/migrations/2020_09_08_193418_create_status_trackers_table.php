<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_trackers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('claimID')->nullable();
            $table->bigInteger('assessmentID')->nullable();
            $table->integer('oldStatus')->nullable();
            $table->integer('newStatus')->nullable();
            $table->string('statusType')->nullable();
            $table->bigInteger('modifiedBy')->nullable();
            $table->bigInteger('createdBy')->nullable();
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
        Schema::dropIfExists('status_trackers');
    }
}
