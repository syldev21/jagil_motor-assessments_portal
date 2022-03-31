<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowerProportionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follower_proportions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('claim_id')->nullable();
            $table->integer('COINSURER_CODE')->nullable();
            $table->string('COINSURER_NAME')->nullable();
            $table->double('SHARE_PERC')->nullable();
            $table->integer('CLAIM_AMOUNT')->nullable();
            $table->integer('SHARE_AMOUNT')->nullable();
            $table->integer('modifiedBy')->nullable();
            $table->integer('createdBy')->nullable();
            $table->timestamp('dateModified')->nullable();
            $table->timestamp('dateCreated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follower_proportions');
    }
}
