<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_post_id');
            $table->unsignedBigInteger('applicant_account_id');
            $table->smallInteger('is_approved')->default(1);
            $table->string('status',50);
            $table->timestamps();
            $table->foreign('job_post_id')->references('id')->on('job_posts');
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
        Schema::dropIfExists('job_applications');
    }
}
