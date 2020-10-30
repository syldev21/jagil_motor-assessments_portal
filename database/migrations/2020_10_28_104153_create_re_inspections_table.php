<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('re_inspections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('assessmentID')->unsigned();
            $table->double('total')->nullable();
            $table->longText('notes')->nullable();
            $table->double('labor')->nullable();
            $table->double('addLabor')->default(0);
            $table->dateTime('approvedAt')->nullable();
            $table->bigInteger('approvedBy')->nullable();
            $table->tinyInteger('approved')->default(0);
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
        Schema::dropIfExists('re_inspections');
    }
}
