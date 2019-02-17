<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 取得登入使用者的所有通知
        $notifications = Auth::user()->notifications()->paginate(20);
        // 標示為已讀，未讀數量為零
        Auth::user()->markAsRead();
        return view('notifications.index', compact('notifications'));
    }
}
