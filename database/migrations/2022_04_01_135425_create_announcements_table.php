<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigIncrements('announcement_id');
            $table->integer('user_id')->comment('Published by.');
            $table->string('path')->nullable()->comment('Icon or image');
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('details')->nullable();
            $table->integer('updated_byuser_id')->nullable()->comment('Last updated by.');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcements');
    }
}
