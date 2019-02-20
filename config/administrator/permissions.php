<?php

use Spatie\Permission\Models\Permission;

return [
    'title'   => '權限',
    'single'  => '權限',
    'model'   => Permission::class,

    'permission' => function () {
        return Auth::user()->can('manage_users');
    },

    // 對 CRUD 動作的單獨權限控制，通過返回布林值來控制權限
    'action_permissions' => [
        // 控制「新建按鈕」的顯示
        'create' => function ($model) {
            return true;
        },
        // 允許更新
        'update' => function ($model) {
            return true;
        },
        // 不允許刪除
        'delete' => function ($model) {
            return false;
        },
        // 允許查看
        'view' => function ($model) {
            return true;
        },
    ],

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title'    => '標誌',
        ],
        'operation' => [
            'title'    => '管理',
            'sortable' => false,
        ],
    ],

    'edit_fields' => [
        'name' => [
            'title' => '標誌（請慎重修改）',

            // 表單項目標題旁的「提示訊息」
            'hint' => '修改權限標誌會影響程式碼的運作，請不要輕易更改'
        ],
        'roles' => [
            'type' => 'relationship',
            'title' => '角色',
            'name_field' => 'name',
        ],
    ],

    'filters' => [
        'name' => [
            'title' => '標誌',
        ],
    ],
];