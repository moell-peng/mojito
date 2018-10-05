<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomFieldPermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->integer('pg_id')->default(0);
            $table->string('display_name', 50)->nullable();
            $table->string('icon', 30)->nullable();
            $table->smallInteger('sequence')->nullable();
            $table->string('created_name', 50)->nullable();
            $table->string('updated_name', 50)->nullable();
            $table->string('description')->nullable();
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropColumn('display_name');
            $table->dropColumn('icon');
            $table->dropColumn('sequence');
            $table->dropColumn('created_name');
            $table->dropColumn('updated_name');
            $table->dropColumn('description');
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
