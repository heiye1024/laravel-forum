@extends('layouts.app')
@section('title', $user->name . ' 的個人中心')

@section('content')
<div class="row">
  <div class="col-lg-3 col-md-3 hidden-sm hidden-xs user-info">
    <div class="card ">
      <img class="card-img-top" src="{{$user->avatar}}" alt="{{ $user->name}}">
      <div class="card-body">
        <h5><strong>個人簡介</strong></h5>
        <p>{{$user->introduction}}</p>
        <hr>
        <h5><strong>註冊於</strong></h5>
        <p>{{$user->created_at->diffForHumans()}}</p>
        <hr>
        <h5><strong>最後登入時間</strong></h5>
        <p title="{{$user->last_actived_at}}">{{$user->last_actived_at->diffForHumans()}}</p>
      </div>
    </div>
  </div>
  <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
    <div class="card ">
      <div class="card-body">
          <h1 class="mb-0" style="font-size:22px;">{{$user->name}} <small>{{$user->email}}</small></h1>
      </div>
    </div>
    <hr>
    {{--使用者發佈的內容--}}
    <div class="card ">
      <div class="card-body">
        <ul class="nav nav-tabs">
          <li class="nav-item"><a class="nav-link active bg-transparent {{active_class(if_query('tab', null))}}" href="{{route('users.show', $user->id)}}">我的主題</a></li>
          <li class="nav-item"><a class="nav-link bg-transparent {{active_class(if_query('tab', 'replies'))}}" href="{{route('users.show', [$user->id, 'tab' => 'replies'])}}">我的回覆</a></li>
        </ul>
        @if (if_query('tab', 'replies'))
          @include('users._replies', ['replies' => $user->replies()->with('topic')->recent()->paginate(5)])
        @else
          @include('users._topics', ['topics' => $user->topics()->recent()->paginate(5)])
        @endif
      </div>
    </div>
  </div>
</div>
@stop
