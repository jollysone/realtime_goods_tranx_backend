<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    private $tableName    = 'orders';
    private $tableComment = '订单';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('sn', 32)->unique();
            $table->string('buyer_id', 32)->default('')->comment('买家');
            $table->string('seller_id', 32)->default('')->comment('卖家');
            $table->string('goods_id', 32)->default('')->comment('商品');
            $table->integer('amount')->default(0)->comment('数量');
            $table->decimal('price', 6, 2)->default(0)->comment('金额');
            $table->tinyInteger('status')->default(0)->comment('状态');
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
