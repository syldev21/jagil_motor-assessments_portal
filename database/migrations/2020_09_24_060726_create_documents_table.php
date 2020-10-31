<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->bigInteger('claimID')->nullable();
            $table->bigInteger('assessmentID')->nullable();
            $table->bigInteger('inspectionID')->nullable();
            $table->integer('documentType')->nullable();
            $table->integer('segment')->nullable();
            $table->string('mime')->nullable();
            $table->integer('size')->nullable();
            $table->tinyInteger('isResized')->default(0);
            $table->string('url')->nullable();
            $table->integer('modifiedBy')->nullable();
            $table->integer('createdBy')->nullable();
            $table->timestamp('dateModified')->nullable();
            $table->dateTime('dateCreated')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
