<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdAndLevelColumnToCategoriesTable extends Migration
{
    private $tableName = 'categories';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->integer('level')->default(0)->after('id')->comment('层级');
            $table->string('parent_id', 32)->default('')->after('id')->comment('上级');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('level');
            $table->dropColumn('parent_id');
        });
    }
}
