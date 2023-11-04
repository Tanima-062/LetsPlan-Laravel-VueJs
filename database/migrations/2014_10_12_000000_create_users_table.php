<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('image')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->integer('gender_id')->comment('1 for male, 2 for female')->nullable();
            $table->integer('role_id')->index();
            $table->boolean('status_id')->nullable();
            $table->string('street_name')->nullable();
            $table->string('house_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('prefix')->nullable();
            $table->integer('phone_number')->nullable();
            $table->string('location')->nullable();
            $table->integer('parent_admin_id')->nullable()->default(null);
            $table->integer('company_id')->index();
            $table->boolean('email_notifications')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
