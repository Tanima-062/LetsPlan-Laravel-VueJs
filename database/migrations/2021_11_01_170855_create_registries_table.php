<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registries', function (Blueprint $table) {
            $table->id();
            $table->date('registration_phase_start_date');
            $table->date('registration_phase_end_date')->index();
            $table->date('playground_period_start_date');
            $table->date('playground_period_end_date');
            $table->date('parents_inform_date');
            $table->date('open_day_date');
            $table->time('open_day_time_from');
            $table->time('open_day_time_to');
            $table->text('content')->nullable();
            $table->integer('parent_admid_id');
            $table->integer('company_id')->index();
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
        Schema::dropIfExists('registries');
    }
}
