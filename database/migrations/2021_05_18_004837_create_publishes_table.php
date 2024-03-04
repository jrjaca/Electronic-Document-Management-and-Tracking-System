<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {  
        Schema::create('publishes', function (Blueprint $table) {
            $table->bigIncrements('published_id');
            $table->integer('document_id')->nullable()->comment('If the document is previously created.');
            $table->boolean('is_published')->default(false);
            $table->integer('user_id')->comment('Published by.');
            $table->string('attachment_name')->nullable();
            $table->string('extension')->nullable();
            $table->string('size')->nullable();
            $table->string('path')->nullable();
            $table->string('title');
            //$table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('document_type_id');
            $table->integer('office_id')->nullable()->comment('Can only viewed by this office.');
            $table->integer('department_id')->nullable()->comment('Can only viewed by this department.');
            $table->integer('section_id')->nullable()->comment('Can only viewed by this section.');       
            $table->integer('updated_byuser_id')->nullable()->comment('Last updated by.');
            $table->integer('deleted_byuser_id')->nullable()->comment('Last deleted by.');            
            $table->timestamp('published_at')->nullable();
            //$table->timestamp('submitted_at')->nullable();
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
        Schema::dropIfExists('publishes');
    }
}
