<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">LaraBBS</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        @guest
          @if (Route::has('login'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">登录</a>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link" href="#">登录</a>
            </li>
          @endif
          @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">注册</a>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link" href="#">注册</a>
            </li>
          @endif
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              @if (Route::has('logout'))
                <li>
                  <a class="dropdown-item" href="#" id="logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">退出登录</a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              @endif
            </ul>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
