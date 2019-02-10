@extends('layouts.app')
@section('title', $user->name . ' 的個人中心')

@section('content')
<div class="row">
  <div class="col-lg-3 col-md-3 hidden-sm hidden-xs user-info">
    <div class="card ">
      <img class="card-img-top" src="https://iocaffcdn.phphub.org/uploads/images/201709/20/1/PtDKbASVcz.png?imageView2/1/w/600/h/600" alt="{{ $user->name}}">
      <div class="card-body">
        <h5><strong>個人簡介</strong></h5>
        <p>{{$user->introduction}}</p>
        <hr>
        <h5><strong>註冊於</strong></h5>
        <p>{{$user->created_at->diffForHumans()}}</p>
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
    <div class="card ">
      <div class="card-body">
        暫時無資料
      </div>
    </div>
  </div>
</div>
@stop
