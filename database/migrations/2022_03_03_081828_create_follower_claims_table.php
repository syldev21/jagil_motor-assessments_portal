<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowerClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follower_claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('CLM_SYS_ID')->nullable();
            $table->integer('CLM_POL_SYS_ID')->nullable();
            $table->string('CLM_POL_NO')->nullable();
            $table->string('CLM_NO')->nullable();
            $table->double('SHARE_PERC')->nullable();
            $table->string('PCPC_LEADER_YN')->nullable();
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
        Schema::dropIfExists('follower_claims');
    }
}

