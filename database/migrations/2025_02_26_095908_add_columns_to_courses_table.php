<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $courses_table_name = config('master-genesis.COURSES_TABLE_NAME', 'courses');

        Schema::table($courses_table_name, function (Blueprint $table) use ($courses_table_name) {
            //department_token
            if (!Schema::hasColumn($courses_table_name, 'department_token')) {
                $table->string('department_token')->nullable()->default(null);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $courses_table_name = config('master-genesis.COURSES_TABLE_NAME', 'courses');
        Schema::table($courses_table_name, function (Blueprint $table) use ($courses_table_name) {
            //
            $table->removeColumn('department_token');
        });
    }
}
