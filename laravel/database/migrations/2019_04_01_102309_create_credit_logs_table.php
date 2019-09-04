<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditLogsTable extends Migration
{
    private $tableName    = 'credit_logs';
    private $tableComment = '信用分记录';

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
            $table->integer('old_score')->default(0)->comment('原信用分');
            $table->integer('change_score')->default(0)->comment('变更信用分');
            $table->string('remark', 50)->default('')->comment('原因');
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
