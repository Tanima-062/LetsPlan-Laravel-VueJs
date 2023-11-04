<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStreetNumberToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('children_registrations', function (Blueprint $table) {
            $table->string('street_number')->change();
        });
        Schema::table('spielgruppens', function (Blueprint $table) {
            $table->string('street_number')->change();
        });
    }

}
