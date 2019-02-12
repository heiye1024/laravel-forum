<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
  <div class="container">
    <!-- Branding Image -->
    <a class="navbar-brand " href="{{url('/')}}">
      Laravel-Forum
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto">
        <li class="nav-item {{active_class(if_route('topics.index'))}}"><a class="nav-link" href="{{route('topics.index')}}">主題</li>
        <li class="nav-item {{category_nav_active(1)}}"><a class="nav-link" href="{{route('categories.show', 1)}}">分享</li>
        <li class="nav-item {{category_nav_active(2)}}"><a class="nav-link" href="{{route('categories.show', 2)}}">教學</li>
        <li class="nav-item {{category_nav_active(3)}}"><a class="nav-link" href="{{route('categories.show', 3)}}">問答</li>
        <li class="nav-item {{category_nav_active(4)}}"><a class="nav-link" href="{{route('categories.show', 4)}}">公告</li>
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav navbar-right">
        <!-- Authentication Links -->
        @guest
          <li class="nav-item"><a class="nav-link" href="{{route('login')}}">登入</a></li>
          <li class="nav-item"><a class="nav-link" href="{{route('register')}}">註冊</a></li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="{{Auth::user()->avatar}}" class="img-responsive img-circle" width="30px" height="30px">
              {{Auth::user()->name}}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{route('users.show', Auth::id())}}">個人中心</a>
              <a class="dropdown-item" href="{{route('users.edit', Auth::id())}}">編輯資料</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" id="logout" href="#">
                <form action="{{route('logout')}}" method="POST">
                  {{csrf_field()}}
                  <button class="btn btn-block btn-danger" type="submit" name="button">登出</button>
                </form>
              </a>
            </div>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
