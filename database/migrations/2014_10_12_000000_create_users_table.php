<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('firstName')->nullable();
            $table->string('middleName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('idNumber')->nullable();
            $table->string('email')->unique();
            $table->bigInteger('branch_id')->nullable()->unsigned();
            $table->bigInteger('userTypeID')->nullable()->unsigned();
            $table->string('MSISDN')->nullable();
            $table->string('location')->nullable();;
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->timestamp('loggedInAt')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('emailVerifiedAt')->nullable();
            $table->timestamp('loggedOutAt')->nullable();
            $table->string('password');
            $table->boolean('loginAttemps')->default(0);
            $table->boolean('active')->default(0);
            $table->boolean('online')->default(0);
            $table->integer('durationOnline')->default(0);
            $table->boolean('accountLocked')->default(0);
            $table->timestamp('dateModified')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('dateCreated')->nullable();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
