<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalColumsToChildrenRegistrationOpeningHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('children_registration_opening_hours', function (Blueprint $table) {
            $table->integer('order')->after('created_at')->default(0);
            $table->integer('spielgruppen_id')->after('created_at')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('children_registration_opening_hours', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropColumn('spielgruppen_id');
        });
    }
}
