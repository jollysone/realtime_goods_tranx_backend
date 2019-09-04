<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    private $tableName    = 'files';
    private $tableComment = '文件';

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
            $table->string('path', 300)->default('')->comment('路径');
            $table->string('thumb_path', 300)->default('')->comment('缩略图');
            $table->string('extension', 10)->default('')->comment('扩展名');
            $table->integer('size')->default(0)->comment('大小');
            $table->unsignedInteger('thumb_size')->default(0)->comment('缩略图大小');
            $table->string('remark', 50)->default('')->comment('备注');
            $table->string('ip', 15)->default('')->comment('上传 IP');
            $table->string('uploaded_by', 32)->default('')->comment('上传用户');
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
