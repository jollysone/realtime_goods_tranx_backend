<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsTable extends Migration
{
    private $tableName    = 'credits';
    private $tableComment = '信用分';

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
            $table->integer('base_score')->default(0)->comment('基础信用分');
            $table->integer('buy_score')->default(0)->comment('购买信用分');
            $table->integer('sell_score')->default(0)->comment('卖出信用分');
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
