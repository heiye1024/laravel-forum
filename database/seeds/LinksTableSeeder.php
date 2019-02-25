<?php

use Illuminate\Database\Seeder;
use App\Models\Link;

class LinksTableSeeder extends Seeder
{
    public function run()
    {
        // 產生數據集合
        $links = factory(Link::class)->times(6)->make();

        // 將數據集合轉換維陣列，並新增到資料庫中
        Link::insert($links->toArray());
    }
}
