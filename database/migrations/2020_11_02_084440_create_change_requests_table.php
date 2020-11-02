<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('assessmentID')->unsigned();
            $table->longText('changeRequest')->nullable();
            $table->bigInteger('createdBy')->nullable();
            $table->bigInteger('directedTo')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('change_requests');
    }
}
