<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('contact_number')->nullable();
            $table->string('company_size')->nullable();
            $table->string('land_line')->nullable();
            $table->longText('overview')->nullable();
            $table->longText('website')->nullable();
            $table->timestamp('last_log')->nullable();
            $table->unsignedBigInteger('company_account_id');
            $table->timestamps();
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
        Schema::dropIfExists('company_details');
    }
}
