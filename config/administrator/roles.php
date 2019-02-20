<?php

use Spatie\Permission\Models\Role;

return [
    'title'   => '角色',
    'single'  => '角色',
    'model'   => Role::class,

    'permission'=> function()
    {
        return Auth::user()->can('manage_users');
    },

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title' => '標誌'
        ],
        'permissions' => [
            'title'  => '權限',
            'output' => function ($value, $model) {
                $model->load('permissions');
                $result = [];
                foreach ($model->permissions as $permission) {
                    $result[] = $permission->name;
                }

                return empty($result) ? 'N/A' : implode($result, ' | ');
            },
            'sortable' => false,
        ],
        'operation' => [
            'title'  => '管理',
            'output' => function ($value, $model) {
                return $value;
            },
            'sortable' => false,
        ],
    ],

    'edit_fields' => [
        'name' => [
            'title' => '標誌',
        ],
        'permissions' => [
            'type' => 'relationship',
            'title' => '權限',
            'name_field' => 'name',
        ],
    ],

    'filters' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title' => '標誌',
        ]
    ],

    // 新建和編輯時的表單驗證規則
    'rules' => [
        'name' => 'required|max:15|unique:roles,name',
    ],

    // 表單驗證錯誤時訂製錯誤消息
    'messages' => [
        'name.required' => '標誌不能為空',
        'name.unique' => '標誌已經存在',
    ]
];