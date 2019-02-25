<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Auth;
use App\Handlers\ImageUploadHandler;
use App\Models\User;
use App\Models\Link;

class TopicsController extends Controller
{
    public function __construct()
    {
        // 除了 index() 和 show() 以外的方法使用 auth middleware 進行認證
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request, Topic $topic, User $user, Link $link)
	{

		$topics = $topic->withOrder($request->order)->paginate(20);
        $active_users = $user->getActiveUsers();
        //dd($active_users);
        $links = $link->getAllCached();
		return view('topics.index', compact('topics', 'active_users', 'links'));
	}

    public function show(Request $request, Topic $topic)
    {
        // 如果 Slug 不為空，且Slug不等於請求的路由參數Slug
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            // 301 永久重新轉址到正確的URL上面
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function store(TopicRequest $request, Topic $topic)
	{
		$topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

		//return redirect()->route('topics.show', $topic->id)->with('message', '發文成功');
        return redirect()->to($topic->link())->with('success', '發文成功');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
        $topic->update($request->all());

        //return redirect()->route('topics.show', $topic->id)->with('success', '更新成功');
        return redirect()->to($topic->link())->with('success', '更新成功');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', '刪除成功');
	}

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化傳回資料，默認是失敗的
        $data = [
            'success' => false,
            'msg' => '上傳失敗!',
            'file_path' => ''
        ];
        // 判斷是否有上傳文件，並且傳值給 $file
        if ($file = $request->upload_file) {
            // 儲存圖片到本機端
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 圖片儲存成功的話
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg'] = "上傳成功!";
                $data['success'] = true;
            }
        }
        return $data;
    }
}
