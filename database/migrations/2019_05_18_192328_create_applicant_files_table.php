<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('url_name');
            $table->String('type');
            $table->String('main_location');
            $table->unsignedBigInteger('applicant_account_id');
            $table->timestamps();
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
        Schema::dropIfExists('applicant_files');
    }
}
