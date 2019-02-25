<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferences extends Migration
{

    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            // 當 user_id  對應的 users 資料表數據被刪除時，刪除此條數據
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('replies', function (Blueprint $table) {
            // 當 user_id  對應的 users 資料表數據被刪除時，刪除此條數據
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // 當 topic_id  對應的 topics 資料表數據被刪除時，刪除此條數據
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            // 移除外鍵約束
            $table->dropForeign(['user_id']);
        });

        Schema::table('replies', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['topic_id']);
        });

    }
}
