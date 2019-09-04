<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    private $tableName    = 'users';
    private $tableComment = '用户';

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
            $table->string('number', 10)->unique()->comment('编号');
            $table->string('phone', 11)->default('')->comment('手机');
            $table->string('password')->default('')->comment('密码');
            $table->string('true_name', 10)->default('')->comment('真实姓名');
            $table->tinyInteger('gender')->default(0)->comment('性别');
            $table->string('avatar_id', 32)->default('')->comment('头像');
            $table->string('email')->default('')->comment('邮箱');
            $table->string('department_id', 32)->default('')->comment('系别');
            $table->string('grade_id', 32)->default('')->comment('年级');
            $table->string('nick')->default('')->comment('昵称');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->integer('role_type')->default(0)->comment('角色类型');
            $table->string('label_id', 32)->default('')->comment('标签');
            $table->timestamp('login_at')->default(null)->nullable()->comment('最后登录');
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
