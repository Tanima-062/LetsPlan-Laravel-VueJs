<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->text('uniq_id')->nullable()->after('name');
            $table->text('first_name')->nullable()->after('uniq_id');
            $table->text('last_name')->nullable()->after('first_name');
            $table->text('telephone')->nullable()->after('last_name');
            $table->text('email')->nullable()->after('telephone');
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('uniq_id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('telephone');
            $table->dropColumn('email');
            $table->dropColumn('status');
        });
    }
}
