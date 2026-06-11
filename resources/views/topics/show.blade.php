@extends('layouts.app')

@section('title', 'Topic #' . $topic->id)

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Topic / Show #{{ $topic->id }}</span>
      <div>
        <a class="btn btn-sm btn-secondary" href="{{ route('topics.index') }}">&larr; Back</a>
        @auth
          <a class="btn btn-sm btn-primary" href="{{ route('topics.edit', $topic) }}">Edit</a>
        @endauth
      </div>
    </div>
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-3">Title</dt>
        <dd class="col-sm-9">{{ $topic->title }}</dd>
        <dt class="col-sm-3">Body</dt>
        <dd class="col-sm-9">{{ $topic->body }}</dd>
        <dt class="col-sm-3">User Id</dt>
        <dd class="col-sm-9">{{ $topic->user_id }}</dd>
        <dt class="col-sm-3">Category Id</dt>
        <dd class="col-sm-9">{{ $topic->category_id }}</dd>
        <dt class="col-sm-3">Reply Count</dt>
        <dd class="col-sm-9">{{ $topic->reply_count }}</dd>
        <dt class="col-sm-3">View Count</dt>
        <dd class="col-sm-9">{{ $topic->view_count }}</dd>
        <dt class="col-sm-3">Last Reply User Id</dt>
        <dd class="col-sm-9">{{ $topic->last_reply_user_id }}</dd>
        <dt class="col-sm-3">Order</dt>
        <dd class="col-sm-9">{{ $topic->order }}</dd>
        <dt class="col-sm-3">Excerpt</dt>
        <dd class="col-sm-9">{{ $topic->excerpt }}</dd>
        <dt class="col-sm-3">Slug</dt>
        <dd class="col-sm-9">{{ $topic->slug }}</dd>
      </dl>
    </div>
  </div>
@endsection
