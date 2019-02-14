<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        // 所有使用者 ID 陣列，如：[1,2,3,4]
        $user_ids = User::all()->pluck('id')->toArray();

        // 所有主題 ID 陣列，如：[1,2,3,4]
        $topic_ids = Topic::all()->pluck('id')->toArray();

        $faker = app(Faker\Generator::class);

        $replys = factory(Reply::class)->times(1000)->make()->each(function ($reply, $index) use ($user_ids, $topic_ids, $faker) {
            // 從使用者 ID 陣列中隨機取出一個並且給值
            $reply->user_id = $faker->randomElement($user_ids);

            // 主題 ID，同上
            $reply->topic_id = $faker->randomElement($topic_ids);
        });

        // 將資料集合轉換為陣列，並且插入到資料庫中
        Reply::insert($replys->toArray());
    }

}

