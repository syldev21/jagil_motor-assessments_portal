<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSafClaimDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saf_claim_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->bigInteger('claimID')->nullable();
            $table->integer('documentType')->nullable();
            $table->string('mime')->nullable();
            $table->integer('size')->nullable();
            $table->integer('pdfType')->nullable();
            $table->tinyInteger('isResized')->default(0);
            $table->tinyInteger('processed')->default(0);
            $table->string('url')->nullable();
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
        Schema::dropIfExists('saf_claim_documents');
    }
}
