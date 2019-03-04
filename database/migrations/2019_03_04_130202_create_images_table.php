<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            // type可分為'avatar'和'topic'，分別用於使用者頭像和主題中的圖片
            // 要分type是因為不同type的圖片有不同的尺寸，以及不同的文件目錄
            // 修改個人頭像所使用的image必須為avatar類型
            $table->string('type')->index();
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
}
