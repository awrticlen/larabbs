<div class="card">
    <div class="card-body">
        @auth
            <a href="{{ route('topics.create') }}" class="btn btn-success w-100">
                <i class="fa-solid fa-pen me-2"></i> 新建帖子
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-success w-100">
                <i class="fa-solid fa-pen me-2"></i> 新建帖子
            </a>
        @endauth
    </div>
</div>
