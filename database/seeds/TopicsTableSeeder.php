<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        // 所有使用者 ID 陣列，如：[1,2,3,4]
        $user_ids = User::all()->pluck('id')->toArray();

        // 所有分類 ID 陣列，如：[1,2,3,4]
        $category_ids = Category::all()->pluck('id')->toArray();

        // 取得 Faker 實例
        $faker = app(Faker\Generator::class);

        // use 是 PHP 中匿名函數提供的local變數傳遞機制，匿名函數必須通過use聲明的引用，才能使用local變數
        $topics = factory(Topic::class)->times(100)->make()->each(function ($topic, $index) use ($user_ids, $category_ids, $faker)
        {
            // 從使用者 ID 陣列中隨機取出一個並且給值
            $topic->user_id = $faker->randomElement($user_ids);

            // 主題分類，同上
            $topic->category_id = $faker->randomElement($category_ids);
        });

        // 將資料集合轉換成陣列，並且新增到資料庫中
        Topic::insert($topics->toArray());
    }

}

