@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Редактирование поста</h1>
    @include('posts._form', ['post' => $post, 'categories' => $categories, 'tags' => $tags])
</div>
@endsection
