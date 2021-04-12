<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vehicleRegNo')->nullable();
            $table->string('claimNo')->nullable();
            $table->string('policyNo')->nullable();
            $table->bigInteger('userID')->unsigned();
            $table->string('role')->nullable();
            $table->string('activity')->nullable();
            $table->longText('notification')->nullable();
            $table->string('notificationTo')->nullable();
            $table->longText('cc')->nullable();
            $table->string('notificationType')->nullable();
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
        Schema::dropIfExists('activity_logs');
    }
}
