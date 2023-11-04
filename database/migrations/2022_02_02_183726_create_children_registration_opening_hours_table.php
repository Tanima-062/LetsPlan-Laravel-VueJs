<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildrenRegistrationOpeningHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('children_registration_opening_hours', function (Blueprint $table) {
            $table->id();
            $table->integer('children_registration_id')->index('children_registration_index_id');
            $table->integer('day_id')->index();
            $table->integer('hour_id')->index();
            $table->string('color')->index();
            $table->string('created_from');
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
        Schema::dropIfExists('children_registration_opening_hours');
    }
}
