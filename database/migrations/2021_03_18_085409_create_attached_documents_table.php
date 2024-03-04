<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachedDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attached_documents', function (Blueprint $table) {
            $table->bigIncrements('attached_document_id');
            $table->integer('document_id');    
            //$table->integer('route_id');
            $table->integer('user_id');
            $table->string('attachment_name')->nullable();
            $table->string('extension')->nullable();
            $table->string('size')->nullable();
            $table->string('path')->nullable();
            $table->text('external_link')->nullable();
            $table->boolean('opened')->default(false)->comment('Link has been click');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attached_documents');
    }
}
