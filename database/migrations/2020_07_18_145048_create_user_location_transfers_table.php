<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLocationTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_location_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            //$table->string('within_office_transfer')->default('N')->comment('N-No, Y-If transfer is within the office/region.');
            $table->string('user_id_admin_from')->nullable()->comment('Admin from current office');
            $table->string('action_date_from')->nullable();
            $table->string('office_id_from');
            $table->string('department_id_from');
            $table->string('section_id_from')->nullable();
            $table->string('user_id_admin_to')->nullable()->comment('Admin from the transfered office');
            $table->string('action_date_to')->nullable();
            $table->string('office_id_to');
            $table->string('department_id_to');
            $table->string('section_id_to')->nullable();
            $table->string('approved_transfer')->nullable()->comment('N-Disapproved, Y-If transfered successfully');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_location_transfers');
    }
}
