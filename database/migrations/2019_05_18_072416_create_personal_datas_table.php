<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_datas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fname',50);
            $table->string('mname',50)->nullable();
            $table->string('lname',50);
            $table->date('date_of_birth');
            $table->string('email',70);
            $table->boolean('gender');//0 male 1 female
            $table->string('contact_num');
            $table->string('civil_status',70);
            $table->longText('resume')->nullable();
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
        Schema::dropIfExists('personal_datas');
    }
}
