<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssessmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('assessmentID')->unsigned();
            $table->bigInteger('partID')->unsigned();
            $table->integer('quantity')->nullable();
            $table->double('contribution')->nullable();
            $table->integer('discount')->nullable();
            $table->double('cost')->nullable();
            $table->double('total')->nullable();
            $table->longText('remarks')->nullable();
            $table->integer('assessmentItemType')->nullable();
            $table->tinyInteger('category')->default(0);
            $table->tinyInteger('reInspection')->default(0);
            $table->integer('reInspectionType')->nullable();
            $table->integer('segment')->default(0);
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
        Schema::dropIfExists('assessment_items');
    }
}
