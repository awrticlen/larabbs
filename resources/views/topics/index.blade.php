@extends('layouts.app')

@section('title', 'Topics')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Topic</h1>
    @auth
      <a class="btn btn-primary" href="{{ route('topics.create') }}">Create</a>
    @endauth
  </div>

  @if ($topics->count())
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Body</th>
            <th>User Id</th>
            <th>Category Id</th>
            <th>Reply Count</th>
            <th>View Count</th>
            <th>Last Reply User Id</th>
            <th>Order</th>
            <th>Excerpt</th>
            <th>Slug</th>
            <th>OPTIONS</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($topics as $topic)
            <tr>
              <td>{{ $topic->id }}</td>
              <td>{{ $topic->title }}</td>
              <td>{{ Str::limit($topic->body, 30) }}</td>
              <td>{{ $topic->user_id }}</td>
              <td>{{ $topic->category_id }}</td>
              <td>{{ $topic->reply_count }}</td>
              <td>{{ $topic->view_count }}</td>
              <td>{{ $topic->last_reply_user_id }}</td>
              <td>{{ $topic->order }}</td>
              <td>{{ Str::limit($topic->excerpt, 20) }}</td>
              <td>{{ $topic->slug }}</td>
              <td class="text-nowrap">
                <a class="btn btn-sm btn-secondary" href="{{ route('topics.show', $topic) }}">V</a>
                @auth
                  <a class="btn btn-sm btn-primary" href="{{ route('topics.edit', $topic) }}">E</a>
                  <form action="{{ route('topics.destroy', $topic) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('确定删除？')">D</button>
                  </form>
                @endauth
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    {{ $topics->links() }}
  @else
    <p class="text-muted mb-0">Empty!</p>
  @endif
@endsection
