@extends('layouts.app')

@section('title', $topic->exists ? 'Edit Topic #' . $topic->id : 'Create Topic')

@section('content')
  <div class="card">
    <div class="card-header">
      Topic /
      @if ($topic->exists)
        Edit #{{ $topic->id }}
      @else
        Create
      @endif
    </div>
    <div class="card-body">
      @if ($topic->exists)
        <form action="{{ route('topics.update', $topic) }}" method="POST">
          @csrf
          @method('PUT')
      @else
        <form action="{{ route('topics.store') }}" method="POST">
          @csrf
      @endif

      @include('common.error')

      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" id="title" class="form-control"
          value="{{ old('title', $topic->title) }}">
      </div>

      <div class="mb-3">
        <label for="body" class="form-label">Body</label>
        <textarea name="body" id="body" class="form-control" rows="5">{{ old('body', $topic->body) }}</textarea>
      </div>

      <div class="mb-3">
        <label for="user_id" class="form-label">User Id</label>
        <input type="number" name="user_id" id="user_id" class="form-control"
          value="{{ old('user_id', $topic->user_id) }}">
      </div>

      <div class="mb-3">
        <label for="category_id" class="form-label">Category Id</label>
        <input type="number" name="category_id" id="category_id" class="form-control"
          value="{{ old('category_id', $topic->category_id) }}">
      </div>

      <div class="mb-3">
        <label for="reply_count" class="form-label">Reply Count</label>
        <input type="number" name="reply_count" id="reply_count" class="form-control"
          value="{{ old('reply_count', $topic->reply_count ?? 0) }}">
      </div>

      <div class="mb-3">
        <label for="view_count" class="form-label">View Count</label>
        <input type="number" name="view_count" id="view_count" class="form-control"
          value="{{ old('view_count', $topic->view_count ?? 0) }}">
      </div>

      <div class="mb-3">
        <label for="last_reply_user_id" class="form-label">Last Reply User Id</label>
        <input type="number" name="last_reply_user_id" id="last_reply_user_id" class="form-control"
          value="{{ old('last_reply_user_id', $topic->last_reply_user_id ?? 0) }}">
      </div>

      <div class="mb-3">
        <label for="order" class="form-label">Order</label>
        <input type="number" name="order" id="order" class="form-control"
          value="{{ old('order', $topic->order ?? 0) }}">
      </div>

      <div class="mb-3">
        <label for="excerpt" class="form-label">Excerpt</label>
        <textarea name="excerpt" id="excerpt" class="form-control" rows="3">{{ old('excerpt', $topic->excerpt) }}</textarea>
      </div>

      <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" name="slug" id="slug" class="form-control"
          value="{{ old('slug', $topic->slug) }}">
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn btn-secondary" href="{{ route('topics.index') }}">&larr; Back</a>
      </div>
      </form>
    </div>
  </div>
@endsection
