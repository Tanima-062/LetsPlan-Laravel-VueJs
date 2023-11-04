<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('registration_start_date');
			$table->date('registration_end_date');
			$table->date('playgroup_start_date');
			$table->date('playgroup_end_date');
			$table->date('date_parent');
			$table->date('open_door_date');
            $table->string('open_door_start_time');
            $table->string('open_door_end_time');
            $table->string('place');
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
        Schema::dropIfExists('divisions');
    }
}
