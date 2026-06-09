@extends('layouts.app')

@section('title', '编辑个人资料')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-10 col-lg-8 col-xl-6">
      <div class="card">
        <div class="card-header">
          编辑个人资料
        </div>

        <div class="card-body">
          <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('shared._error')

            <div class="row mb-3">
              <label for="name-field" class="col-md-3 col-form-label text-md-end">用户名</label>
              <div class="col-md-9">
                <input class="form-control" type="text" name="name" id="name-field"
                  value="{{ old('name', $user->name) }}">
              </div>
            </div>

            <div class="row mb-3">
              <label for="email-field" class="col-md-3 col-form-label text-md-end">邮 箱</label>
              <div class="col-md-9">
                <input class="form-control" type="email" name="email" id="email-field"
                  value="{{ old('email', $user->email) }}">
              </div>
            </div>

            <div class="row mb-3">
              <label for="introduction-field" class="col-md-3 col-form-label text-md-end">个人简介</label>
              <div class="col-md-9">
                <textarea name="introduction" id="introduction-field" class="form-control" rows="3">{{ old('introduction', $user->introduction) }}</textarea>
              </div>
            </div>

            <div class="row mb-3">
              <label for="avatar-field" class="col-md-3 col-form-label text-md-end avatar-label">用户头像</label>
              <div class="col-md-9">
                <input type="file" name="avatar" id="avatar-field" class="form-control">
                @if ($user->avatar)
                  <img class="img-thumbnail mt-3" src="{{ $user->avatar }}" width="200" alt="用户头像">
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-md-9 offset-md-3">
                <button type="submit" class="btn btn-primary">保存</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
