<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomFieldPermissionTestTables extends Migration
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
            $table->smallInteger('sequence')->default(0);
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
            $table->dropColumn([
                'pg_id',
                'display_name',
                'icon',
                'sequence',
                'created_name',
                'updated_name',
                'description'
            ]);
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
