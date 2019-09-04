<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIllegalLogsTable extends Migration
{
    private $tableName    = 'illegal_logs';
    private $tableComment = '违规记录';

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
            $table->string('user_id', 32)->default('')->comment('用户');
            $table->string('goods_id', 32)->default('')->comment('商品');
            $table->tinyInteger('type')->default(0)->comment('类型');
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
