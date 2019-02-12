@extends('layouts.app')

@section('title', isset($category) ? $category->name : '主題列表')

@section('content')
<div class="row mb-5">
  <div class="col-lg-9 col-md-9 topic-list">
    @if (isset($category))
      <div class="alert alert-info" role="alert">
        {{$category->name}}：{{$category->description}}
      </div>
    @endif

    <div class="card ">
      <div class="card-header bg-transparent">
        <ul class="nav nav-pills">
          <li class="nav-item"><a class="nav-link active" href="#">最後回覆</a></li>
          <li class="nav-item"><a class="nav-link" href="#">最新發佈</a></li>
        </ul>
      </div>

      <div class="card-body">
        {{-- 主題列表 --}}
        @include('topics._topic_list', ['topics' => $topics])
        {{-- 分頁：為了後面要做排序功能，該功能使用URL傳遞參數來實現，所以使用分頁中的appends()方法使URI中的請求參數得到繼承 --}}
        <div class="mt-5">
          {!! $topics->appends(Request::except('page'))->render() !!}
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-3 sidebar">
    @include('topics._sidebar')
  </div>
</div>

@endsection
