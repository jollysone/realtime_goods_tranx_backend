<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    private $tableName    = 'goods';
    private $tableComment = '商品';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('ai');
            $table->string('id', 32)->unique();
            $table->string('number', 20)->default('')->comment('编号');
            $table->string('title', 40)->default('')->comment('标题');
            $table->decimal('price', 6, 2)->default(0)->comment('价格');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->string('category_id', 32)->default('')->comment('类型');
            $table->integer('hot_degree')->default(0)->comment('热度');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `$this->tableName` comment '$this->tableComment'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
