<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpielgruppensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spielgruppens', function (Blueprint $table) {
            $table->id();
            $table->string('image')->index();
            $table->string('name');
            $table->string('street_name');
            $table->string('place_id');
            $table->integer('street_number');
            $table->integer('postal_code');
            $table->string('location');
            $table->integer('minimum_occupancy');
            $table->integer('maximum_occupancy');
            $table->integer('status_id');
            $table->integer('parent_admin_id');
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
        Schema::dropIfExists('spielgruppens');
    }
}
