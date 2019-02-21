<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{csrf_token()}}">

  <title>@yield('title', 'Laravel-Forum') - {{setting('site_name', 'Laravel 論壇系統')}}</title>
  <meta name="description" content="@yield('description', setting('seo_description', 'Laravel 愛好者社群'))" />
  <meta name="keyword" content="@yield('keyword', setting('seo_keyword', 'Laravel-Forum, 論壇, 開發者論壇'))" />

  <!-- Styles -->
  <link href="{{mix('css/app.css')}}" rel="stylesheet">
  @yield('styles')
</head>

<body>
  <div id="app" class="{{route_class()}}-page">

    @include('layouts._header')
    <div class="container">
      @include('shared._messages')
      @yield('content')
    </div>
    @include('layouts._footer')
  </div>

  @if (app()->isLocal())
    @include('sudosu::user-selector')
  @endif

  <!-- Scripts -->
  <script src="{{mix('js/app.js')}}"></script>
  @yield('scripts')
</body>
</html>
