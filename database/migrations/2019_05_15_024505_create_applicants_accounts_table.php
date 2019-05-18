<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantsAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username',70)->unique();
            $table->string('password');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_log')->default(false);
            $table->timestamp('last_log')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicants_accounts');
    }
}
