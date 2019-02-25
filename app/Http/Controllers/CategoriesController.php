<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;
use App\Models\User;
use App\Models\Link;

class CategoriesController extends Controller
{
    public function show(Category $category, Request $request, Topic $topic, User $user, Link $link)
    {
        // 讀取分類 ID 關聯的主題，並且每 20 條為一個分頁
        $topics = $topic->withOrder($request->order)->where('category_id', $category->id)->paginate(20);

        // 活躍使用者列表
        $active_users = $user->getActiveUsers();

        // 資源連結
        $links = $link->getAllCached();

        // 傳遞主題和分類的變數到模板中
        return view('topics.index', compact('topics', 'category', 'active_users', 'links'));
    }
}
