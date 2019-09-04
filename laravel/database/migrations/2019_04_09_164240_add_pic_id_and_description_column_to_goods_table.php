<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPicIdAndDescriptionColumnToGoodsTable extends Migration
{
    private $tableName = 'goods';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->text('description')->nullable()->after('hot_degree')->comment('描述');
            $table->string('pic_id', 32)->default('')->after('hot_degree')->comment('图片');
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
            $table->dropColumn('description');
            $table->dropColumn('pic_id');
        });
    }
}
