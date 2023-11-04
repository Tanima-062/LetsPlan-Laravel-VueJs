<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildrenRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('children_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('child_first_name');
            $table->string('child_last_name');
            $table->integer('gender_id');
            $table->date('child_birthday');
            $table->integer('nationality_id');
            $table->integer('mother_tongue_id')->nullable();
            $table->string('other_mother_tongue')->nullable();
            $table->string('mother_first_name');
            $table->string('mother_last_name');
            $table->string('father_first_name');
            $table->string('father_last_name');
            $table->string('street');
            $table->string('optional_address');
            $table->integer('street_number');
            $table->integer('postal_code');
            $table->string('place');
            $table->string('email');
            $table->string('phone_number_prefix')->default('+41');
            $table->integer('phone_number');
            $table->string('mobile_phone_number_prefix')->default('+41');
            $table->integer('mobile_phone_number')->nullable();
            $table->integer('spielgruppen_id')->index()->nullable();
            $table->integer('semester_id');
            $table->integer('membership_id')->nullable();
            $table->longText('note');
            $table->string('place_id');
            $table->integer('company_id')->index();
            $table->integer('parent_admin_id')->nullable();
            $table->integer('session_id')->index()->nullable();
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
        Schema::dropIfExists('children_registrations');
    }
}
