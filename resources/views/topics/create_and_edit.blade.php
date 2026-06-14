@extends('layouts.app')

@section('title', $topic->exists ? '编辑话题' : '新建话题')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <h2 class="h4 mb-0">
                        <i class="fa-regular fa-pen-to-square me-2"></i>
                        @if ($topic->exists)
                            编辑话题
                        @else
                            新建话题
                        @endif
                    </h2>

                    <hr>

                    @if ($topic->exists)
                        <form action="{{ route('topics.update', $topic) }}" method="POST">
                            @csrf
                            @method('PUT')
                        @else
                            <form action="{{ route('topics.store') }}" method="POST">
                                @csrf
                    @endif

                    @include('shared._error')

                    <div class="mb-3">
                        <input class="form-control" type="text" name="title" value="{{ old('title', $topic->title) }}"
                            placeholder="请填写标题" required>
                    </div>

                    <div class="mb-3">
                        <select class="form-select" name="category_id" required>
                            <option value="" disabled {{ old('category_id', $topic->category_id) ? '' : 'selected' }}>
                                请选择分类
                            </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $topic->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <textarea name="body" class="form-control" id="editor" rows="6" placeholder="请填入至少三个字符的内容。" required>{{ old('body', $topic->body) }}</textarea>
                    </div>

                    <div class="border rounded p-3 bg-light">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-regular fa-floppy-disk me-2" aria-hidden="true"></i> 保存
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/simditor.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/module.js') }}"></script>
    <script src="{{ asset('js/hotkeys.js') }}"></script>
    <script src="{{ asset('js/uploader.js') }}"></script>
    <script src="{{ asset('js/dompurify.min.js') }}"></script>
    <script src="{{ asset('js/simditor.js') }}"></script>

    <script>
        $(function() {
            new Simditor({
                textarea: $('#editor'),
            });
        });
    </script>
@endsection
