<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeenLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seen_logs', function (Blueprint $table) {
            $table->bigIncrements('seen_log_id');
            $table->integer('route_id');
            $table->integer('attached_document_id')->nullable();
            $table->integer('document_id');
            $table->integer('sender_id');
            $table->timestamp('submitted_at')->nullable();
            $table->integer('recipient_id');
            $table->timestamp('last_seen_at')->nullable();
            $table->integer('document_action_id')->nullable();
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seen_logs');
    }
}
