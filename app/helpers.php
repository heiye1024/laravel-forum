<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function category_nav_active($category_id)
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
}

// config/administrator/topics.php
function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

// config/administrator/topics.php
function model_link($title, $model, $prefix = '')
{
    // 獲取數據模型的複數底線命名
    $model_name = model_plural_name($model);

    // 初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';

    // 使用網站 URL 拼接全量 URL
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    // 拼接 HTML A 標籤，並返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

// config/administrator/topics.php
function model_plural_name($model)
{
    // 從實體中獲取完整類別名稱，例如：App\Models\User
    $full_class_name = get_class($model);

    // 取得基礎類別名稱，例如：傳遞參數`App\Models\User`會得到`User`
    $class_name = class_basename($full_class_name);

    // 底線命名，例如：傳遞參數`User`會得到`user`，`FooBar`會得到`foo_bar`
    $snake_case_name = snake_case($class_name);

    // 取得子串的複數形式，例如：傳遞參數`user`會得到`users`
    return str_plural($snake_case_name);
}
