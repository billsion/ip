<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth', function(Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false)->comment('应用名称');
            $table->string('app_key', 255)->nullable(false)->comment('应用key');
            $table->string('app_secret', 255)->nullable(false)->comment('应用密钥');
            $table->string('memo', 255)->default('')->nullable(true)->comment('备注');
            $table->boolean('enabled', 2)->default(1)->nullable(false)->comment('是否开启');

            $table->timestamp('created_at')->nullable(false);
            $table->timestamp('updated_at')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth');
    }
};
