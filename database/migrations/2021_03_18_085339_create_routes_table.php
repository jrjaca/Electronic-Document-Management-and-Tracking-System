<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->bigIncrements('route_id');
            $table->string('tracking_no')->comment('Format: YYMMDD-HRMMSS-IDNO  ex: 210325-010203-20556408. Use server datetime');
            $table->text('document_id')->nullable(); //for Saving document used. Blank if routing
            //$table->boolean('is_draft')->default(false);
            $table->char('status', 1)->comment('D-Draft, F-Finalized/first released, R-Released, C-Received, T-Terminal. For saving docs: L-Released(sent), C-Received(Sent), 1-Released(routed), 2-Received(Routed)');
            $table->integer('user_id')->comment('Created by');
            $table->integer('office_id')->comment('Created from');
            $table->integer('department_id')->comment('Created from');
            $table->integer('section_id')->nullable()->comment('Created from');
            $table->string('title');
            $table->text('remarks')->nullable();
            $table->integer('document_type_id');
            $table->integer('document_action_id');
            $table->integer('courier_user_id')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('extension')->nullable();
            $table->string('size')->nullable();
            $table->string('path')->nullable();
            $table->boolean('email_notification')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->timestamp('routed_at')->nullable();
            $table->timestamp('terminal_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->integer('terminal_office_id')->comment('Terminal to');
            $table->integer('terminal_department_id')->comment('Terminal to');
            $table->integer('terminal_section_id')->nullable()->comment('Terminal to');
            $table->integer('terminal_user_id')->comment('Who tag as terminal');
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
        Schema::dropIfExists('routes');
    }
}
