<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Picqer\Barcode\Exceptions\BarcodeException;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('document_id');
            $table->string('barcode')->unique()->comment('Format: YYMMDD-HRMMSS-IDNO  ex: 210325-010203-20556408. Use server datetime');
            $table->integer('user_id')->comment('Creator');
            $table->string('title');
            $table->text('remarks')->nullable();
            $table->integer('document_type_id');
            $table->integer('document_action_id');
            $table->boolean('is_hardcopy')->default(false);
            //$table->integer('courier_user_id')->nullable();
            $table->boolean('is_confidential')->default(false);
            //$table->boolean('is_public')->default(false);
            $table->boolean('is_draft')->default(true);
            $table->boolean('email_notification')->default(false);
            $table->timestamps();
            $table->timestamp('submitted_at')->nullable();
            $table->boolean('is_terminal')->default(false);
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
        Schema::dropIfExists('documents');
    }
}
