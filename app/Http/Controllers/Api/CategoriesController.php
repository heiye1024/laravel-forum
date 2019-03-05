<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index()
    {
        // 分類數據是集合，所以使用 $this->response->collection 返回數據
        return $this->response->collection(Category::all(), new CategoryTransformer());
    }
}
