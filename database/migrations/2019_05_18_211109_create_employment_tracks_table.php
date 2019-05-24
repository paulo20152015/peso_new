<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmploymentTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_tracks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_detail_id');
            $table->unsignedBigInteger('applicant_account_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('position',70);
            $table->string('status',70);
            $table->timestamps();
            $table->foreign('company_detail_id')->references('id')->on('company_details');
            $table->foreign('applicant_account_id')->references('id')->on('applicant_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employment_tracks');
    }
}
