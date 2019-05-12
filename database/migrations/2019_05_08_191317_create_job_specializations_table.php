<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobSpecializationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_specializations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_post_id');
            $table->unsignedBigInteger('specialization_id');
            $table->timestamps();
            $table->foreign('job_post_id')->references('id')->on('job_posts');
            $table->foreign('specialization_id')->references('id')->on('specializations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_specializations');
    }
}
