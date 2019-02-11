<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCategoriesData extends Migration
{
    public function up()
    {
        $categories = [
            [
                'name'        => '分享',
                'description' => '分享創造，分享發現',
            ],
            [
                'name'        => '教學',
                'description' => '開發技巧、推薦套件等',
            ],
            [
                'name'        => '問答',
                'description' => '保持友善，互相幫助',
            ],
            [
                'name'        => '公告',
                'description' => '站內公告',
            ],
        ];

        DB::table('categories')->insert($categories);
    }

    public function down()
    {
        DB::table('categories')->truncate();
    }
}
