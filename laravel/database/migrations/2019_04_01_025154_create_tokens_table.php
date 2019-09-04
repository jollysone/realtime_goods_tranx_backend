<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokensTable extends Migration
{
    private $tableName    = 'tokens';
    private $tableComment = '用户登录标识';

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
            $table->tinyInteger('app_type')->default(0)->comment('应用类型');
            $table->string('user_id', 32)->default('')->comment('用户');
            $table->string('token')->default('')->comment('标识内容');
            $table->string('ip', 15)->default('')->comment('登录 IP');
            $table->timestamp('expire_at')->nullable()->default(null)->comment('过期时间');
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
