@extends('layouts.app')

@section('title', '无权限访问')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
      <div class="card">
        <div class="card-body p-4">
          @auth
            <div class="alert alert-danger text-center mb-0">
              当前登录账号无后台访问权限。
            </div>
          @else
            <div class="alert alert-danger text-center">
              请登录以后再操作。
            </div>

            <a class="btn btn-lg btn-primary w-100" href="{{ route('login') }}">
              登 录
            </a>
          @endauth
        </div>
      </div>
    </div>
  </div>
@endsection