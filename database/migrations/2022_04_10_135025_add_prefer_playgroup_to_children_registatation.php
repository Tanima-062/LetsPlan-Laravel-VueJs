<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreferPlaygroupToChildrenRegistatation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('children_registrations', function (Blueprint $table) {
            $table->integer('spielgruppen_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('children_registrations', function (Blueprint $table) {
            $table->dropColumn('spielgruppen_id');
        });
    }
}
