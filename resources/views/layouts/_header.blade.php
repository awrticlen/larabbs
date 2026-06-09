<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">LaraBBS</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        @guest
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">登录</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">注册</a>
          </li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
              role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="{{ asset('images/default-avatar.svg') }}"
                class="rounded-circle me-2" width="30" height="30" alt="{{ Auth::user()->name }}">
              {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="{{ route('users.show', Auth::id()) }}">个人中心</a></li>
              <li><a class="dropdown-item" href="{{ route('users.edit', Auth::id()) }}">编辑资料</a></li>
              <li><hr class="dropdown-divider"></li>
              <li class="px-3 py-2">
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button class="btn btn-danger w-100" type="submit" name="button">退出</button>
                </form>
              </li>
            </ul>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
