<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdByCompanySpielgruppensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spielgruppens', function (Blueprint $table) {
            $table->integer('id_by_company')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spielgruppens', function (Blueprint $table) {
            $table->dropColumn('id_by_company');
        });
    }
}
