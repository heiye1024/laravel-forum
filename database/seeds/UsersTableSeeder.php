<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // 獲取 Faker 實例
        $faker = app(Faker\Generator::class);

        // 頭像假數據
        $avatars = [
            'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?f=y',
            'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=identicon&f=y',
            'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=monsterid&f=y',
            'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=wavatar&f=y',
            'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=retro&f=y',
            'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=robohash&f=y',
        ];

        // 產生數據集合
        $users = factory(User::class)->times(10)->make()->each(function ($user, $index) use ($faker, $avatars) {
            // 從頭像陣列中隨機取出一個並且給予值
            $user->avatar = $faker->randomElement($avatars);
        });

        // 讓隱藏字段可見，並將數據集合轉換為陣列，確保進入資料庫時不會報錯
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 新增到資料庫中
        User::insert($user_array);

        // 單獨處理第一個使用者的資料
        $user = User::find(1);
        $user->name = 'Sian';
        $user->email = 'sianchou@test.com';
        $user->avatar = 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y';
        $user->save();
        // 初始化使用者角色，將1號使用者指派為「站長」
        $user->assignRole('Founder');

        // 將2號使用者指派為「管理員」
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
