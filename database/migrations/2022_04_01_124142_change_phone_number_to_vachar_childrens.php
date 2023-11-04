<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePhoneNumberToVacharChildrens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('children_registrations', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->change();
            $table->string('mobile_phone_number')->nullable()->change();
            $table->string('mobile_phone_number_prefix')->nullable()->change();
            $table->string('phone_number_prefix')->nullable()->change();
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
            //
        });
    }
}
