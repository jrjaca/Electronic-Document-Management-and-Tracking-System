<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteTrailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_trails', function (Blueprint $table) {
            $table->bigIncrements('trail_id');
            $table->char('status', 1)->comment('R-Released, E-Received'); //if terminal, insert a received and update the route status as terminal.
            $table->integer('user_id')->comment('Executed by');
            $table->integer('office_id')->comment('Executed from');
            $table->integer('department_id')->comment('Executed from');
            $table->integer('section_id')->nullable()->comment('Executed from');
            $table->string('tracking_no')->unique()->comment('Format: YYMMDD-HRMMSS-IDNO  ex: 210325-010203-20556408. Use server datetime');
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
            $table->timestamp('routed_at')->nullable();
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
        Schema::dropIfExists('route_trails');
    }
}
