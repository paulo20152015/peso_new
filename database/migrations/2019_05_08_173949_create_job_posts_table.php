<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',70);
            $table->string('full_address');
            $table->string('work_experience',70);
            $table->longText('job_description');
            $table->longText('job_requirements');
            $table->boolean('is_archive')->default(false); 
            $table->unsignedBigInteger('town_id');
            $table->unsignedBigInteger('company_account_id');
            $table->timestamps();
            $table->foreign('company_account_id')->references('id')->on('company_accounts');
            $table->foreign('town_id')->references('id')->on('towns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_posts');
    }
}
