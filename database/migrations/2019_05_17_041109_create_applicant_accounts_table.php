<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username',70)->unique();
            $table->string('password');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_log')->default(false);
            $table->boolean('is_resume')->default(false);
            $table->boolean('is_specialization')->default(false);
            $table->boolean('is_archive')->default(false);
            $table->timestamp('last_log')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('applicant_accounts');
    }
}
