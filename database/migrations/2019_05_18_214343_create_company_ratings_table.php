<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('message');
            $table->string('rate');
            $table->unsignedBigInteger('applicant_account_id');
            $table->unsignedBigInteger('company_account_id');
            $table->timestamps();
            $table->foreign('applicant_account_id')->references('id')->on('applicant_accounts');
            $table->foreign('company_account_id')->references('id')->on('company_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_ratings');
    }
}
