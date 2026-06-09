@extends('layouts.app')

@section('title', $user->name . ' 的个人中心')

@section('content')
  <div class="row">
    <div class="col-md-3 d-none d-md-block user-info">
      <div class="card">
        <img class="card-img-top"
          src="{{ asset('images/default-avatar.svg') }}"
          alt="{{ $user->name }}">
        <div class="card-body">
          <h5 class="card-title"><strong>个人简介</strong></h5>
          <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
          <hr>
          <h5 class="card-title"><strong>注册于</strong></h5>
          <p class="card-text mb-0">{{ $user->created_at->format('Y-m-d') }}</p>
        </div>
      </div>
    </div>

    <div class="col-lg-9 col-md-9 col-12">
      <div class="card">
        <div class="card-body">
          <h1 class="h4 mb-0">
            {{ $user->name }}
            <small class="text-muted fs-6">{{ $user->email }}</small>
          </h1>
        </div>
      </div>

      <hr class="my-3">

      <div class="card">
        <div class="card-body">
          暂无数据 ~_~
        </div>
      </div>
    </div>
  </div>
@endsection
