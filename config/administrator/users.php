<?php

use App\Models\User;

return [
    // 頁面標題
    'title'   => '使用者',

    // 模型單數，用作頁面「新建 $single」
    'single'  => '使用者',

    // 數據模型，用作數據的 CRUD
    'model'   => User::class,

    // 設置當前頁面的訪問權限，通過返回布林值來控制權限
    // 返回 True 及通過權限驗證，False則無權訪問並從 Menu 中隱藏
    'permission'=> function()
    {
        return Auth::user()->can('manage_users');
    },

    // 欄負責渲染「數據表格」，由無數的「列」組成
    'columns' => [

        // 列的標示，這是一個最小化「列」資訊配置的例子，讀取的是模型裡對應的屬性的值，如 $model->id
        'id',

        'avatar' => [
            // 數據表格裡列的名稱，默認會使用「列標示」
            'title'  => '頭像',

            // 默認情況下會直接輸出數據，你也可以使用 output 選項來訂製輸出內容
            'output' => function ($avatar, $model) {
                return empty($avatar) ? 'N/A' : '<img src="'.$avatar.'" width="40">';
            },

            // 是否允許排序
            'sortable' => false,
        ],

        'name' => [
            'title'    => '使用者名稱',
            'sortable' => false,
            'output' => function ($name, $model) {
                return '<a href="/users/'.$model->id.'" target=_blank>'.$name.'</a>';
            },
        ],

        'email' => [
            'title' => '電子郵件',
        ],

        'operation' => [
            'title'  => '管理',
            'sortable' => false,
        ],
    ],

    // 「模型表單」設置項
    'edit_fields' => [
        'name' => [
            'title' => '使用者名稱',
        ],
        'email' => [
            'title' => '電子郵件',
        ],
        'password' => [
            'title' => '密碼',

            // 表單使用 input 類型 password
            'type' => 'password',
        ],
        'avatar' => [
            'title' => '使用者頭像',

            // 設置表單的類型，默認的 type 是 input
            'type' => 'image',

            // 圖片上傳必須設置圖片存放路徑
            'location' => public_path() . '/uploads/images/avatars/',
        ],
        'roles' => [
            'title' => '使用者角色',

            // 指定數據的類型為關聯模型
            'type' => 'relationship',

            // 關聯模型的欄位，用來做關聯顯示
            'name_field' => 'name',
        ],
    ],

    // 「數據過濾」設置
    'filters' => [
        'id' => [
            // 過濾表單顯示名稱
            'title' => '使用者 ID',
        ],
        'name' => [
            'title' => '使用者名稱',
        ],
        'email' => [
            'title' => '電子郵件',
        ],
    ],
];
